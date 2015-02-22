<?php
/*
Plugin Name: WP phpBB Embed Widget
Plugin URI: http://miketeehan.com/plugins/wp-phpbb-embed
Description: Shows the most recent posts from a phpBB forum
Author: Mike Teehan <mike.teehan@gmail.com>
Version: 0.1
Author URI: http://miketeehan.com
*/

class phpBBEmbedWidget extends WP_Widget
{
	private $url, $wpurl, $dataurl;

	function phpBBEmbedWidget() {
		// boilerplate
		$widget_ops = array('classname' => 'phpBBEmbedWidget', 'description' => 'Embeds the most recent phpBB forum posts' );
		$this->WP_Widget('phpBBEmbedWidget', 'phpBB Embed Recent Posts', $widget_ops);
	}

	// outputs the html of the widget
	function widget($args, $instance) {
		// boilerplate BEGIN
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

		if (!empty($title))
			echo $before_title . $title . $after_title;
		// boilerplate END

		$w = new phpBBEmbedWidget();
		$s = $w->get_settings();
		$o = null;
		// FIXME: we find the first instance that has settings and use that. for all pages.
		foreach($s as $setting) {
			if(empty($setting) )
				continue;
			$o = $setting;
			break;
		}
		$wpurl = $o['wpurl'];
		$phpbburl = $o['phpbburl'];
		$recentsurl = $o['recenturl'];
		// https or not, break the transport off and use whatever is being used and rebuild the url
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
		$u = parse_url($recentsurl);
		$dataurl = "{$protocol}://{$u['host']}{$u['path']}";
		// php file io does the http call... neat! FIXME: the call and rendering should all be moved clientside
		$json = file_get_contents($dataurl);
		$obj = json_decode($json, true);

		$html = "";
		if($obj["error"] == true)
			$html = "Error: {$obj["msg"]}";

		// loop through the json we grabbed and turn it into html
		$data = ($obj["data"]) ? $obj["data"] : array();
		// 0: title, 1: username, 2: summary, 3: url, 4: time
		foreach($data as $row)
			$html .= "<a href='{$phpbburl}{$row[3]}' target='_blank'>{$row[0]}</a><br />by {$row[1]}<hr>";

		echo "<div id='phpbbforum'><b>Recent Posts</b>:<hr>{$html}</div>";

		// boilerplate
		echo $after_widget;
	}

	// widget settings
	// wpurl - the base url of wordpress eg: //wp.domain.com
	// phpbburl - base url of phpbb forums eg: //wp.domain.com/phpbb
	// recenturl - url of the json source for recent posts usually: //wp.domain.com/phpbb/recents.json.php
	public function form($instance) {
		error_log("in: " . json_encode($instance));
		if(isset($instance['wpurl']) )
			$option1 = $instance['wpurl'];
		else
			$option1 = __('new wpurl', 'wpb_widget_domain');

		if (isset($instance['phpbburl']) )
			$option2 = $instance['phpbburl'];
		else
			$option2 = __('new phpbburl', 'wpb_widget_domain');

		if(isset($instance['recenturl']) )
			$option3 = $instance['recenturl'];
		else
			$option3 = __('new recenturl', 'wpb_widget_domain');
?>
<p>
<label for="<?php echo $this->get_field_id('wpurl'); ?>"><?php _e('WP URL:'); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id('wpurl' ); ?>" name="<?php echo $this->get_field_name('wpurl'); ?>" type="text" value="<?php echo esc_attr($option1); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'phpbburl' ); ?>"><?php _e('phpBB URL:'); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id('phpbburl'); ?>" name="<?php echo $this->get_field_name('phpbburl'); ?>" type="text" value="<?php echo esc_attr($option2); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'recenturl' ); ?>"><?php _e( 'Recents URL:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id('recenturl'); ?>" name="<?php echo $this->get_field_name('recenturl'); ?>" type="text" value="<?php echo esc_attr($option3); ?>" />
</p>
<?php
	}

}

// ugh
add_action('widgets_init', create_function('', 'return register_widget("phpBBEmbedWidget");') );