<?php

defined('mnminclude') or die();

if (empty($link->id)) {
    returnToStep(1);
}

if ($link->check_field_errors()) {
    returnToStep(2, $link->id);
}

$link->key = md5($link->randkey.$current_user->user_id.$current_user->user_email.$site_key.get_server_name());

if ($_POST) {
    require __DIR__.'/link-3-post.php';
}

do_header(_('Отправить историю') . ' 3/3', _('Enviar historia'));

Haanga::Load('story/submit/link-3.html', array(
    'site_properties' => $site_properties,
    'link' => $link,
    'error' => $error,
    'warning' => $warning,
    'related' => ($link->url ? $link->get_related(6) : [])
));
