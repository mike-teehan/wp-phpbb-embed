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
	margin: 1em auto;
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
	$embedframe.on('load', resizeIframe);

	applyParams();

	function resizeIframe() {
		var newheight = $($embedframe[0].contentWindow.document.body).height(),
			newwidth = $($embedframe[0].contentWindow.document.body).width();
		$embedframe.height(newheight + "px");
		$embedframe.width(newwidth + "px");
	}

	function applyParams() {
		var params = getParams();
		if(params['bg'])
			$('body').css('background-color', '#' + params['bg']);
		if(params['title'])
			document.title = params['title'];
		if(params['fsrc'] && params['turl'])
			$embedframe.attr('src', params['fsrc'] + decodeURIComponent(params['turl']) );
		if(params['hsrc'])
			$('#logoimg').attr('src', params['hsrc']);
		if(params['hurl'])
			$('#urla').attr('href', params['hurl']);
	}

	function getParams() {
		var hash = location.hash || "";
		var params = hash.substr(1).split('&');
		var ret = {};
		$.each(params, function(key, row) {
			var keyval = row.split('=');
			var attr = keyval[0],
				val = decodeURI(keyval[1]);
			switch(attr) {
			case "bg":
			case "title":
			case "fsrc":
			case "hsrc":
			case "hurl":
			case "turl":
				ret[attr] = val;
			break;
			}
		});
		return ret;
	}

})(jQuery);
		</script>
	</body>

</html>

<?php
