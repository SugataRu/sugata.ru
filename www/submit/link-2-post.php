<?php

defined('mnminclude') or die();

try {
    $validator->checkKey();
} catch (Exception $e) {
    return;
}

if (!empty($_POST['url']) && ($_POST['url'] !== $link->url)) {
    $link->url = clean_input_url($_POST['url']);

    if (!validateLinkUrl($link, $validator)) {
        return;
    }
}

require_once mnminclude . 'tags.php';

if ($link->is_new) {
    require __DIR__.'/link-2-post-discard.php';
} else {
    require __DIR__.'/link-2-post-queue.php';
}
