<?php

defined('mnminclude') or die();

try {
    $validator->checkKey();
} catch (Exception $e) {
    return;
}

// Store previous value for the log
Backup::store('links', $link->id, $link->duplicate());

if ($_POST['title'] = trim($_POST['title'])) {
    $link->title = $_POST['title'];
}

if ($_POST['uri'] = trim($_POST['uri'])) {
    $link->uri = $_POST['uri'];
}

$link->nsfw = !empty($_POST['nsfw']);

$link->store_advanced();

die(header('Location: '. $link->get_permalink()));
