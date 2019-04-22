<?php
// The source code packaged with this file is Free Software, Copyright (C) 2010 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//        http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019


$path = $globals['path'];
$globals['submnm'] = preg_replace('/[^\p{L}\d_]/u', ':', $path[1]);

require_once __DIR__.'/config.php';

$forbidden_routes = array('s', 'user', 'legal', 'notame', 'mobile', 'register', 'login', 'trends');

//print_r($forbidden_route);

if (in_array($path[2], $forbidden_routes)) {
    // syslog(LOG_INFO, "Forbidden in subs: ".$path[2]);
    // Redirect to the root
    $uri = preg_split('/\/+/', $_SERVER['REQUEST_URI'], 10, PREG_SPLIT_NO_EMPTY);

    die(header('Location: /'.implode('/', array_slice($uri, 2))));
}

$globals['site_shortname'] = $globals['submnm'];

if (empty($globals['submnm']) || ! ($info = SitesMgr::get_info())) {
    not_found();
}

$globals['path'] = array_slice($path, 2);
$globals['base_url'] .= $path[0] . '/' . $path[1] . '/';

if (empty($routes[$path[2]])) {
    require_once __DIR__.'/story.php';
    return;
}

$file = __DIR__.'/'.$routes[$path[2]];

if (!is_file($file)) {
    not_found($path[1]);
}

$res = include $file;

if ($res === false) {
    not_found($path[1]);
}
