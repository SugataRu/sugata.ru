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


$globals['description'] = 'Лучшие комментарии на Sugata. Отвечайте качественным содержанием и оно получит высокую оценку у пользователей.';

do_header(_('Самые ценные комментарии за 24 часа') . ' | ' . $globals['site_name']);
do_tabs('main', '+ ' . _('комментарии'), true);



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


echo '<div class="topheading"><h1>'._('Самые ценные комментарии за 24 часа').'</h1></div>';


$last_link = 0;
$counter = 0;

echo '<div class="comments" style="padding: 0 25px 25px 25px;">';

$min_date = date("Y-m-d H:00:00", time() - 86000); //  about 24 hours
$comments = $db->get_results("SELECT comment_id, link_id FROM comments, links WHERE comment_date > '$min_date' and link_id=comment_link_id ORDER BY comment_karma desc, link_id asc limit 25");
if ($comments) {
    foreach ($comments as $dbcomment) {
        $link = Link::from_db($dbcomment->link_id, null, false);
        $comment = Comment::from_db($dbcomment->comment_id);
        if ($last_link != $link->id) {
            echo '<h3>';
            echo '<a href="'.$link->get_relative_permalink().'">'. $link->title. '</a>';
            echo '</h3>';
        }
        echo '<ol class="comments-list">';
        echo '<li>';
        $comment->link_object = $link;
        $comment->print_summary(2000, false);
        echo '</li>';
        if ($last_link != $link->id) {
            $last_link = $link->id;
            $counter++;
        }
        echo "</ol>\n";
    }
}

echo '</div>';
echo '</div>';

do_footer();
