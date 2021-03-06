<?php
// The Meneame source code is Free Software, Copyright (C) 2005-2009 by
// Ricardo Galli <gallir at gmail dot com> and Menéame Comunicacions S.L.
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.

// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//	  http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019

require_once __DIR__.'/../config.php';
require_once mnminclude.'html1.php';
require_once mnminclude.'geo.php';

geo_init('onLoad', false, 3);

do_header(_('геообращение'));
do_tabs('main', _('геообращение'), true);

/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_banner_right();
do_vertical_tags();
echo '</div>' . "\n";
/*** END SIDEBAR ***/

echo '<div id="newswrap">'."\n";

echo '<div class="topheading"><h2>активность геолокированных пользователей</h2></div>';

echo '<div id="map" style="width: 95%; height: 500px;margin:0 0 0 20px"></div></div>'
?>

<script>
//<![CDATA[
var timestamp = 0;
var period = 10000;
var persistency = 300000;
var counter=0;

function add_marker(item, delay) {
	var myicon;
	var point = new GLatLng(item.lat, item.lng);
	var marker;
	switch (item.type) {
		case 'link':
			if (item.evt == 'geo_edit') marker = geo_get_marker(point, 'geo');
			else  marker = geo_get_marker(point, item.status);
			break;
		default:
			marker = geo_get_marker(point, item.type);
	}
	marker.myId = item.id;
	marker.myType = item.type;
	setTimeout(function () {
				geo_map.addOverlay(marker);
				GDownloadUrl(base_url+"geo/"+marker.myType+".php?id="+marker.myId, function(data, responseCode) {
					marker.openInfoWindowHtml(data);
				});
			}, delay);
	setTimeout(function () {geo_map.removeOverlay(marker)}, persistency);
}

function get_json() {
	$.getJSON('geo/sneaker.php', {"time": timestamp}, function (json) {
			var items = json.items.length;
			timestamp = json.ts;
			var delay_time;
			var item;
			for (i=items-1; i>=0; i--) {
				if (typeof (json.items[i]) != "undefined") { // IE return a undefined, sometimes :-O
					delay_time = parseInt(period - (period/items) * (i+1));
					add_marker(json.items[i], delay_time);
				}
			}
		});
	counter++;
	if (counter > 700) {
		if ( !confirm('<?php echo _('¿desea continuar conectado?');?>') ) {
			return;
		}
		counter = 0;
	}
	setTimeout(get_json, period);
}

function onLoad(foo_lat, foo_lng, foo_zoom, foo_icontype) {
	if (geo_basic_load(18, 15, 3)) {
		geo_map.addControl(new GLargeMapControl());
		geo_map.addControl(new GMapTypeControl());
		GEvent.addListener(geo_map, 'click', function (overlay, point) {
			if (overlay && overlay.myId > 0) {
				GDownloadUrl(base_url+"geo/"+overlay.myType+".php?id="+overlay.myId, function(data, responseCode) {
					overlay.openInfoWindowHtml(data);
				});
			}
		});
		get_json();
	}
}
//]]>
</script>
<?php

do_footer_menu();
do_footer();
