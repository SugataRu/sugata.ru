<?php

defined('mnminclude') or die();

if (empty($link->id)) {
    returnToStep(1);
}

if ($link->check_field_errors()) {
    returnToStep(2, $link->id);
}

if ($_POST) {
    require __DIR__.'/article-3-post.php';
}

do_header(_('Отправить статью') . ' 3/3', _('Отправить статью'));

Haanga::Load('story/submit/article-3.html', array(
    'site_properties' => $site_properties,
    'link' => $link,
    'error' => $error,
    'warning' => $warning
));
