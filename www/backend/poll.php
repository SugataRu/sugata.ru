<?php

if (!defined('mnmpath')) {
    require_once __DIR__.'/../config.php';
    require_once mnminclude.'html1.php';
}

array_push($globals['cache-control'], 'no-cache');
http_cache();

if (empty($current_user->user_id)) {
    die('ERROR: '._('Это действие возможно только для зарегистрированных пользователей'));
}

$_POST = array_map('intval', $_POST);

if (empty($_POST['id'])) {
    die('ERROR: '._('Не удалось получить запрошенный опрос'));
}

if (empty($_POST['option']) || empty($_POST['option'])) {
    die('ERROR: '._('Указанный вариант голосования недействителен'));
}

$poll = new Poll;
$poll->id = $_POST['id'];

if (!$poll->read()) {
    die('ERROR: '._('Указанный опрос не существует'));
}

if ($poll->voted) {
    die('ERROR: '._('Вы уже проголосовали в этом опросе'));
}

if ($poll->finished) {
    die('ERROR: '._('Опрос уже закрыт'));
}

if (!($option = $poll->getOption($_POST['option']))) {
    die('ERROR: '._('Проголосованный вариант не существует для этого опроса'));
}

// Check the user is not a clon by cookie of others that voted the same comment
if (UserAuth::check_clon_votes($current_user->user_id, $poll->id, 5, 'polls')) {
    die('ERROR: '._('Вы не можете голосовать с клонами'));
}

// Verify that there are a period of $globals['polls_min_time_for_votes'] seconds between votes
if (Vote::fast_vote('polls', $globals['polls_min_time_for_votes'])) {
    die('ERROR: '.sprintf(_('Вы должны ждать %s секунд между голосованием'), $globals['polls_min_time_for_votes']));
}

if (!$poll->vote($option)) {
    die('ERROR: '._('Извините, но не удалось зарегистрировать голосование'));
}

Haanga::Load('poll_vote.html', array('poll' => $poll));

die();
