<?php

if (empty($_GET['url'])) {
    die;
}

require_once __DIR__.'/../config.php';

$mnm_image = $globals['base_static']."img/api/mnm-over-01.png";
header('Content-Type: text/html; charset=UTF-8');

echo '<html>'."\n";
echo '<body>'."\n";
echo '<a href="/submit?url='.urlencode($_GET['url']).'" title="'._('menéame').'" target="_parent"><img style="border: 0" src="'.$mnm_image.'" name="menéame"/></a>';
echo '</body></html>';
