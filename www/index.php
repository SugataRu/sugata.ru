<?php
// The source code packaged with this file is Free Software, Copyright (C) 2010 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//        http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019

//print $_SERVER['SERVER_NAME'];

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';

meta_get_current();

$page_size = $globals['page_size'];
$page = get_current_page();
$offset = ($page - 1) * $page_size;
$globals['ads_section'] = 'portada';

$globals['url_str'] = 'home';

$pagetitle = $globals['site_name'];

if ($page > 1) {
    $pagetitle .= " ($page)";
}

$from = '';

switch ($globals['meta']) {
    case '_subs':
        if ($current_user->user_id && $current_user->has_subs) {
            $from_time = '"' . date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_comments']) . '"';
            $where = "id IN ($current_user->subs) AND status='published' AND id = origen AND date > $from_time";
            $rows = -1;

            $tab_option = 7; // Show "personal" as default

            Link::$original_status = true; // Show status in original sub

            break;
        }

    // NOTE: If the user has no subscriptions it will fall into next: _*
    case '_*':
        $from_time = '"' . date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_comments']) . '"';
        $from = ", subs";
        $where = "sub_statuses.status='published' AND sub_statuses.id = sub_statuses.origen AND sub_statuses.date > $from_time AND sub_statuses.origen = subs.id AND subs.owner > 0";
        $rows = -1;
        $tab_option = 8;

        Link::$original_status = true; // Show status in original sub

        break;

    case '_friends':
        if (!$current_user->user_id > 0) {
            do_error(_('debe autentificarse'), 401); // Check authenticated users
        }

        $from_time = '"' . date("Y-m-d H:00:00", $globals['now'] - 86400 * 4) . '"';
        $from = ", friends, links";
        $where = "sub_statuses.id = " . SitesMgr::my_id() . " AND date > $from_time AND status = 'published' AND friend_type = 'manual' AND friend_from = $current_user->user_id AND friend_to=link_author AND friend_value > 0 AND link_id = link";
        $rows = -1;
        $tab_option = 1; // Friends

        break;

    default:
        $tab_option = 0; // All
        $rows = Link::count('published');
    $where = "sub_statuses.id = " . SitesMgr::my_id() . " AND status = 'published' ";
	  //1 - Интернет, 2 - наука, 4- сугата, 13 - ошибки
//$where = "id IN (1,2,4) AND status='published' AND id = origen ";


if ($globals['uri'] == '/' || $globals['uri'] == '/?page=2'  || $globals['uri'] == '/?page=3'  || $globals['uri'] == '/?page=4') {
    $where = "id IN (1,2,4,10,13,14) AND status='published' AND id = origen ";
} else{
    $where = "sub_statuses.id = " . SitesMgr::my_id() . " AND status = 'published' ";
}

}



$this_space = SitesMgr::get_info();

/*
print '<br>1<br>1<br>';
print_r($this_space);
print '<br>1<br>1<br>';
print_r($this_space->name_long);
*/

if ($this_space->name_long != null) {
	
	
	if ($globals['uri'] == '/') {
		$pagetitle = 'Sugata — лучшее в сети ';
		$globals['description'] = 'На Sugata можно поделиться историями, ссылками. Размещать комментраии, публиковать статьи, зарабатывать карму и голосовать за лучшие посты.';
		$globals['home'] = '1';
	} else{
		$pagetitle = $this_space->name_long . ' — Sugata ';
		$globals['description'] = $this_space->name_long . ' | поделитесь этой тематикой на Sugata. Пишите комментраии, веди блоги, зарабатывайте карму.';
	}

	} else {
		
	$globals['description'] = 'Присоединяйтесь к сообществу Sugata: делитесь интересными ссылками, комментируйте новости, ведите блоги. Поделитесь историей.';

 
}

//do_header($pagetitle);

if ($globals['uri'] == '/?meta=_*') {
do_header($pagetitle, _('S/+'), false, $tab_option);
	} else{ 
do_header($pagetitle, _('Главная'), false, $tab_option);
}


if ($tab_option == 0) {
    Haanga::Load('site_search_box.html'); // Search box for search engines
}

/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_my_menu();
do_sub_message_right();
do_banner_right();
do_last_subs('published');

do_my_subs();

if ($globals['preguntame_home_sidebar']) {
    do_sidebar_preguntame();
}

if ($globals['show_popular_published']) {
    do_active_stories();
}

// do_banner_promotions();

if ($globals['show_popular_published']) {
//    do_most_clicked_stories();
    do_best_stories();
}

do_banner_promotions();

// do_best_sites();

//do_most_clicked_sites();

if ($page < 2) {
    do_best_comments();
}

// do_categories_cloud('published');

//do_vertical_tags('published');

do_footer_menu();

// do_last_blogs();
echo '</div>';
/*** END SIDEBAR ***/

echo '<div id="newswrap">';
do_sub_description();

do_banner_top_news();

$globals['site_id'] = SitesMgr::my_id();

if ($page == 1 && empty($globals['meta']) && ($top = Link::top())) {
    Haanga::Load('link_top.html', array('self' => $top));
}

$order_by = 'ORDER BY date DESC ';

if (($sponsor = Sponsor::getCurrent()) && ($sponsored_link = Link::from_db($sponsor->link))) {
    $where .= ' AND link != "'.$sponsored_link->id.'" ';
} else {
    $sponsored_link = null;
}

if (!$rows) {
    $rows = $db->get_var('SELECT SQL_CACHE count(*) FROM sub_statuses '.$from.' WHERE '.$where.';');
}

// We use a "INNER JOIN" in order to avoid "order by" whith filesorting. It was very bad for high pages
$sql = '
    SELECT '.Link::SQL.'
    INNER JOIN (
        SELECT link
        FROM sub_statuses '.$from.'
        WHERE '.$where.'
        '.$order_by.'
        LIMIT '.$offset.', '.$page_size.'
    ) AS ids ON (ids.link = link_id);
';

//print_r($sql);

$links = $db->get_results($sql, 'Link');

if ($links) {
    $all_ids = array_map(function ($value) {
        return $value->id;
    }, $links);

    $pollCollection = new PollCollection;
    $pollCollection->loadSimpleFromRelatedIds('link_id', $all_ids);

    $official_subs = get_data_widget_official_subs();

    if ($globals['show_promoted_articles'] ) {
        $promoted_articles = Link::getPromotedArticles();
    } else {
        $promoted_articles = [];
    }

    if ($globals['show_widget_popular_articles']) {
        $widget_popular_articles = Link::getPopularArticles();
    } else {
        $widget_popular_articles = [];
    }

    $counter = 0;

    foreach ($links as $link) {
        $link->poll = $pollCollection->get($link->id);
		
        $link->max_len = 300;

        if ($globals['show_promoted_articles'] ) {
            Haanga::Safe_Load('private/promoted_articles.html', compact('counter', 'promoted_articles'));
        }

        Haanga::Safe_Load('private/ad-interlinks.html', [
            'counter' => $counter++,
            'page_size' => $page_size,
            'self' => $sponsored_link,
            'official_subs' => $official_subs,
            'widget_popular_articles' => $widget_popular_articles
        ]);

        $link->print_summary();
    }
}

do_pages($rows, $page_size);
echo '</div>';


do_footer();

exit(0);
