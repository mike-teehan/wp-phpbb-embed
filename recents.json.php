<?php
// recent-widget.php
// Returns json encoded recent post data

date_default_timezone_set("America/New_York");

// include phpbb config for db settings
include 'config.php';

// all output must be makeJSON()
header('Content-Type: application/json');

if(!mysql_connect($dbhost, $dbuser, $dbpasswd) )
	die(makeJSON(null, true, "DB connection error") );

if(!mysql_select_db($dbname))
	die(makeJSON(null, true, "DB selection error") );

mysql_query("SET NAMES 'utf8'");

$result = mysql_query("
SELECT phpbb_posts.post_time AS post_time, phpbb_posts.post_text AS post_text, phpbb_posts.topic_id AS tid,
	phpbb_posts.forum_id AS fid, phpbb_topics.topic_title AS topic_title, phpbb_users.username AS username,
	phpbb_posts.post_username AS anon
FROM phpbb_posts, phpbb_topics, phpbb_users
WHERE post_id IN (SELECT * FROM (SELECT max(post_id) AS mpt FROM phpbb_posts GROUP BY topic_id ORDER BY mpt DESC LIMIT 5) alias)
	AND post_reported=0
	AND phpbb_posts.topic_id=phpbb_topics.topic_id
	AND phpbb_posts.poster_id=phpbb_users.user_id
ORDER BY post_time DESC
");

if(!$result)
    die(makeJSON(null, true, "DB query error - ({$result})") );

// 0: title, 1: username, 2: summary, 3: url, 4: time
$ret = array();
while($row = mysql_fetch_assoc($result) ) {
	$data = array();
	$data[0] = $row['topic_title'];
	$data[1] = ($row['username'] == "Anonymous") ? $row['anon'] : $row['username'];
	$data[2] = summary($row['post_text'], 200, true);
	$data[3] = "f={$row['fid']}&t={$row['tid']}";
	$data[4] = strftime('%A, %d. %b. %Y', $row['post_time']);
	$ret[] = $data;
}

die(makeJSON($ret) );

// trim+
function summary($str, $limit = 200, $strip = false) {
	// strip html and bbcode... maybe
	if($strip) {
		$str = html_entity_decode($str);
		$str = strip_tags($str);
		$str = preg_replace('/\[[^\]]*\]/', '', $str);
	}

	// Remove extra junk
	$str = str_replace("<!--", "", $str);
	$str = str_replace("-->", "", $str);

	// > $limit? truncate and append '...'
	if(strlen($str) > $limit) {
		$str = substr($str, 0, $limit - 3);
		$str = substr($str, 0, strrpos($str, ' ') ); // find the last space
		$str .= "...";
	}

	return trim($str);
}

function makeJSON($data = null, $error = false, $msg = null) {
	$ret = array(
		"error"	=> $error,
		"msg"	=> $msg,
		"data"	=> $data
	);

	return json_encode($ret);
}
