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
	private $plugin_location;

	function phpBBEmbedWidget() {
		// boilerplate
		$widget_ops = array('classname' => 'phpBBEmbedWidget', 'description' => 'Embeds the most recent phpBB forum posts' );
		$this->WP_Widget('phpBBEmbedWidget', 'phpBB Embed Recent Posts', $widget_ops);
		$this->plugin_location = plugin_dir_url(__FILE__);
	}

	// outputs the html of the widget
	function widget($args, $instance) {

		// instance IDs are like widget-23 so chop the number off the end
		$wid = $this->id;
		$wid = substr($wid, strrpos($wid, "-") + 1);

		// register the javascripts for loading
		wp_register_script('wp-phpbb-widget' . $wid, $this->plugin_location . "wp-phpbb-widget.js", array('jquery') );
		wp_enqueue_script('wp-phpbb-widget' . $wid);

		// boilerplate BEGIN
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

		if (!empty($title))
			echo $before_title . $title . $after_title;
		// boilerplate END

		$s = $this->get_settings();
		$o = $s[$wid];
		$title = $o['title'];
		$wpurl = $o['wpurl'];
		$phpbburl = $o['phpbburl'];
		$recentsurl = $o['recenturl'];
		// https or not, break the transport off and use whatever is being used and rebuild the url
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
		$u = parse_url($recentsurl);
		$dataurl = "{$protocol}://{$u['host']}{$u['path']}";

		echo "<div id='phpbbforum' data-url=\"{$dataurl}\" data-phpbburl=\"{$phpbburl}\"><hr></div>";

		// boilerplate
		echo $after_widget;
	}

	// widget settings
	// wpurl - the base url of wordpress eg: //wp.domain.com
	// phpbburl - base url of phpbb forums eg: //wp.domain.com/phpbb
	// recenturl - url of the json source for recent posts usually: //wp.domain.com/phpbb/recents.json.php
	public function form($instance) {
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

		if(isset($instance['fbgcolor']) )
			$option5 = $instance['fbgcolor'];
		else
			$option5 = __('#000000', 'wpb_widget_domain');

		if(isset($instance['ftitle']) )
			$option6 = $instance['ftitle'];
		else
			$option6 = __('Page Title', 'wpb_widget_domain');

		if(isset($instance['hsrc']) )
			$option7 = $instance['hsrc'];
		else
			$option7 = __('Header Image URL', 'wpb_widget_domain');

		if(isset($instance['hurl']) )
			$option8 = $instance['hurl'];
		else
			$option8 = __('Header Link URL', 'wpb_widget_domain');

// 		case "hurl":
// 			$('#urla').attr('href', val);
// 		break;

?>
<h3>Widget:</h3>
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
<hr />
<h3>FakePress:</h3>
<p>
<label for="<?php echo $this->get_field_id('fbgcolor'); ?>"><?php _e('Background color:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('fbgcolor' ); ?>" name="<?php echo $this->get_field_name('fbgcolor'); ?>" type="text" value="<?php echo esc_attr($option5); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('ftitle'); ?>"><?php _e('Page Title:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('ftitle' ); ?>" name="<?php echo $this->get_field_name('ftitle'); ?>" type="text" value="<?php echo esc_attr($option6); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('hsrc'); ?>"><?php _e('Header Image URL:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('hsrc' ); ?>" name="<?php echo $this->get_field_name('hsrc'); ?>" type="text" value="<?php echo esc_attr($option7); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('hurl'); ?>"><?php _e('Header Link URL:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('hurl' ); ?>" name="<?php echo $this->get_field_name('hurl'); ?>" type="text" value="<?php echo esc_attr($option8); ?>" />
</p>

<?php
	}

}

add_action('widgets_init', create_function('', 'return register_widget("phpBBEmbedWidget");') );
