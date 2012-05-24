<?php
// create custom plugin settings menu
add_action( 'admin_init', 'fb_admin_menu_settings' );
add_action( 'admin_menu', 'fb_create_menu' );
add_action('admin_menu', 'fb_add_settings_pages', 10);

/**
 * Create the Settings menu in the admin control panel
 *
 * @since 1.0
 */
function fb_create_menu() {
	//create new top-level menu
	$page = add_menu_page( sprintf( __( '%s Plugin Settings', 'facebook' ), 'Facebook'), 'Facebook', 'manage_options', __FILE__, 'fb_settings_page', plugins_url( 'images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_print_styles-' . $page, 'fb_admin_style');
	add_action( 'admin_print_scripts-' . $page, 'fb_admin_scripts' );
}

/**
 * Add admin styles to the head
 *
 * @since 1.0
 */
function fb_admin_style() {
	wp_enqueue_style( 'fb_admin', plugins_url( 'style/style-admin.css', __FILE__), array(), '1.0' );
}

/**
 * Add admin scripts to the head
 *
 * @since 1.0
 */
function fb_admin_scripts( $hook_suffix ) {
	wp_enqueue_script( 'fb_admin', plugins_url( 'scripts/fb-admin.js', __FILE__ ), array(), '1.0', true );
}

/**
 * Queue scripts and styles up
 *
 * @since 1.0
 */
function fb_admin_menu_settings() {
	register_setting( 'fb_options', 'fb_options', 'fb_options_validate' );

	//call register settings function
	add_action( 'admin_print_styles', 'fb_admin_style' );
	add_action( 'admin_print_scripts', 'fb_admin_scripts' );
}

/**
 * The settings page
 *
 * @since 1.0
 */
function fb_settings_page() {
	?>
	<div class="wrap">
		<div class="facebook-logo"></div>
		<h2><?php echo esc_html__( 'Facebook for WordPress', 'facebook' ); ?></h2>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'fb_options' );

			echo '<h2>' . esc_html__( 'Main Settings', 'facebook' ) . '</h2>';
			echo 'Get your App ID, Secret, and Namespace at <a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a>. <strong>If you already have a Facebook app for this website, it\'s important that you use the same information below</strong>.';
			fb_get_main_settings_fields();

			echo '<h2>' . esc_html__( 'Post and Page Settings', 'facebook' ) . '</h2>';

			echo 'These settings affect Pages and Posts only.  Additional Social Plugins are also available in the Widgets settings.';

			fb_get_social_publisher_fields();
			fb_get_recommendations_bar_fields();
			fb_get_like_fields();
			fb_get_subscribe_fields();
			fb_get_send_fields();
			fb_get_comments_fields();

			submit_button();
			?>
		</form>
	</div>
	<?php
}

function fb_get_main_settings_fields() {
	$children = array(array('name' => 'app_id',
													'type' => 'text',
													'help_text' => __( 'Your App ID.', 'facebook' ),
													),
										array('name' => 'app_secret',
													'type' => 'text',
													'help_text' => __( 'Your App Secret.', 'facebook' ),
													),
										array('name' => 'app_namespace',
													'type' => 'text',
													'help_text' => __( 'Your App Namespace.', 'facebook' ),
													),
										);

	fb_construct_fields('settings', $children);
}

/**
 * Add Facebook options to other sub-menus
 *
 * @since 1.0
 */
function fb_add_settings_pages() {
     add_submenu_page(
         'edit-comments.php',
         'Facebook',
         'Facebook',
         'moderate_comments',
         'fb_comments',
         'fb_settings_page'
     );
     add_submenu_page(
         'facebook/fb-admin-menu.php',
         'Insights',
         'Insights',
         'publish_posts',
         'fb_insights',
         'fb_insights_page'
     );
}

/**
 * Validate all of the settings
 *
 * @since 1.0
 */
function fb_options_validate($input) {
	/*
	if (!defined('FB_APP_SECRET')) {
		// secrets are 32 bytes long and made of hex values
		$input['app_secret'] = trim($input['app_secret']);
		if(! preg_match('/^[a-f0-9]{32}$/i', $input['app_secret'])) {
		  $input['app_secret'] = '';
		}
	}

	if (!defined('FB_APP_ID')) {
		// app ids are big integers
		$input['app_id'] = trim($input['app_id']);
		if(! preg_match('/^[0-9]+$/i', $input['app_id'])) {
		  $input['app_id'] = '';
		}
	}

	if (!defined('FB_FANPAGE')) {
		// fanpage ids are big integers
		$input['fanpage'] = trim($input['fanpage']);
		if(! preg_match('/^[0-9]+$/i', $input['fanpage'])) {
		  $input['fanpage'] = '';
		}
	}

	$input = apply_filters('fb_validate_options',$input); // filter to let sub-plugins validate their options too
	*/
	return $input;
}

?>
