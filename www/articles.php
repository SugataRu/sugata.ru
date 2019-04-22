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

meta_get_current();

$globals['tag_status'] = 'queued';

$page_size = $globals['page_size'] * 2;
$offset = (get_current_page() - 1) * $page_size;
$rows = -1;

$globals['description'] = 'Статьи размещенные на Sugata. Пибликуйте интересные материалы, ведите блоги, голосуйте за интересные новости.';

Link::$original_status = true; // Show status in original sub

$pagetitle = _('Статьи на Sugata');

if ($page > 1) {
    $pagetitle .= " ($page)";
}

do_header($pagetitle, _('статьи'), false);



/*** SIDEBAR ****/
echo '<div id="sidebar">';

do_sub_message_right();
do_banner_right();

if ($globals['show_popular_queued']) {
    do_best_queued();
}

do_last_subs('queued', 15, 'link_karma');
do_vertical_tags('queued');


do_footer_menu();

echo '</div>' . "\n";
/*** END SIDEBAR ***/

echo '<div id="newswrap">'."\n";

$site = SitesMgr::get_info();

$sql = '
    SELECT '.Link::SQL.' INNER JOIN (
        SELECT link
        FROM sub_statuses, subs, links
        WHERE (
            link_content_type = "article"
            AND link_status IN ("queued", "published")
            AND sub_statuses.link = link_id
            AND sub_statuses.id = sub_statuses.origen
            AND sub_statuses.date > "'.date('Y-m-d H:00:00', $globals['now'] - $globals['time_enabled_votes']).'"
            AND sub_statuses.origen = subs.id
            '.($site->sub ? ('AND subs.id = "'.$site->id.'"') : '').'
        )
        ORDER BY sub_statuses.date DESC
        LIMIT '.$offset.', '.$page_size.'
    ) AS ids ON (ids.link = link_id);
';

$links = $db->get_results($sql, 'Link');

if ($links) {
    foreach ($links as $link) {
        if ($link->votes == 0 && $link->author != $current_user->user_id) {
            continue;
        }

        $link->max_len = 600;
        $link->print_summary('full', ($offset < 1000) ? 16 : null, false);
    }
}

do_pages($rows, $page_size);

echo '</div>'."\n";


do_footer();
