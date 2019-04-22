<?php

if (!defined('mnmpath')) {
    require_once __DIR__ . '/../config.php';

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: public, s-maxage=300');
}

if (empty($_GET['id'])) {
    die;
}

$id = (int)$_GET['id'];
$user = new User;

if ($id > 0) {
    $user->id = $id;
} else {
    $user->username = $_GET['id'];
}

if (!$user->read()) {
    die(_('Несуществующий пользователь'));
}

if ($user->avatar) {
    echo '<div style="float: left; margin-right: 10px;"><img class="avatar big" src="' . get_avatar_url($user->id, $user->avatar, 80) . '" width="80" height="80" alt="' . $user->username . '"/></div>';
}

echo '<strong>' . _('Ник') . ':</strong>&nbsp;' . $user->username;

$user->print_medals();

if ($current_user->user_id > 0 && $current_user->user_id != $user->id) {
    echo '&nbsp;' . User::friend_teaser($current_user->user_id, $user->id);
}

if ($user->names) {
    echo '<br /><strong>' . _('Имя') . ':</strong>&nbsp;' . $user->names;
}

if ($user->url) {
    echo '<br /><strong>' . _('Сайт') . ':</strong>&nbsp;' . $user->url;
}

echo '<br /><strong>' . _('Карма') . ':</strong>&nbsp;' . $user->karma;
echo '<br /><strong>' . _('Регистрация') . ':</strong>&nbsp;' . get_date($user->date);

if ($user->bio) {
    echo '<br clear="left"><strong>' . _('О себе') . '</strong>: <br />'.text_to_html($user->bio);
}
