<?php

if (!defined('mnmpath')) {
    require_once __DIR__ . '/../config.php';
    header('Content-Type: text/json; charset=utf-8');
}

global $globals, $db;

if ($globals['memcache_host']) {
    $memcache_list_subs_json = 'list_subs_json';
}

if (!$memcache_list_subs_json || !$subs = unserialize(memcache_mget($memcache_list_subs_json))) {

    // Not in memcache

    $sql = 'SELECT s.name, s.name_long FROM (subs AS s) LEFT JOIN users AS u ON (u.user_id = s.owner AND s.show_admin = 1) WHERE s.sub = 1 AND s.enabled = 1 ORDER BY s.name ASC';

    $results = $db->get_results($sql);

    if ($memcache_list_subs_json) {
        memcache_madd($memcache_list_subs_json, serialize($results), 1800);
    }

    echo json_encode($results);
} else {
    echo json_encode($subs);
}
