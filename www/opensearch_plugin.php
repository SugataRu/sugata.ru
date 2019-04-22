<?php

require_once __DIR__.'/config.php';
header('Content-Type: text/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">'."\n";
echo '<ShortName>Sugata Search</ShortName>'."\n";
echo '<Description>'._('новости, выбранные пользователями').'</Description>'."\n";
echo '<InputEncoding>UTF-8</InputEncoding>'."\n";
echo '<Image height="16" width="16">http://'.get_static_server_name().$globals['base_url'].'img/favicons/favicon4.ico</Image>'."\n";
echo '<Url type="text/html" method="GET" template="http://'.get_server_name().$globals['base_url'].'search.php">'."\n";
echo '<Param name="q" value="{searchTerms}"/>'."\n";
echo '</Url>'."\n";
echo '<Url type="application/rss+xml" template="http://'.get_server_name().$globals['base_url'].'rss">'."\n";
echo '<Param name="q" value="{searchTerms}"/>'."\n";
echo '</Url>'."\n";
echo '<SearchForm>http://'.get_server_name().$globals['base_url'].'search</SearchForm>'."\n";
echo '</OpenSearchDescription>'."\n";
