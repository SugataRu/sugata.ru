<?php

require_once __DIR__.'/../config.php';
require_once mnminclude.'ban.php';

function error($mess)
{
    die(json_encode(array('error' => $mess)));
}

header('Content-Type: application/json; charset=UTF-8');

if (check_ban_proxy()) {
    error(_('IP не допускается'));
}

if (!($id=check_integer('id'))) {
    error(_('отсутствующий ID комментария'));
}

if (empty($_REQUEST['user'])) {
    error(_('ID пользователя отсутствует'));
}

if ($current_user->user_id != $_REQUEST['user']) {
    error(_('неправильный пользователь'). $current_user->user_id . '-'. htmlspecialchars($_REQUEST['user']));
}

if (!check_security_key($_REQUEST['key'])) {
    error(_('неверный контрольный ключ'));
}


if (empty($_REQUEST['value']) || ! is_numeric($_REQUEST['value'])) {
    error(_('отсутствие оценки'));
}

if ($current_user->user_karma < $globals['min_karma_for_post_votes']) {
    error(_('низкая карма, чтобы голосовать за комментарии'));
}

$value = intval($_REQUEST['value']);

if ($value != -1 && $value != 1) {
    error(_('неверное значение голосования'));
}

$vote = new Vote('posts', $id, $current_user->user_id);
$vote->link=$id;

if ($vote->exists()) {
    error(_('вы уже голосовали ранее'));
}

$votes_freq = intval($db->get_var("select count(*) from votes where vote_type='posts' and vote_user_id=$current_user->user_id and vote_date > subtime(now(), '0:0:30') and vote_ip_int = ".$globals['user_ip_int']));

$freq = 6;

if ($votes_freq > $freq) {
    if ($current_user->user_id > 0 && $current_user->user_karma > 4) {
        // Crazy votes attack, decrease karma
        // she does not deserve it :-)
        $user = new User;
        $user->id = $current_user->user_id;
        $user->read();
        $user->karma = $user->karma - 0.1;
        $user->store();
        error(_('Ваша карма ушла: ') . $user->karma);
    } else {
        error(_('Скорость ваших действий велика'));
    }
}

$vote->value = $value * $current_user->user_karma;

$post = Post::from_db($id);

if (! $post) {
    error(_('nota no existente'));
}

if ($post->author == $current_user->user_id) {
    error(_('no puedes votar a tus comentarios'));
}

if ($post->date < time() - $globals['time_enabled_votes']) {
    error(_('votos cerrados'));
}

if (! $post->insert_vote($current_user->user_id, $vote->value)) {
    error(_('ya ha votado antes'));
}

$dict = array();
$dict['id'] = $id;
$dict['votes'] = $post->votes + 1;
$dict['value'] = round($vote->value);
$dict['karma'] = round($post->karma + $vote->value);

die(json_encode($dict));
