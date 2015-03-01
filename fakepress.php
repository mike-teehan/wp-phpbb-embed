<!DOCTYPE html>
<html>

	<head>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<style>
body {
	margin: 0px;
	text-align: center;
}

#headerdiv, #contentdiv {
	min-width: 100%;
	margin: 0 auto;
}

#embedframe {
	width: 980px;
}
		</style>
	</head>

	<body>
		<div id="headerdiv">
			<a id="urla"><img id="logoimg"></a>
		</div>
		<div id="contentdiv">
			<iframe id="embedframe" scrolling="no"></iframe>
		</div>
		<script>
(function($) {

	var $embedframe = $('#embedframe');
	$embedframe.on('load', function() {
		var newheight = $embedframe[0].contentWindow.document.body.scrollHeight,
			newwidth = $embedframe[0].contentWindow.document.body.scrollWidth;
		$embedframe.height(newheight + "px");
		$embedframe.width(newwidth + "px");
	});

	var hash = location.hash || "";
	console.log("hash: " + hash);
	var params = hash.substr(1).split('&');
	$.each(params, function(key, row) {
		var keyval = row.split('=');
		var val = decodeURI(keyval[1]);
		switch(keyval[0]) {
		case "bg":
			$('body').css('background-color', '#' + val);
		break;
		case "title":
			document.title = val;
		break;
		case "fsrc":
			$embedframe.attr('src', val);
		break;
		case "hsrc":
			$('#logoimg').attr('src', val);
		break;
		case "hurl":
			$('#urla').attr('href', val);
		break;
		}
	});

})(jQuery);
		</script>
	</body>

</html>

<?php
