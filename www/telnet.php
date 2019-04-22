<?php
// The source code packaged with this file is Free Software, Copyright (C) 2010 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//        http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';
require_once mnminclude.'sneak.php';

$globals['ads'] = false;
$globals['favicon'] = 'img/common/konsole.png';
$globals['description'] = 'Телнет Сетевой теледоступ.  Возможность подключиться через консоль к сайту Sugata. Режим командной строки.';
$globals['extra_css_after'][] = 'telnet.css';

init_sneak();

$globals['site_id'] = SitesMgr::my_id();

do_header('Телнет. Сетевой теледоступ');

Haanga::Load('sneak/telnet_base.html');

$globals['sneak_telnet'] = true;

Haanga::Load('sneak/form.html', compact('max_items'));

do_footer();
