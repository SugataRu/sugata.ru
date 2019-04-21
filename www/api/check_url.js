var url = 'https://sugata.ru/api/check_url.php?url='+encodeURIComponent(document.URL);

function write_iframe() {
	var span = document.getElementById("meneame");
	span.innerHTML='<iframe width="98" height="17" scrolling="no" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" src="'+url+'"></iframe>';
}

document.write('<span id="meneame" style="width: 98px; height: 17px; border: none; padding: 0; margin: 0; background: transparent ; "><script type="text/javascript">setTimeout("write_iframe()", 200)</script></span>');
