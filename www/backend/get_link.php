<?php

if (empty($_GET['id'])) {
    die;
}

if (!defined('mnmpath')) {
    require_once __DIR__ . '/../config.php';

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: public, s-maxage=300');
}

$id = intval($_GET['id']);
$link = Link::from_db($id);

if (!$link) {
    die;
}

echo '<p>';
    if ($link->avatar) {
        echo '<img class="avatar" src="'.get_avatar_url($link->author, $link->avatar, 40).'" width="40" height="40" alt="avatar"  style="float:left; margin: 0 5px 0 0;"/>';
    }

    echo '<strong>'.$link->title.'</strong><br/>';
    echo _('автор').' <strong>'.$link->username.'</strong><br/>';
    echo '<strong>'.$link->sub_name.'&nbsp;|&nbsp;карма:&nbsp;'.intval($link->karma).'&nbsp;|&nbsp;'._('отрицательный').':&nbsp;'. $link->negatives. '</strong>';
echo '</p>';

echo '<p style="margin-top: 4px">';
    if (($image = $link->has_thumb())) {
        echo "<img src='$image' width='$link->thumb_x' height='$link->thumb_y' alt='' class='thumbnail'/>";
    }

    echo $link->to_html($link->content);
echo '</p>';
