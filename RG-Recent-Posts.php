<?php
/*
Plugin Name: RG Recent Posts
Plugin URI: http://robgloudemans.nl
Description: Display recent posts in a widget. Where the default widget only let's you display recent posts of the 'post' posttype, with RG Recent Posts you can specify from which post type you want to display the recent posts.
Version: 1.1.1
Author: Rob Gloudemans
Author URI: http://robgloudemans.nl
License: GPLv2
Text Domain: rg-recentposts
Domain Path: /lang/
*/

$plugin_header_translation = array(
	__('Display recent posts in a widget. Where the default widget only let\'s you display recent posts of the \'post\' posttype, with RG Recent Posts you can specify from which post type you want to display the recent posts.', 'rg-recentposts')
);

class RG_Recent_Posts extends WP_Widget{

	public function __construct()
	{
		$this->plugin_textdomain();

		$params = array(
			'name' => __('RG Recent Posts', 'rg-recentposts'),
			'description' => __('Display recent posts in a widget. Specifiy which post type should be listed.', 'rg-recentposts')
		);

		parent::__construct('RG_Recent_Posts', '', $params);
	}

	public function plugin_textdomain()
	{
		load_plugin_textdomain('rg-recentposts', false, dirname(plugin_basename(__FILE__)) . '/lang/');
	}

	public function form($instance)
	{
		extract($instance);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title', 'rg-recentposts');?>:</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo $this->get_field_id('title');?>"
				name="<?php echo $this->get_field_name('title');?>"
				value="<?php echo (isset($title)) ? esc_attr($title) : '';?>"
			>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('posttype');?>"><?php _e('Posttype', 'rg-recentposts');?>:</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo $this->get_field_id('posttype');?>"
				name="<?php echo $this->get_field_name('posttype');?>"
				value="<?php echo (isset($posttype)) ? esc_attr($posttype) : '';?>"
			>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('num_posts');?>"><?php _e('Number of posts to show', 'rg-recentposts');?>:</label>
			<input
				type="text"
				size="3"
				value="5"
				id="<?php echo $this->get_field_id('num_posts');?>"
				name="<?php echo $this->get_field_name('num_posts');?>"
				value="<?php echo (isset($num_posts)) ? esc_attr($num_posts) : '';?>"
			>
		</p>

		<?php
	}

	public function widget($args, $instance)
	{
		extract($args);
		extract($instance);

		echo $before_widget;
		echo $before_title . $title . $after_title;

		echo '<ul>';

		$posttype = ( ! empty($posttype)) ? $posttype : 'post';

		$recentposts = new WP_Query(array(
			'post_type' => esc_attr($posttype),
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => esc_attr($num_posts),
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1
		));

		if($recentposts->have_posts()) : while($recentposts->have_posts()) : $recentposts->the_post();?>

			<li><a href="<?php the_permalink();?>"><?php the_title();?></a></li>

		<?php endwhile; endif;

		echo '</ul>';

		echo $after_widget;

	}

}

add_action('widgets_init', function(){
	register_widget('RG_Recent_Posts');
});