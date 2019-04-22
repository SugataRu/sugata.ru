<?php

defined('mnminclude') or die();

$link->randkey = rand(10000, 10000000);
$link->key = md5($link->randkey.$current_user->user_id.$current_user->user_email.$site_key.get_server_name());

if ($_POST || !empty($_GET['write'])) {
    require __DIR__.'/article-1-post.php';
}

do_header(_('Отправить статью') . ' 1/3', _('Отправить статью'));

Haanga::Load('story/submit/link-1.html', array(
    'site_properties' => $site_properties,
    'link' => $link,
    'error' => $error,
    'warning' => $warning
));
