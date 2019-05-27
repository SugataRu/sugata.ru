<?php
// The source code packaged with this file is Free Software, Copyright (C) 2019 by
// Evg Sugata.ru

require_once __DIR__.'/../config.php';
require_once mnminclude.'color.php';

header('Content-Type: application/json; charset=UTF-8');

if (empty($_REQUEST['type'])) {
    error(_('тип отсутствует'));
}
$type = $_REQUEST['type'];

if (!($user = intval($_REQUEST['user']))) {
    error(_('код пользователя отсутствует'));
}

if ($user != $current_user->user_id) {
    error(_('неправильный пользователь'));
}

if (! check_security_key($_REQUEST['key'])) {
    error(_('неверный контрольный ключ'));
}

$dict['value'] = color_add_delete($user, $type);
echo json_encode($dict);

function error($mess)
{
    $dict['error'] = $mess;
    echo json_encode($dict);
    die;
}
