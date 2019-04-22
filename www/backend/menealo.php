<?php

require_once __DIR__ . '/../config.php';
require_once mnminclude . 'ban.php';

header('Content-Type: application/json; charset=UTF-8');
array_push($globals['cache-control'], 'no-cache');
http_cache();

if (check_ban_proxy()) {
    error(_('IP не разрешен'));
}

if (!($id = check_integer('id'))) {
    error(_('отсутствует ID статьи'));
}

if (empty($_REQUEST['user']) && $_REQUEST['user'] !== '0') {
    error(_('отсутствует код пользователя'));
}

if (!check_security_key($_REQUEST['key'])) {
    error(_('неправильный ключ управления'));
}

$link = Link::from_db($id, null, false);

if (!$link) {
    error(_('несуществующая статья'));
}

if (!$link->is_votable() || $link->total_votes == 0) {
    error(_('votos cerrados'));
}

// Only if the link has been not published, let them play
if ($current_user->user_id == 0 && $link->status != 'published') {
    if (!$anonnymous_vote) {
        error(_('Анонимные голоса временно отключены'));
    }

    // Check that there are not too much annonymous votes
    if ($link->status == 'published') {
        $anon_to_user_votes = max(10, $globals['anon_to_user_votes']);
    }

    // Allow more ano votes if published.
    if ($link->anonymous > $link->votes * $globals['anon_to_user_votes']) {
        error(_('Слишком много анонимных голосов для этой новости, зарегистрируйтесь в качестве пользователя или попробуйте позже'));
    }
}

if ($current_user->user_id != $_REQUEST['user']) {
    error(_('неправильный пользователь'));
}

if ($current_user->user_id == 0) {
    $ip_check = 'and vote_ip_int = ' . $globals['user_ip_int'];
} else {
    $ip_check = '';
}

$votes_freq = $db->get_var("select count(*) from votes where vote_type='links' and vote_user_id=$current_user->user_id and vote_date > subtime(now(), '0:0:30') $ip_check");

// Check the user is not a clon by cookie of others that voted the same link
if ($current_user->user_id > 0 && $link->status !== 'published') {
    if (UserAuth::check_clon_votes($current_user->user_id, $link->id, 5, 'links') > 0) {
        error(_('вы не можете голосовать с клонами'));
    }
}

$freq = ($current_user->user_id > 0) ? 3 : 2;

// Allow to play a little more if published
if ($link->status === 'published') {
    $freq *= 2;
}

// Check for clicks vs votes
// to avoid "cowboy votes" without reading the article
if (!empty($link->url) && $globals['click_counter'] && !$link->user_clicked()) {
    if ($link->votes > 3 && $link->negatives > 2 && $current_user->user_id > 0 && $link->votes / 10 < $link->negatives && $link->get_clicks() < $link->total_votes * 1.5) {
        error(_('непрочитанная ссылка, со многим негативом'));
    }

    // Check is not in "story" page
    if ((empty($_GET['l']) || $_GET['l'] != $link->id) && $link->total_votes > $link->get_clicks()) {
        // Don't allow to vote if it has less clicks than votes
        error(_('Вы не прочитали') . ' (' . $link->get_clicks() . ' < ' . $link->total_votes . ')');
    }
}

if ($votes_freq > $freq) {
    if ($current_user->user_id > 0 && $current_user->user_karma > 4 && $link->status !== 'published') {
        // Crazy votes attack, decrease karma
        // she does not deserve it :-)
        $user = new User($current_user->user_id);
        $user->add_karma(-0.2, _('voto cowboy'));

        error(_('¡tranquilo cowboy!') . ', ' . _('tu karma ha bajado: ') . $user->karma);
    }

    error(_('полегче, ковбой!'));
}

$value = ($current_user->user_id > 0) ? $current_user->user_karma : $globals['anon_karma'];

if (!$link->insert_vote($value)) {
    if ($current_user->user_id > 0) {
        error(_('вы уже проголосовали ранее тут'));
    }

    error(_('он уже проголосовал раньше IP'));
}

if ($link->status === 'discard' && $current_user->user_id > 0 && $link->votes > $link->negatives && $link->karma > 0) {
    $link->status = 'queued';
    $link->store_basic();
}

die($link->json_votes_info(intval($value)));

function error($message)
{
    die(json_encode(array('error' => $message)));
}
