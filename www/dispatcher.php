<?php
// The source code packaged with this file is Free Software, Copyright (C) 2010 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//        http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019



$routes = array(
    ''                       => 'index.php',
    'story'                  => 'story.php',
    'my-story'               => 'my-story.php',
    'submit'                 => 'submit/index.php',
    'subedit'                => 'subedit.php',
    'subs'                   => 'subs.php',
    'comment_ajax'           => 'backend/comment_ajax.php',
    'login'                  => 'login.php',
    'register'               => 'register.php',
    'cloud'                  => 'cloud.php',
    'sites_cloud'            => 'sitescloud.php',
    'rsss'                   => 'rsss.php',
    'promote'                => 'promote.php',
    'queue'                  => 'shakeit.php',
    'articles'               => 'articles.php',
    'go'                     => 'go.php',
    'b'                      => 'bar.php',
    'c'                      => 'comment.php',
    's'                      => 'submnm.php',
    'user'                   => 'user/index.php',
    'profile'                => 'user/edit.php',
    'search'                 => 'search.php',
    'rss'                    => 'rss2.php',
    'comments_rss'           => 'comments_rss2.php',
    'sneakme_rss'            => 'sneakme_rss2.php',
    'sneak'                  => 'sneak.php',
    'telnet'                 => 'telnet.php',
    'popular'                => 'topstories.php',
    'top_visited'            => 'topclicked.php',
    'top_active'             => 'topactive.php',
    'top_comments'           => 'topcomments.php',
    'top_users'              => 'topusers.php',
    'top_commented'          => 'topcommented.php',
    'sitemap'                => 'sitemap.php',
    'opensearch'             => 'opensearch_plugin.php',
    'backend'                => 'backend/dispatcher.php',
    'api'                    => 'api/dispatcher.php',
    'notame'                 => 'sneakme/dispatcher.php',
    'captcha'                => 'info/captcha.php',
 //   'news-sugata'   => 'changelog.php',
	'legal'   => 'info/legal.php',
	'between'                => 'info/between.php',
	'trends'                 => 'info/trends.php',
	'faq-ru'                 => 'info/info/faq-ru.php',
	'space'                 => 'info/space.php',
	'karma'                 => 'info/karma.php',
	'rules'                 => 'info/rules.php',
	'help'                 => 'info/help.php'
);

$globals['path'] = $path = preg_split('/\/+/', $_SERVER['PATH_INFO'], 10, PREG_SPLIT_NO_EMPTY) ?: array('');

if (!isset($path[0]) || !isset($routes[$path[0]]) || !is_file(__DIR__.'/'.$routes[$path[0]])) {
    require_once __DIR__.'/config.php';
    do_error('Страница ошибки', 404, true);
}

$globals['script'] = $script = $routes[$path[0]];

if ((include __DIR__.'/'.$script) === false) {
    require_once __DIR__.'/config.php';
    do_error('bad request '.$script, 400, true);
}
