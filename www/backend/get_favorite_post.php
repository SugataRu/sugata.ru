<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

require_once __DIR__.'/../config.php';
require_once mnminclude.'favorites.php';

header('Content-Type: text/plain; charset=UTF-8');

if (!($link=intval($_REQUEST['id']))) {
    error(_('пропустить ID из записи'). " $link");
}

if (!($user = intval($_REQUEST['type']))) {
    error(_('код пользователя отсутствует'));
}

if ($user != $current_user->user_id) {
    error(_('неправильный пользователь'));
}

if (! check_security_key($_REQUEST['key'])) {
    error(_(' неверный контрольный ключ'));
}


echo favorite_add_delete($user, $link, 'post');

function error($mess)
{
    echo "ERROR: $mess\n";
    die;
}
