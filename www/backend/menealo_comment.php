<?php

include __DIR__.'/../config.php';
require_once mnminclude . 'ban.php';

header('Content-Type: application/json; charset=UTF-8');
array_push($globals['cache-control'], 'no-cache');
http_cache();

if (check_ban_proxy()) {
    error(_('Вы не можете голосовать'));
}

if (!($id = check_integer('id'))) {
    error(_('отсутствующий ID комментария'));
}

if (empty($_REQUEST['user'])) {
    error(_('код пользователя отсутствует'));
}

if ($current_user->user_id != $_REQUEST['user']) {
    error(_('неправильный пользователь'));
}

if (!check_security_key($_REQUEST['key'])) {
    error(_('неверный контрольный ключ'));
}

if (empty($_REQUEST['value']) || !is_numeric($_REQUEST['value'])) {
    error(_('отсутствие оценки'));
}

if ($current_user->user_karma < $globals['min_karma_for_comment_votes']) {
    error(_('низкая карма, чтобы голосовать'));
}

$value = intval($_REQUEST['value']);

if ($value != -1 && $value != 1) {
    error(_('Неверное значение голосования'));
}

if ($value < 0 && $current_user->user_id == (int) $db->get_var("select link_author from links, comments where comment_id = $id and link_id = comment_link_id")) {
    error(_('Не голосуйте отрицательно за комментарии к вашими данными'));
}

$comment = new Comment();
$comment->id = $id;

if (!$comment->read_basic()) {
    error(_('несуществующий комментарий'));
}

if ($comment->author == $current_user->user_id) {
    error(_('Вы не можете голосовать за ваши комментарии'));
}

if ($comment->date < time() - $globals['time_enabled_comments']) {
    error(_('закрытые голоса'));
}

// Check the user is not a clon by cookie of others that voted the same comment
if (UserAuth::check_clon_votes($current_user->user_id, $id, 5, 'comments') > 0) {
    error(_('не пытайтесь использовать клона!'));
}

if ($value > 0) {
    $votes_freq = intval($db->get_var("select count(*) from votes where vote_type='comments' and vote_user_id=$current_user->user_id and vote_date > subtime(now(), '0:0:30') and vote_value > 0 and vote_ip_int = " . $globals['user_ip_int']));
    $freq = 10;
} else {
    $votes_freq = intval($db->get_var("select count(*) from votes where vote_type='comments' and vote_user_id=$current_user->user_id and vote_date > subtime(now(), '0:0:30') and vote_value <= 0 and vote_ip_int = " . $globals['user_ip_int']));
    $freq = 5;
}

if ($votes_freq > $freq) {
    if (!$current_user->user_id || $current_user->user_karma <= 4) {
        error(_('Тихо, тихо!'));
    }

    // Crazy votes attack, decrease karma
    // she does not deserve it :-)
    $user = new User($current_user->user_id);
    $user->add_karma(-0.2, _('Голосуйте за комментарии'));

    error(_('Спокойный ковбой, твоя карма сошла: ') . $user->karma);
}

// EXPERIMENTAL: the negative karma to comments depends upon the number of comments and posts
$hours = 168;
$comments = $db->get_var("select count(*) from comments where comment_user_id = $current_user->user_id and comment_date > date_sub(now(), interval $hours hour) and comment_karma > 0");
$posts = $db->get_var("select count(*) from posts where post_user_id = $current_user->user_id and post_date > date_sub(now(), interval $hours hour) and post_karma >= 0");
$negatives = $db->get_var("select count(*) from votes where vote_type = 'comments' and vote_user_id = $current_user->user_id and vote_date > date_sub(now(), interval $hours hour) and vote_value < 0");

if (!$current_user->admin && !$current_user->special) {
    if ($value < 0) {
        $points = 2 * $comments + $posts - $negatives;
        $value = round(-1 * max(min($points, $current_user->user_karma), 1)); // Min is -1
    } else {
        $points = 4 * $comments + $posts - $negatives;
        $value = round(max(min($points, $current_user->user_karma), 3)); // Min is 3
    }
} else {
    $value = round($value * $current_user->user_karma);
}

if (!$comment->insert_vote($value)) {
    error(_('вы уже проголосовали ранее с этого IP'));
}

$comment->votes++;
$comment->karma += $value;

$dict = array();
$dict['id'] = $id;
$dict['votes'] = $comment->votes;
$dict['value'] = $value;
$dict['karma'] = $comment->karma;

die(json_encode($dict));

function error($error)
{
    die(json_encode(array('error' => $error)));
}
