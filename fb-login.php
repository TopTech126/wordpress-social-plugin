<?php
/**
 * Check to see if the active admin has authenticated with Facebook
 * If not, display a warning message
 *
 * @since 1.0
 */
function fb_check_connected_accounts() {
	$current_user = wp_get_current_user();

	global $facebook;
	
	if ( ! isset( $facebook ) )
		return;
	
	$options = get_option('fb_options');

	// check if we have enough info to handle the authFacebook function
	if ( ! $options || empty( $options['app_id'] ) || empty( $options['app_secret'] ) )
		return;

	//see if they have connected their account to facebook
	$fb_data = fb_get_user_meta($current_user->ID, 'fb_data', true);
	
	//if no, show message prompting to connect
	if ( empty( $fb_data['fb_uid'] ) && isset( $options['social_publisher'] ) && isset( $options['social_publisher']['enabled'] ) ) {
		$fb_user = fb_get_current_user();
		
		if ($fb_user) {
			$perms = $facebook->api('/me/permissions', 'GET', array('ref' => 'fbwpp'));
		}
		
		if ($fb_user && isset($perms['data'][0]['manage_pages']) && isset($perms['data'][0]['publish_actions']) && isset($perms['data'][0]['publish_stream'])) {
			$fb_user_data = array('fb_uid' => $fb_user['id'], 'activation_time' => time());
			if ( ! empty( $fb_user['username'] ) )
				$fb_user_data['username'] = $fb_user['username'];

			fb_update_user_meta($current_user->ID, 'fb_data', $fb_user_data);
		}
		else {
			fb_admin_dialog( sprintf( __('Facebook social publishing is enabled. %sLink your Facebook account to your WordPress account</a> to get full functionality, including adding new Posts to your Timeline.', 'facebook' ), '<a href="#" onclick="authFacebook(); return false;">' ), true);
		}
	}
	else {

	}
}
add_action('admin_notices', 'fb_check_connected_accounts');


add_action('admin_init', 'fb_extend_access_token');
function fb_extend_access_token() {
	global $facebook;
	
	if (!$facebook)
		return;

	$facebook->setExtendedAccessToken();
}

/**
 * Gets and returns the current, active Facebook user
 *
 * @since 1.0
 */
function fb_get_current_user() {
	global $facebook;

	if ( ! isset( $facebook ) )
		return;

	try {
		$user = $facebook->api('/me', 'GET', array('ref' => 'fbwpp'));

		return $user;
	}
	catch (WP_FacebookApiException $e) {
	}
}
