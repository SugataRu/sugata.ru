<?php

require_once mnminclude.'favorites.php';

class LinkValidator
{
    public $link;
    public $user;
    public $user_id;
    public $errorCallback;
    public $warningCallback;

    public $error;
    public $warning;

    public $userDrafts;
    public $userVotes;
    public $userLinks;
    public $userSent;
    public $userSentRecent;
    public $linksQueded;

    public function __construct(Link $link)
    {
        global $current_user;

        $this->link = $link;
        $this->user = $current_user;
        $this->user_id = $current_user->user_id;
    }

    public function fixUrl()
    {
        $this->link->url = preg_replace('/#.*$/', '', clean_input_url(urldecode($this->link->url)));

        if (!preg_match('#^http(s)?://#', $this->link->url)) {
            $this->link->url = 'http://'.$this->link->url;
        }

        $this->fixUrlAmp();

        return $this;
    }

    public function fixUrlAmp()
    {
        $url = parse_url($this->link->url);

        if (!strstr($url['host'], '.cdn.ampproject.org')) {
            return;
        }

        $path = preg_replace('#^/([a-z]/)+#', '', $url['path']);
        $path = preg_replace('#^(amp|m)\.#', 'www.', $path);
        $path = preg_replace('#/amp$#', '', $path);
        $path = str_replace(['.amp.', '/amp/'], ['.', '/'], $path);

        $this->link->url = 'http://'.$path;
    }

    public function checkUrl()
    {
        $host = parse_url($this->link->url, PHP_URL_HOST);

        if (empty($host) || (gethostbyname($host) === $host)) {
            $this->setError(_('Отправленный URL не является действительным'), '', 'Hostname error: '.$this->link->url);
        }

        if (strlen($this->link->url) > 250) {
            $this->setError(_('URL слишком долго'), _('Длина URL превышает максимально допустимый размер (250 сиволов)'));
        }

        if (!filter_var($this->urlToAscii($this->link->url), FILTER_VALIDATE_URL)) {
            $this->setError(_('Отправленный URL не является действительным'));
        }

        return $this;
    }

    protected function urlToAscii($url)
    {
        $path = parse_url($url, PHP_URL_PATH);

        return str_replace($path, implode('/', array_map('urlencode', explode('/', $path))), $url);
    }

    public function checkKey()
    {
        global $site_key, $current_user;

        if ($_POST['key'] != md5($_POST['randkey'].$current_user->user_id.$current_user->user_email.$site_key.get_server_name())) {
            $this->setError(_('Неверный ключ процесса'));
        };

        return $this;
    }

    public function checkSiteSend()
    {
        if ($this->link->sub_id > 0 && !SitesMgr::can_send($this->link->sub_id) && $this->user->user_level !== 'god') {
            $this->setError(__('Отгрузки в %s отключены.', $this->link->sub_name));
        }

        return $this;
    }

    public function checkBasicData()
    {
        if (empty($this->link->title)) {
            $this->setError(_('Не имеет заголовка.'));
        }

        if (empty($this->link->tags)) {
            $this->setError(_('Не имеет тегов.'));
        }

        if (empty($this->link->sub_id)) {
            $this->setError(_('Нет пространства.'));
        }

        return $this;
    }

    public function checkDuplicates()
    {
        if ($this->user->admin || empty($this->link->url) || !($found = Link::duplicates($this->link->url))) {
            return $this;
        }

        $link = new Link;
        $link->id = $found;
        $link->read();

        $this->setError(
            _('Отгрузка продублирована'),
            __('Вы можете увидеть текущий <a href="%s">здесь</a>', $link->get_permalink())
        );
    }

    public function checkRemote($anti_spam)
    {
        global $site;

        if (!$this->link->get($this->link->url, null, $anti_spam)) {
            $this->setError(_('Неверная ссылка или не допускается'));
        }

        if ($this->link->valid) {
            return $this;
        }

        $e = _('Error leyendo la URL').': '.htmlspecialchars($this->link->url);

        if ($this->user->user_karma < 7 && $this->user->user_level === 'normal' && !$site->owner) {
            $this->setError($e, _('URL недействительный, неполный или не допускается.'));
        }

        $this->setWarning($e, _('Он недействителен, отключен или имеет механизмы антибота. <strong>Продолжить</strong>, но убедитесь, что это правильно.'));

        return $this;
    }

