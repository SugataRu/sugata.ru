<?php

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';
$left_options['selected'] = 'queue';
$page_size = $globals['page_size'] * 2;

meta_get_current();

$page = get_current_page();
$offset = ($page-1)*$page_size;
$rows = -1; // Don't show page numbers by default
$from = '';


$globals['description'] = 'Новости, истории ожидающие утверждения на Sugata. Данные новости смогут попасть на центральную страницу сайта при достижении некотороого значения кармы.';

switch ($globals['meta']) {
    case '_subs':
        if ($current_user->user_id && $current_user->has_subs) {
            $globals['tag_status'] = 'queued';
            $from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_votes']).'"';
            $where = "id in ($current_user->subs) AND status='queued' and id = origen and date > $from_time";
            $order_by = "ORDER BY date DESC";
            $rows = -1;
            $tab = 7;
            Link::$original_status = true; // Show status in original sub
            break;
        }

    // NOTE: If the user has no subscriptions it will fall into next: _*
    case '_*':
        $globals['tag_status'] = 'queued';
        $from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_votes']).'"';
        $from = ", subs";
        $where = "sub_statuses.status='queued' AND sub_statuses.id = sub_statuses.origen and sub_statuses.date > $from_time and sub_statuses.origen = subs.id and subs.owner > 0";
        $order_by = "ORDER BY sub_statuses.date DESC";
        $rows = -1;
        $tab = 8;
        Link::$original_status = true; // Show status in original sub
        break;

    case '_friends':
        $globals['noindex'] = true;
        $from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_votes']).'"';
        $from = ", friends, links";
        $where = "sub_statuses.id = ". SitesMgr::my_id() ." AND date > $from_time and status='queued' and friend_type='manual' and friend_from = $current_user->user_id and friend_to=link_author and friend_value > 0 and link_id = link";
        $rows = -1;
        $order_by = "ORDER BY date DESC";
        $tab = 2;
        $globals['tag_status'] = 'queued';
        break;

    case '_popular':
        // Show  the hihgher karma first
        $globals['noindex'] = true;
        $from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - 86400*4).'"';
        $from = ", links, link_clicks";
        $where = "sub_statuses.id = ". SitesMgr::my_id() ." AND date > $from_time and status='queued' and link = link_id and link_id = link_clicks.id and link_clicks.counter/(link_votes+link_negatives) > 1.3 and link_karma > 20 ";
        $order_by = "ORDER BY link_karma DESC";
        $rows = -1;
        $tab = 3;
        $globals['tag_status'] = 'queued';
        break;

    case '_discarded':
        // Show only discarded in four days
        $globals['noindex'] = true;
        $globals['ads'] = false;
        $from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - 86400*4).'"';
        $where = "sub_statuses.id = ". SitesMgr::my_id() ." AND status in ('discard', 'abuse', 'autodiscard') " ;
        $order_by = "ORDER BY date DESC ";
        $tab = 5;
        $globals['tag_status'] = 'discard';
        $rows = Link::count('discard') + Link::count('autodiscard') + Link::count('abuse');
        break;

    default:
        $globals['tag_status'] = 'queued';
        $order_by = "ORDER BY date DESC";
        $rows = Link::count('queued');
        $where = "sub_statuses.id = ". SitesMgr::my_id() ." AND status='queued' ";
        $tab = 1;
        break;
}

$pagetitle = _('Новости, проекты на Sugata');
$active[$option] = 'queue';
if ($page > 1) {
    $pagetitle .= " ($page)";
}

do_header($pagetitle, _('Очередь'), false, $tab);

/*** SIDEBAR ****/
echo '<div id="sidebar">';
    do_sub_message_right();
    do_banner_right();

    if ($globals['show_popular_queued']) {
        do_best_queued();
    }

    do_last_subs('queued', 15, 'link_karma');
    //do_last_blogs();
    //do_best_comments();
    //do_categories_cloud('queued', 24);
    do_banner_promotions();
    do_vertical_tags('queued');
	
	do_footer_menu();
	
echo '</div>' . "\n";
/*** END SIDEBAR ***/

echo '<div id="newswrap">'."\n";
    $sql = "SELECT".Link::SQL."INNER JOIN (SELECT link FROM sub_statuses $from WHERE $where $order_by LIMIT $offset,$page_size) as ids on (ids.link = link_id)";

    $links = $db->get_results($sql, "Link");

    if ($links) {
        $all_ids = array_map(function ($value) {
            return $value->id;
        }, $links);

        $pollCollection = new PollCollection;
        $pollCollection->loadSimpleFromRelatedIds('link_id', $all_ids);

        $counter = 0;

        foreach ($links as $link) {
            if ($link->votes == 0 && $link->author != $current_user->user_id) {
                continue;
            }

            $link->poll = $pollCollection->get($link->id);
            $link->max_len = 800;

            Haanga::Safe_Load('private/ad-interlinks.html', [
                'counter' => $counter,
                'page_size' => $page_size
            ]);

            $link->print_summary('full', ($offset < 1000) ? 16 : null);
            $counter++;
        }
    }

    do_pages($rows, $page_size);
echo '</div>'."\n";


do_footer();
