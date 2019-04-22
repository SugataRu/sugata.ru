<?php

require_once __DIR__ . '/../config.php';
require_once mnminclude . 'ban.php';

header('Content-Type: application/json; charset=UTF-8');
array_push($globals['cache-control'], 'no-cache');
http_cache();

if (check_ban_proxy()) {
    error(_('IP не допускается'));
}

$id = intval($_REQUEST['id']);
$user_id = intval($_REQUEST['user']);

$value = round($_REQUEST['value']);

if ($value < -count($globals['negative_votes_values']) || $value > -1) {
    error(_('Плохое голосование') . ' '.$value);
}

$link = Link::from_db($id, null, false);

if (!$link) {
    error(_('несуществующая статья'));
}

if (!$link->is_votable() || $link->total_votes == 0) {
    error(_('закрытые голоса'));
}

if ($current_user->user_id != $user_id) {
    error(_('Неправильный пользователь, перезагрузите страницу, чтобы иметь возможность голосовать'));
}

if (!check_security_key($_REQUEST['key'])) {
    error(_('неверный контрольный ключ'));
}

if (!$link->negatives_allowed(true)) {
    error(_('Вы больше не можете голосовать против'));
}

$votes_freq = $db->get_var("select count(*) from votes where vote_type='links' and vote_user_id=$current_user->user_id and vote_date > subtime(now(), '0:0:30')");

if ($current_user->user_id > 0 && $current_user->admin) {
    $freq = 5;
} else {
    $freq = 2;
}

if ($votes_freq > $freq && $current_user->user_karma > 4) {
    // Typical "negative votes" attack, decrease karma
    $user = new User($current_user->user_id);
    $user->add_karma(-1.0, _('Voto cowboy negativo'));

    error(_('Ваша карма уменьшилась: ') . $user->karma);
}

// Check the user is not a clon by cookie of others that voted the same link
if (($current_user->user_id > 0) && UserAuth::check_clon_votes($current_user->user_id, $link->id, 5, 'links') > 0) {
    error(_('Вы не можете голосовать с клонами'));
}

if (!$link->insert_vote($value)) {
    error(_('уже голосовали ранее с тем же пользователем или IP'));
}

echo $link->json_votes_info(intval($value));

function error($message)
{
    die(json_encode(array('error' => $message)));
}