    public function checkLocal()
    {
        $components = parse_url($this->link->url);
        $quoted = preg_quote(get_server_name(), '/');

        if ($components['host'] === 'localhost') {
            $this->setError(_('Сервер локальный'), '', 'Имя сервера - локальное имя: '.$this->link->url);
        }

        if (preg_match('/^'.$quoted.'$/', $components['host']) && !strstr($this->link->url, '/my-story/')) {
            $this->setError(_('Сервер локальный'), '', 'Имя сервера - локальное имя: '.$this->link->url);
        }

        return $this;
    }

    public function checkBan($url = null)
    {
        require_once mnminclude.'ban.php';

        $url = $url ?: $this->link->url;

        if (!($ban = check_ban($url, 'hostname', false, true))) {
            return $this;
        }

        $info = _('Razón').': '.$ban['comment'];

        if ($ban['expire'] > 0) {
            $info .= ', '._('caduca').': '.get_date_time($ban['expire']);
        }

        $this->setError(_('На сервере есть заявка BAN'), $info, 'Имя сервера забанено: '.$url);
    }

    public function checkBanPunished($url = null)
    {
        require_once mnminclude.'ban.php';

        $url = $url ?: $this->link->url;

        if (!($ban = check_ban($url, 'punished_hostname', false, true))) {
            return $this;
        }

        $info = _('Лучше отправь ссылку на первоисточник');
        $info .= '.'._('Причина').': '.$ban['comment'];

        if ($ban['expire'] > 0) {
            $info .= ', '._('истекает').': '.get_date_time($ban['expire']);
        }

        $this->setWarning(_('Предупреждение').' '.$ban['match'], $info);
    }

    public function checkBanUser($url = null)
    {
        global $globals;

        require_once mnminclude.'ban.php';

        if (!($ban = check_ban($globals['user_ip'], 'ip', true)) && !($ban = check_ban_proxy())) {
            return $this;
        }

        if ($ban['expire'] > 0) {
            $info = _('Истекает').': '.get_date_time($ban['expire']);
        } else {
            $info = '';
        }

        $this->setError(_('С вашего комьютера не разрешено отправлять'), $info, 'Banned IP '.$globals['user_ip'].': '.$this->link->url);
    }

