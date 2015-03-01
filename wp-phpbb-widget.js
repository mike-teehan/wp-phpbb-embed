		// php file io does the http call... neat! FIXME: the call and rendering should all be moved clientside
// 		$json = file_get_contents($dataurl);
// 		$obj = json_decode($json, true);
// 
// 		$html = "";
// 		if($obj["error"] == true)
// 			$html = "Error: {$obj["msg"]}";
// 
// 		// loop through the json we grabbed and turn it into html
// 		$data = ($obj["data"]) ? $obj["data"] : array();
// 		// 0: title, 1: username, 2: summary, 3: url, 4: time
// 		foreach($data as $row)
// 			$html .= "<a href='{$phpbburl}/viewtopic.php?{$row[3]}' target='_blank'>{$row[0]}</a><br />by {$row[1]}<hr>";

(function($) {
	
	$(document).ready(function() {
		var $bbdiv = $('#phpbbforum');
		var url = $bbdiv.data("url"),
			furl = $bbdiv.data("furl");
		$.get(url, function(json) {
			if(json['error'] === true) {
				console.log("wp-phpbb-widget: recents.json.php error (" + json['msg'] + ")");
				return;
			}
			var data = json['data'];
			// 0: title, 1: username, 2: summary, 3: url, 4: time
			$.each(data, function(key, row) {
				var lurl = furl + "&turl=" + encodeURIComponent(row[3]);
				var $base = $("<div>"),
					$a = $("<a>").attr('href', lurl )
						.append(
							$("<span>").html(row[0] + "<br />- by " + row[1] + " (" + row[4] + ")")
						),
					$hr = $("<hr>");
				$base.append($a, $hr);
				$bbdiv.append($base);
			});
		})
		.fail(function() {
			alert( "error" );
		});
	});
	
})(jQuery);
