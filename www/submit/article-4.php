<?php

defined('mnminclude') or die();

if (!$current_user->admin || empty($link->id)) {
    die();
}

if ($_POST) {
    require __DIR__.'/article-4-post.php';
}

do_header(_('Расширенное редактирование статьи') . ' 4/4', _('Расширенное редактирование статьи'));

$link->key = md5($link->randkey.$current_user->user_id.$current_user->user_email.$site_key.get_server_name());

Haanga::Load('story/submit/article-4.html', array(
    'link' => $link
));