    public function checkDrafts()
    {
        global $globals, $db, $current_user;

        // Check the user does not have too many drafts
        if ($this->getUserDrafts() > $globals['draft_limit']) {
            if ($this->link->content_type === 'article') {
                $this->setError(
                    _('Слишком много черновиков'),
                    _('Вы делаете слишком много попыток. См. ').' <a href="'.$globals['base_url'].'user/'.$current_user->user_login.'/articles_discard">'._('список проектов').'</a>',
                    'too many drafts: '.$this->link->url
                );
            } else {
                $this->setError(
                    _('Слишком много черновиков'),
                    _('Вы сделали слишком много попыток. См. ').' <a href="'.$globals['base_url'].'queue?meta=_discarded">'._('список проектов').'</a>',
                    'too many drafts: '.$this->link->url
                );
            }
        }

        $db->query('
            DELETE FROM `links`
            WHERE (
                `link_author` = "'.$this->user_id.'"
                AND `link_content_type` != "article"
                AND `link_date` > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                AND `link_date` < DATE_SUB(NOW(), INTERVAL 10 MINUTE)
                AND `link_status` = "discard"
                AND `link_votes` = 0
            );
        ');

        return $this;
    }

    public function checkKarmaMin()
    {
        global $globals, $db;

        if ($globals['min_karma_for_links'] > 0 && $this->user->user_karma < $globals['min_karma_for_links']) {
            $this->setError(_('У вас нет минимальной кармы, чтобы отправить новую историю'));
        }

        return $this;
    }

    public function checkVotesMin()
    {
        global $globals, $db;

        $total = $this->getUserSent();

        if (!$globals['min_user_votes'] || $this->user->user_karma >= $globals['new_user_karma']) {
            return $this;
        }

        $user_votes = $this->getUservotes();

        if ($this->getUserSentRecent() === 0) {
            $min_votes = $globals['min_user_votes'];
        } else {
            $min_votes = min(4, intval($this->getLinksQueded() / 20)) * $this->getUserLinks();
        }

        if ($this->user->admin || $user_votes >= $min_votes) {
            return $this;
        }

        $needed = $min_votes - $user_votes;

        $this->setWarning(_('не голосуй поспешно '), '<a href="'.$globals['base_url'].'queue" target="_blank">'._('нажмите здесь, чтобы проголосовать').'</a>');

        if ($total === 0) {
            $this->setError(_('Вы первый раз отправляете историю?'), __('Тебе нужно как минимум %s голосов', $needed));
        } else {
            $this->setError(_('У вас нет минимального количества голосов, необходимых для отправки новой истории'), __('Вам необходимо минимум %s постов', $needed));
        }

        return $this;
    }

    public function checkClones()
    {
        global $globals, $db;

        $hours = intval($globals['user_links_clon_interval']);
        $clones = $this->user->get_clones($hours + 1);

        if ($hours <= 0 || !$clones) {
            return $this;
        }

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_status != "published"
                AND link_date > DATE_SUB(NOW(), INTERVAL '.$hours.' HOUR)
                AND link_author IN ('.implode(', ', $clones).')
            );
        ');

        if ($count) {
            $this->setError(
                _('Вы уже отправили это через клона...'),
                '',
                'Clon submit: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkUserNotPulished($hours, $limit)
    {
        global $globals, $db;

        if (empty($limit)) {
            return $this;
        }

        $queued = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                status != "published"
                AND `date` > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND link_author = "'.$this->user_id.'"
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        if ($queued > $limit) {
            $this->setError(
                __('Вы должны подождать, у вас слишком много отправлений в очереди за последние 24 часа (%s)', $queued),
                '',
                'Слишком много в очереди за 24 часа: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkUserQueued($minutes)
    {
        global $globals, $db;

        $limit = $globals['limit_3_minutes'];

        if ($this->user->user_karma > $globals['limit_3_minutes_karma']) {
            $limit *= 1.5;
        }

        // Check the number of links sent by the user in the last MINUTEs
        $queued = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_status = "queued"
                AND link_date > DATE_SUB(NOW(), INTERVAL '.(int) $minutes.' MINUTE)
                AND link_author = "'.$this->user_id.'"
            );
        ');

        if ($queued > $limit) {
            $this->setError(
                _('Превышение количество историй'),
                __('За последние 3 минуты отправлено слишком много историй (%s > %s)', $queued, $limit),
                'Too many queued: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkUserSame()
    {
        global $globals, $db;

        list($limit, $interval) = $this->getUserLimitInterval();

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.$interval.' HOUR)
                AND link_author = "'.$this->user_id.'"
            );
        ') - $this->getUserDrafts();

        if ($count > $limit) {
            $this->setError(_('Вы должны подождать, слишком много ссылок уже отправлено.'));
        }

        return $this;
    }

    public function checkUserIP()
    {
        global $globals, $db;

        list($limit, $interval) = $this->getUserLimitInterval();

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.$interval.' HOUR)
                AND link_ip = "'.$globals['user_ip'].'"
            );
        ') - $this->getUserDrafts();

        if ($count > $limit) {
            //$this->setError(_('Debes esperar, ya se enviaron varias desde esta misma IP'));
        }

        return $this;
    }

    public function checkUserNegatives()
    {
        global $globals, $db;

        list($limit, $interval) = $this->getUserLimitInterval();

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.$interval.' HOUR)
                AND link_author = "'.$this->user_id.'"
            );
        ') - $this->getUserDrafts();

        if ($count <= 1 || $this->user->user_karma >= $globals['karma_propaganda']) {
            return $this;
        }

        $positives = (int) $db->get_var('
            SELECT SUM(link_votes)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.$interval.' HOUR)
                AND link_author = "'.$this->user_id.'"
            );
        ');

        $negatives = (int) $db->get_var('
            SELECT SUM(link_negatives)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.$interval.' HOUR)
                AND link_author = "'.$this->user_id.'"
            );
        ');

        if ($negatives > 10 && $negatives > $positives * 1.5) {
            $this->setError(_('Вы должны подождать, у вас было слишком много отрицательных голосов в вашех недавних постах'));
        }

        return $this;
    }

    public function checkRatio($blog)
    {
        global $globals, $db;

        $sents = $this->getUserSent();

        if ($sents <= 30) {
            return $this;
        }

        $ratio = (float) $db->get_var('
            SELECT COUNT(DISTINCT link_blog) / COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL 60 DAY)
            );
        ');

        $threshold = 1 / log($sents, 2);

        if ($ratio >= $threshold) {
            return $this;
        }

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL 60 DAY)
                AND link_blog = "'.$blog->id.'"
            );
        ');

        if ($count > 2) {
            $this->setError(
                _('Вы уже отправили слишком много ссылок на одни и те же сайты'),
                _('Варьирует источники, можно считать спамом'),
                'Запрещено из-за низкой энтропии: '.$ratio.' < '.$threshold.': '.$this->link->url
            );
        }

        return $this;
    }

    public function checkMedia()
    {
        global $globals, $db;

        $sents = $this->getUserSent();

        if ($sents <= 5 || ($this->link->content_type !== 'image' && $this->link->content_type !== 'video')) {
            return $this;
        }

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL 60 DAY)
                AND link_content_type IN ("image", "video")
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        if ($count > $sents * 0.8) {
            $this->setError(
                _('Вы уже отправили слишком много  медиа'),
                '',
                'Запрещено из-за слишком большого количества медиа: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkMediaOverflow($hours)
    {
        global $globals, $db;

        if ($this->link->content_type !== 'image' && $this->link->content_type !== 'video') {
            return $this;
        }

        $limit = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND link_content_type IN ("image", "video")
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        if ($count > 5 && $count > $limit * 0.15) {
            // Only 15% images AND videos
            $this->setError(
                _('Слишком много фото уже отправлено, пожалуйста, подождите несколько минут'),
                __('Всего за 12 часов было % - текущий максимум %s', $count, intval($limit * 0.05)),
                'Запрещено из-за переполнения изображения: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkBlogSame($blog, $hours)
    {
        global $globals, $db;

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND link_author = "'.$this->user_id.'"
                AND link_blog = "'.$blog->id.'"
                AND link_votes > 0
            );
        ');

        if ($count >= $globals['limit_same_site_24_hours']) {
            $this->setError(
                _('Слишком много ссылок на один и тот же сайт за последние часы'),
                '',
                'Запрещено из-за слишком большого количества ссылок на один и тот же сайт в прошлом '.$hours.' часов: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkBlogFast($blog, $minutes)
    {
        global $globals, $db;

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $minutes.' MINUTE)
                AND link_author = "'.$this->user_id.'"
                AND link_blog = "'.$blog->id.'"
                AND link_votes > 0
            );
        ');

        if ($count && $this->user->user_karma < 12) {
            $this->setError(
                _('Вы уже отправили ссылку на тот же сайт недавно'),
                __('Вы должны подождать %s минут, чтобы повторить отправку на этот сайт.', $minutes),
                'Запрещено из-за короткого периода между ссылками на один и тот же сайт: '.$this->link->url
            );
        }

        return $this;
    }

    public function checkBlogHistory($blog, $days)
    {
        global $globals, $db;

        $sents = $this->getUserSent();

        if ($sents <= 3) {
            return $this;
        }

        $count = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL '.(int) $days.' DAY)
                AND link_blog = "'.$blog->id.'"
            );
        ');

        $ratio = $count / $sents;

        if (!$count || ($ratio <= 0.5)) {
            return $this;
        }

        $e = __('Вы отправили слишком много ссылок на %s', $blog->url);

        if ($sents > 5 && $ratio > 0.75) {
            $this->setError(
                $e,
                _('Вы превысили лимиты достов этого сайта'),
                'Процесс прерван: '.$this->link->url
            );
        }

        $this->setWarning(
            $e,
            _('Продолжайте, но имейте в виду, что вы можете получить отрицательные голоса').', '.'<a href="'.$globals['base_url'].$globals['legal'].'">'._('условия использования').'</a>'.
            'предупреждение, продолжить: '.$this->link->url
        );

        return $this;
    }

    public function checkBlogOverflow($blog, $hours)
    {
        global $globals, $db;

        // check there is no an 'overflow' FROM the same site
        $site = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND link_blog = "'.$blog->id.'"
                AND link_status = "queued"
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        if ($site <= 10) {
            return $this;
        }

        $time = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links, subs, sub_statuses
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL '.(int) $hours.' HOUR)
                AND sub_statuses.link = link_id
                AND subs.id = sub_statuses.id
                AND sub_statuses.origen = sub_statuses.id
                AND subs.parent = 0
                AND subs.owner = 0
            );
        ');

        if ($site > $time * 0.05) {
            // Only 5% FROM the same site
            $this->setError(
                _('Слишком много очередей с одного сайта, подождите несколько минут, пожалуйста'),
                __('Всего за 12 часов %s а текущий максимум %s', $site, intval($time * 0.05)),
                'Запрещено из-за переполнения на тот же сайт: '.$this->link->url
            );
        }

        return $this;
    }

    public function getUserLimitInterval()
    {
        global $globals;

        if ($this->getUserSent()) {
            $limit = $globals['user_links_limit'];
            $interval = $globals['user_links_interval'];
        } else {
            $limit = $globals['new_user_links_limit'];
            $interval = $globals['new_user_links_interval'];
        }

        return array($limit, intval($interval / 3600));
    }

    public function getUserDrafts()
    {
        global $globals, $db;

        if ($this->userDrafts !== null) {
            return $this->userDrafts;
        }

        $minutes = intval($globals['draft_time'] / 60) + 10;

        return $this->userDrafts = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL '.$minutes.' MINUTE)
                AND link_status = "discard"
                AND link_votes = 0
            );
        ');
    }

    public function getUserVotes()
    {
        global $globals, $db;

        if ($this->userVotes !== null) {
            return $this->userVotes;
        }

        return $this->userVotes = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM votes
            WHERE (
                vote_type = "links"
                AND vote_date > DATE_SUB(NOW(), INTERVAL 72 HOUR)
                AND vote_user_id = "'.$this->user_id.'"
            );
        ');
    }

    public function getUserLinks()
    {
        global $globals, $db;

        if ($this->userLinks !== null) {
            return $this->userLinks;
        }

        return $this->userLinks = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND link_status != "discard"
            );
        ') + 1;
    }

    public function getUserSent()
    {
        global $globals, $db;

        if ($this->userSent !== null) {
            return $this->userSent;
        }

        return $this->userSent = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE link_author = "'.$this->user_id.'";
        ') - $this->getUserDrafts();
    }

    public function getUserSentRecent()
    {
        global $globals, $db;

        if ($this->userSentRecent !== null) {
            return $this->userSentRecent;
        }

        return $this->userSentRecent = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_author = "'.$this->user_id.'"
                AND link_date > DATE_SUB(NOW(), INTERVAL 60 DAY)
            );
        ') - $this->getUserDrafts();
    }

    public function getLinksQueded()
    {
        global $globals, $db;

        if ($this->linksQueded !== null) {
            return $this->linksQueded;
        }

        return $this->linksQueded = (int) $db->get_var('
            SELECT SQL_CACHE COUNT(*)
            FROM links
            WHERE (
                link_date > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND link_status = "queued"
            );
        ');
    }

    public function setErrorCallback($callback)
    {
        if (is_callable($callback)) {
            $this->errorCallback = $callback;
        }
    }

    public function setWarningCallback($callback)
    {
        if (is_callable($callback)) {
            $this->warningCallback = $callback;
        }
    }

    private function setError($title, $info = null, $syslog = '')
    {
        $this->error = [
            'title' => $title,
            'info' => $info,
            'syslog' => $syslog,
        ];

        if ($this->errorCallback) {
            call_user_func($this->errorCallback, $this->error);
        }

        throw new Exception($title);
    }

    private function setWarning($title, $info = null, $syslog = '')
    {
        $this->warning = [
            'title' => $title,
            'info' => $info,
            'syslog' => $syslog,
        ];

        if ($this->warningCallback) {
            call_user_func($this->warningCallback, $this->warning);
        }
    }
}
