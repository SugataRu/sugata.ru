<?php
// The source code packaged with this file is Free Software, Copyright (C) 2010 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//        http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';
require_once mnminclude.'search.php';

$globals['description'] = 'Поиск по историям, статьям, комментариям, блогам и тегам на Sugata. Все результаты в одном месте. Выбор параметра поиска.';



$globals['extra_js'][] = 'autocomplete/jquery.autocomplete.min.js';
$globals['extra_css'][] = 'jquery.autocomplete.css';
$globals['extra_js'][] = 'jquery.user_autocomplete.js';

$page_size = $globals['page_size'];
$offset = (get_current_page() - 1) * $page_size;

$globals['noindex'] = true;

$response = do_search(false, $offset, $page_size);

do_header(sprintf(_('поиск по «%s»'), htmlspecialchars($_REQUEST['words'])));
do_tabs('main', _('поиск'), __($_SERVER['REQUEST_URI']));

switch ($_REQUEST['w']) {
    case 'posts':
        $rss_program = 'sneakme_rss';
        break;

    case 'comments':
        $rss_program = 'comments_rss';
        break;

    default:
        $rss_program = 'rss';
}

/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_banner_right();
do_rss_box($rss_program);

do_footer_menu();

echo '</div>'."\n";
/*** END SIDEBAR ***/

$options = array(
    'w' => array('links', 'posts', 'comments'),
    'p' => array('' => _('поля...'), 'url', 'tags', 'title', 'site'),
    's' => array('' => _('состояние...'), 'published', 'queued', 'discard', 'autodiscard', 'abuse'),
    'h' => array('' => _('период...'), 24 => _('24 часа'), 48 => _('48 часов'), 24 * 7 => _('неделю'), 24 * 30 => _('месяц'), 24 * 180 => _('6 месяцев'), 24 * 365 => _('1 год')),
    'o' => array('' => _('по релевантности'), 'date' => _('по дате')),
);

$selected = array_intersect_key($_REQUEST, $options);

Haanga::Load('search.html', compact('options', 'selected', 'response', 'rss_program'));

 
do_footer();

function print_result()
{
    global $response, $page_size, $current_user;

    if (empty($response['ids'])) {
        return;
    }

    $rows = min($response['rows'], 1000);

    foreach ($response['ids'] as $id) {
        switch ($_REQUEST['w']) {
            case 'posts':
                $obj = Post::from_db($id);
                break;

            case 'comments':
                $obj = Comment::from_db($id);
                break;

            default:
                $obj = Link::from_db($id);
        }

        if (!$obj) {
            continue;
        }

        $obj->basic_summary = true;

        switch ($_REQUEST['w']) {
            case 'posts':
                $obj->print_summary(800);
                break;

            case 'comments':
                if (($obj->type === 'admin') && !$current_user->admin) {
                    continue;
                }

                $obj->print_summary(800);
                break;

            default:
                $obj->max_len = 600;
                $obj->print_summary();
        }
    }

    do_pages($rows, $page_size);
}
