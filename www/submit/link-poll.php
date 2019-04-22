<?php

defined('mnminclude') or die();

$link->poll = new Poll;

$link->poll->read('link_id', $link->id);
$link->poll->link_id = $link->id;

$db->transaction();

try {
    $link->poll->storeFromArray($_POST);
} catch (Exception $e) {
    $db->rollback();
    throw $e;
}

$db->commit();
