<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

require_once __DIR__ . '/../config.php';

header('Content-Type: text/plain; charset=UTF-8');

if (!$current_user->user_id) {
    die('ERROR: '._('неправильный пользователь'));
}

if (!($to = (int)$_REQUEST['id'])) {
    die('ERROR: '._('код пользователя отсутствует'));
}

if (!check_security_key($_REQUEST['key'])) {
    die('ERROR: '._(' неверный контрольный ключ'));
}

switch ($_REQUEST['value']) {
    case '0':
        User::friend_delete($current_user->user_id, $to);
        break;

    case '1':
        User::friend_insert($current_user->user_id, $to, 1);
        break;

    case '-1':
        User::friend_insert($current_user->user_id, $to, -1);
        break;

    default:
        die('ERROR: '._('opción incorrecta'));
}

die(User::friend_teaser($current_user->user_id, $to));
