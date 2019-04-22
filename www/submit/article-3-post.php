<?php

defined('mnminclude') or die();

try {
    $validator->checkKey();
    $validator->checkSiteSend();
} catch (Exception $e) {
    return;
}

// Check this one was not already queued
if ($link->votes == 0 && ($link->status !== 'queued')) {
    $link->enqueue();
}

die(header('Location: '. $link->get_permalink()));
