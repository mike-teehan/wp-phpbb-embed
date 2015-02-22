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
		$title = $o['title'];
		$wpurl = $o['wpurl'];
		$phpbburl = $o['phpbburl'];
		$recentsurl = $o['recenturl'];
		// https or not, break the transport off and use whatever is being used and rebuild the url
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
		$u = parse_url($recentsurl);
		$dataurl = "{$protocol}://{$u['host']}{$u['path']}";

		echo "<div id='phpbbforum' data-url=\"{$dataurl}\" data-phpbburl=\"{$phpbburl}\"><b>{$title}</b><br /><hr></div>";

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
			$option1 = __('//site.domain/wordpress', 'wpb_widget_domain');

		if (isset($instance['phpbburl']) )
			$option2 = $instance['phpbburl'];
		else
			$option2 = __('//site.domain/phpbb', 'wpb_widget_domain');

		if(isset($instance['recenturl']) )
			$option3 = $instance['recenturl'];
		else
			$option3 = __('//site.domain/recents.json.php', 'wpb_widget_domain');

		if(isset($instance['title']) )
			$option4 = $instance['title'];
		else
			$option4 = __('Title', 'wpb_widget_domain');
?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($option4); ?>" />
</p>
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

add_action('widgets_init', create_function('', 'return register_widget("phpBBEmbedWidget");') );

function enqueue_wp_phpbb_widget() {
	wp_enqueue_script(
		'wp-phpbb-widget',
		plugins_url() . '/wp-phpbb-embed/wp-phpbb-widget.js',
		array('jquery')
	);
}

add_action('wp_enqueue_scripts', 'enqueue_wp_phpbb_widget');

