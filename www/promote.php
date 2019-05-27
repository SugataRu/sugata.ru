<?php

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';

$globals['ads'] = false;
$globals['description'] = 'Открытые отчеты о карме постов необходимоых для продвижения историй на центральную страницу проекта Sugata.';

promote_style();
do_header(_('Продвижение и статитиска постов, кармы в Sugata') . ' | ' . _('sugata'));

/*** SIDEBAR ****/
echo '<div id="sidebar">';

do_footer_menu();

echo '</div>';


echo '<div id="newswrap">'."\n";

$site_id = SitesMgr::my_id();

$annotation = new Annotation("promote-$site_id");
$annotation->text = $output;
if ($annotation->read()) {
    echo $annotation->text;
}

echo '</div>'."\n";





do_footer();


function promote_style()
{
    global $globals;
    $globals['extra_head'] = '
<style type="text/css">
#newswrap {
	box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 12px 0px;
	background: rgb(255, 255, 255);
	padding: 10px 22px 0 32px;
	margin-top: 20px;
}
p {
	font-family: Bitstream Vera Sans, Arial, Helvetica, sans-serif;
	font-size: 90%;
}
table {
	margin: 0px;
	padding: 4px;
}
td {
	margin: 0px;
	padding: 4px;
}
.thead {
	font-size: 115%;
	text-transform: uppercase;
	color: #FFFFFF;
	background-color: #FF6600;
	padding: 6px;
}
.tdata0 {
	background-color: #FFF;
}
.tdata1 {
	background-color: #FFF3E8;
}
.tnumber0 {
	text-align: center;
}
.tnumber1 {
	text-align: center;
	background-color: #FFF3E8;
}
</style>
';
}
