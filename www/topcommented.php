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

$page_size = $globals['page_size'];

$range_names  = array(_('24 часа'), _('48 часов'), _('неделя'), _('месяц'));
$range_values = array(1, 2, 7, 30);

$offset=(get_current_page()-1)*$page_size;

$from = intval($_GET['range']);
if ($from >= count($range_values) || $from < 0) {
    $from = 0;
}

$globals['description'] = 'Саме комментируемые истории на Sugata. Напишите интересную тему и она вызовет неподдельный интерес у читателей. Её будут комментировать и она будет тут.';

if ($range_values[$from] > 0) {
    // we use this to allow sql caching
    $from_time = '"'.date("Y-m-d H:00:00", time() - 86400 * $range_values[$from]).'"';
    $sql = "SELECT link_id, link_comments as comments FROM links, sub_statuses WHERE id = ".SitesMgr::my_id()." AND status = 'published' AND date > $from_time AND link_id = link ORDER BY link_comments DESC ";
    $time_link = "date > FROM_UNIXTIME($from_time)";
} else {
    $sql = "SELECT link_id, link_comments as comments FROM links, sub_statuses  WHERE id = ".SitesMgr::my_id()." AND status = 'published' AND link_id = link ORDER BY link_comments DESC ";
    $time_link = '';
}

do_header(_('Cамые комментируемые новости') . ' | ' . $globals['site_name']);
do_tabs('main', _('комментируемые новости'), true);
print_period_tabs();

/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_banner_right();
do_best_stories();
do_best_comments();
do_vertical_tags('published');

do_footer_menu();

echo '</div>' . "\n";
/*** END SIDEBAR ***/

echo '<div id="newswrap">'."\n";


echo '<div class="topheading"><h1>'._('Cамые комментируемые новости').'</h1></div>';

$link = new Link;

// Use memcache if available
if ($globals['memcache_host'] && get_current_page() < 4) {
    $memcache_key = 'topcommented_'.$globals['site_shortname'].$from.'_'.get_current_page();
}

if (!($memcache_key
        && ($rows = memcache_mget($memcache_key.'rows'))
        && ($links = unserialize(memcache_mget($memcache_key))))) {
    // It's not in memcache

    $rows = -1; // min(100, $db->get_var("SELECT count(*) FROM links"));

    $links = $db->get_results("$sql LIMIT $offset,$page_size");
    if ($memcache_key) {
        memcache_madd($memcache_key.'rows', $rows, 1800);
        memcache_madd($memcache_key, serialize($links), 1800);
    }
}


if ($links) {
    foreach ($links as $dblink) {
        $link->id=$dblink->link_id;
        $link->read();
        $link->print_summary('short');
    }
}
do_pages($rows, $page_size);
echo '</div>';

do_footer();

function print_period_tabs()
{
    global $globals, $current_user, $range_values, $range_names;

    if (!($current_range = check_integer('range')) || $current_range < 1 || $current_range >= count($range_values)) {
        $current_range = 0;
    }
    echo '<ul class="subheader">'."\n";
    for ($i=0; $i<count($range_values) /*&& $range_values[$i] < 40 */; $i++) {
        if ($i == $current_range) {
            $active = ' class="selected"';
        } else {
            $active = "";
        }
        echo '<li'.$active.'><a href="top_commented?range='.$i.'">' .$range_names[$i]. '</a></li>'."\n";
    }
    echo '</ul>'."\n";
}
