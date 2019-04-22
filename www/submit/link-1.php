<?php


defined('mnminclude') or die();

$link->url = empty($_REQUEST['url']) ? '' : clean_input_url($_REQUEST['url']);
$link->randkey = rand(10000, 10000000);
$link->key = md5($link->randkey.$current_user->user_id.$current_user->user_email.$site_key.get_server_name());

if ($_POST) {
    require __DIR__.'/link-1-post.php';
}

do_header(_('Отправить историю') . ' 1/3', _('Enviar historia'));

Haanga::Load('story/submit/link-1.html', array(
    'site_properties' => $site_properties,
    'link' => $link,
    'error' => $error,
    'warning' => $warning
));
