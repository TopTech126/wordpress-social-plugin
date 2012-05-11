<?php
function fb_get_subscribe_button($options = array()) {
	$params = '';

	foreach ($options as $option => $value) {
		$params .= $option . '="' . $value . '" ';
	}

	$params .= 'data-ref="wp" ';

	return '<div class="fb-subscribe fb-social-plugin" ' . $params . '></div>';
}

function fb_subscribe_button_automatic($content) {
	$options = get_option('fb_options');

	foreach($options['subscribe'] as $param => $val) {
		$param = str_replace('_', '-', $param);

		$options['subscribe']['data-' . $param] =  $val;
	}

	$fb_data = get_user_meta(get_the_author_meta('ID'), 'fb_data', true);

	$options['subscribe']['data-href'] = 'http://www.facebook.com/' . $fb_data['username'];

	$content .= fb_get_subscribe_button($options['subscribe']);

	return $content;
}


/**
 * Adds the Subscribe Button Social Plugin as a WordPress Widget
 */
class Facebook_Subscribe_Button extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	public function __construct() {
		parent::__construct(
	 		'fb_subscribe', // Base ID
			__( 'Facebook Subscribe Button', 'facebook' ), // Name
			array( 'description' => __( 'Lets a user subscribe to your public updates on Facebook.', 'facebook' ) ) // Args
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

		if ( ! empty( $instance['title'] ) )
			echo $before_title . $instance['title'] . $after_title;

		echo fb_get_subscribe_button($instance);
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
		fb_get_subscribe_fields('widget', $this);
	}
}


function fb_get_subscribe_fields($placement = 'settings', $object = null) {
	$fields_array = fb_get_subscribe_fields_array($placement);

	fb_construct_fields($placement, $fields_array['children'], $fields_array['parent'], $object);
}

function fb_get_subscribe_fields_array($placement) {
	$array['parent'] = array('name' => 'subscribe',
									'field_type' => 'checkbox',
									'help_text' => __( 'Click to learn more.', 'facebook' ),
									'help_link' => 'https://developers.facebook.com/docs/reference/plugins/subscribe/',
									);

	$array['children'] = array(array('name' => 'layout',
													'field_type' => 'dropdown',
													'default' => 'standard',
													'options' => array('standard', 'button_count', 'box_count'),
													'help_text' => __( 'Determines the size and amount of social context at the bottom.', 'facebook' ),
													),
										array('name' => 'width',
													'field_type' => 'text',
													'default' => '450',
													'help_text' => __( 'The width of the plugin, in pixels.', 'facebook' ),
													),
										array('name' => 'show_faces',
													'field_type' => 'checkbox',
													'default' => true,
													'help_text' => __( 'Show profile pictures below the button.  Applicable to standard layout only.', 'facebook' ),
													),
										array('name' => 'colorscheme',
													'field_type' => 'dropdown',
													'default' => 'light',
													'options' => array('light', 'dark'),
													'help_text' => __( 'The color scheme of the plugin.', 'facebook' ),
													),
										array('name' => 'font',
													'field_type' => 'dropdown',
													'default' => 'arial',
													'options' => array('arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'),
													'help_text' => __( 'The font of the plugin.', 'facebook' ),
													),
										);

	if ($placement == 'settings') {
		$array['children'][] = array('name' => 'position',
													'field_type' => 'dropdown',
													'options' => array('top', 'bottom', 'both'),
													'help_text' => __( 'Where the button will display on the page or post.', 'facebook' ),
													);
	}

	if ($placement == 'widget') {
		$array['children'][] = array('name' => 'href',
													'field_type' => 'text',
													'help_text' => __( 'The URL the Like button will point to.', 'facebook' ),
													);

		$array['children'][] = array('name' => 'title',
													'field_type' => 'text',
													'help_text' => __( 'The title above the button.', 'facebook' ),
													);
	}

	return $array;
}

?>