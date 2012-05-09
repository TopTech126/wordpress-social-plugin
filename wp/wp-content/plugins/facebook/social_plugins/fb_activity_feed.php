<?php
function fb_get_activity_feed($options = array()) {
	$params = '';

	foreach ($options as $option => $value) {
		$params .= $option . '="' . $value . '" ';
	}

	return '<div class="fb-activity" ' . $params . '></div>';
}

/**
 * Adds the Recent Activity Social Plugin as a WordPress Widget
 */
class Facebook_Activity_Feed extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	public function __construct() {
		parent::__construct(
	 		'fb_activity_feed', // Base ID
			'Facebook Recent Activity', // Name
			array( 'description' => __( "Displays the most interesting recent activity taking place on your site.", 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;

		//$options = array('data-href' => $instance['url']);
		
		echo fb_get_activity_feed($instance);
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		fb_get_activity_feed_fields('widget', $this);
	}
}


function fb_get_activity_feed_fields($placement = 'settings', $object = null) {
	$fields_array = fb_get_activity_feed_fields_array();
	
	fb_construct_fields($placement, $fields_array['children'], null, $object);
}

function fb_get_activity_feed_fields_array() {
	$array['children'] = array(array('name' => 'width',
													'field_type' => 'text',
													'help_text' => 'The width of the plugin, in pixels.',
													),
										array('name' => 'height',
													'field_type' => 'text',
													'help_text' => 'The width of the plugin, in pixels.',
													),
										array('name' => 'colorscheme',
													'field_type' => 'dropdown',
													'options' => array('light', 'dark'),
													'help_text' => 'The color scheme of the plugin.',
													),
										array('name' => 'border_color',
													'field_type' => 'text',
													'help_text' => 'The color scheme of the plugin.',
													),
										array('name' => 'font',
													'field_type' => 'dropdown',
													'options' => array('arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'),
													'help_text' => 'The font of the plugin.',
													),
										array('name' => 'recommendations',
													'field_type' => 'checkbox',
													'help_text' => 'Includes recommendations.',
													),
										);
	
	return $array;
}

?>