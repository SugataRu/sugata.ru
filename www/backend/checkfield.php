<?php

include __DIR__.'/../config.php';

header('Content-Type: text/plain; charset=UTF-8');

$name = clean_input_string($_GET['name']);
$value = clean_input_string($_GET['value']);

if ($name === 'username') {
    if (!check_username($value)) {
        die(_('Символы недействительны или не начинаются с буквы'));
    }

    if (strlen($value) < 3) {
        die(_('Имя слишком короткое'));
    }

    if (strlen($value) > 24) {
        die(_('Имя слишком длинное'));
    }

    if (!($current_user->user_id > 0 && $current_user->user_login == $value) && user_exists($value, $current_user->user_id)) {
        die(_('Пользователь уже существует'));
    }

    die('OK');
}

if ($name === 'email') {
    if (!check_email($value)) {
        die(_('Неверный адрес e-mail'));
    }

    if (!($current_user->user_id > 0 && $current_user->user_email == $value) && email_exists($value, $current_user->user_id == 0)) {
        // Only check for previuos used if the user is not authenticated
        die(_('Дублированный адрес e-mail, или был недавно использован'));
    }

    die('OK');
}

require_once mnminclude . 'ban.php';

if ($name === 'ban_hostname') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if ($ban = check_ban($value, 'hostname')) {
        die($ban['comment']);
    }

    die('OK');
}

if ($name === 'ban_punished_hostname') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if ($ban = check_ban($value, 'punished_hostname')) {
        die($ban['comment']);
    }

    die('OK');
}

if ($name === 'ban_email') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if (!check_email($value)) {
        die(_('Неверный адрес e-mail'));
    }

    if ($ban = check_ban($value, 'email')) {
        die($ban['comment']);
    }

    die('OK');
}

if ($name === 'ban_ip') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if ($ban = check_ban($value, 'ip')) {
        die($ban['comment']);
    }

    die('OK');
}

if ($name === 'ban_proxy') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if ($ban = check_ban($value, 'proxy')) {
        die($ban['comment']);
    }

    die('OK');
}

if ($name === 'ban_words') {
    if (strlen($value) > 64) {
        die(_('Имя слишком длинное'));
    }

    if (($ban = check_ban($value, 'words'))) {
        die($ban['comment']);
    }

    die('OK');
}

die("KO $name");
