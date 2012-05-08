<?php
/**
 * Core header file, invoked by the get_header() function
 *
 * @package Suffusion
 * @subpackage Templates
 */
global $suffusion_unified_options, $suffusion_interactive_text_fields, $suffusion_translatable_fields, $suffusion_skin_dependence, $suf_color_scheme;
if (function_exists('icl_t')) {
	foreach ($suffusion_unified_options as $id => $value) {
		/**
		 * Some strings are set interactively in the admin screens of Suffusion. If you have WPML installed, then there may be translations of such strings.
		 * This code ensures that such translations are picked up, then the unified options array is rewritten so that subsequent calls can pick it up.
		 */
		if (function_exists('icl_t') && in_array($id, $suffusion_translatable_fields) && isset($suffusion_interactive_text_fields[$id])) {
			$value = wpml_t('suffusion-interactive', $suffusion_interactive_text_fields[$id]."|".$id, $value);
		}
		global $$id;
		$$id = $value;
		$suffusion_unified_options[$id] = $value;
	}
}

$queried_id = get_queried_object_id();
$hidden_elements = array();
if ($queried_id != 0) {
	$hide_top_navigation = suffusion_get_post_meta($queried_id, 'suf_hide_top_navigation', true);
	if ($hide_top_navigation) {
		add_filter('suffusion_can_display_top_navigation', 'suffusion_disable_component_for_view');
		$hidden_elements[] = 'no-top-nav';
	}
	$hide_main_navigation = suffusion_get_post_meta($queried_id, 'suf_hide_main_navigation', true);
	if ($hide_main_navigation) {
		add_filter('suffusion_can_display_main_navigation', 'suffusion_disable_component_for_view');
		$hidden_elements[] = 'no-main-nav';
	}
	$hide_header = suffusion_get_post_meta($queried_id, 'suf_hide_header', true);
	if ($hide_header) {
		add_filter('suffusion_can_display_header', 'suffusion_disable_component_for_view');
		$hidden_elements[] = 'no-header';
	}
	$hide_footer = suffusion_get_post_meta($queried_id, 'suf_hide_footer', true);
	if ($hide_footer) {
		add_filter('suffusion_can_display_site_footer', 'suffusion_disable_component_for_view');
		$hidden_elements[] = 'no-site-footer';
	}
}
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
	suffusion_document_header();
	$suffusion_pseudo_template = suffusion_get_pseudo_template_class();
	if (is_singular()) {
		wp_enqueue_script('comment-reply');
	}
	$skin = isset($suf_color_scheme) ? $suf_color_scheme : 'light-theme-gray-1';
	$extra_classes = $suffusion_pseudo_template;
	if (isset($suffusion_skin_dependence[$skin])) {
		$extra_classes = array_merge($extra_classes, $suffusion_skin_dependence[$skin]);
	}
	$extra_classes[] = $skin;

	if (is_page_template('template-custom-post-type-archive.php')) {
		$extra_classes[] = 'custom-post-type-archive';
		$cpt_name = suffusion_get_post_meta($queried_id, 'suf_cpt_post_type', true);
		$extra_classes[] = 'custom-post-type-archive-'.$cpt_name;
	}
	$extra_classes = array_merge($extra_classes, $hidden_elements);
?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/scripts/html5.js" type="text/javascript"></script>
	<![endif]-->
<?php
	global $suf_cpt_layouts, $suffusion_cpt_layouts;
	if (isset($suf_cpt_layouts)) {
		$suffusion_cpt_layouts = suffusion_get_associative_array($suf_cpt_layouts);
	}

	wp_head();
?>
</head>

<body <?php body_class($extra_classes); ?>>
    <?php suffusion_before_page(); ?>
		<?php
			suffusion_before_begin_wrapper();
		?>
		<div id="wrapper" class="fix">
		<?php
			suffusion_after_begin_wrapper();
		?>
			<div id="container" class="fix">
				<?php
					suffusion_after_begin_container();
				?>