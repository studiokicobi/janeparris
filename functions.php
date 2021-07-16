<?php

/**
 * JANE PARRIS
 *
 * This file adds functions to the Jane Parris theme.
 *
 * @package Jane Parris
 * @author  Colin Lewis
 * @link    https://colinlewis.se/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action('after_setup_theme', 'janeparris_localization_setup');
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function janeparris_localization_setup()
{

	load_child_theme_textdomain(genesis_get_theme_handle(), get_stylesheet_directory() . '/languages');
}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action('after_setup_theme', 'genesis_child_gutenberg_support');
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support()
{ // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if (function_exists('genesis_register_responsive_menus')) {
	genesis_register_responsive_menus(genesis_get_config('responsive-menus'));
}

add_action('wp_enqueue_scripts', 'janeparris_enqueue_scripts_styles');
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function janeparris_enqueue_scripts_styles()
{

	$appearance = genesis_get_config('appearance');

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		null
	);

	wp_enqueue_style('dashicons');

	if (genesis_is_amp()) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[genesis_get_theme_handle()],
			genesis_get_theme_version()
		);
	}
}

add_filter('body_class', 'janeparris_body_classes');
/**
 * Add additional classes to the body element.
 *
 * @since 3.4.1
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function janeparris_body_classes($classes)
{

	if (!genesis_is_amp()) {
		// Add 'no-js' class to the body class values.
		$classes[] = 'no-js';
	}
	return $classes;
}

add_action('genesis_before', 'janeparris_js_nojs_script', 1);
/**
 * Echo the script that changes 'no-js' class to 'js'.
 *
 * @since 3.4.1
 */
function janeparris_js_nojs_script()
{

	if (genesis_is_amp()) {
		return;
	}

?>
	<script>
		//<![CDATA[
		(function() {
			var c = document.body.classList;
			c.remove('no-js');
			c.add('js');
		})();
		//]]>
	</script>
<?php
}

add_filter('wp_resource_hints', 'janeparris_resource_hints', 10, 2);
/**
 * Add preconnect for Google Fonts.
 *
 * @since 3.4.1
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function janeparris_resource_hints($urls, $relation_type)
{

	if (wp_style_is(genesis_get_theme_handle() . '-fonts', 'queue') && 'preconnect' === $relation_type) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}

add_action('after_setup_theme', 'janeparris_theme_support', 9);
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function janeparris_theme_support()
{

	$theme_supports = genesis_get_config('theme-supports');

	foreach ($theme_supports as $feature => $args) {
		add_theme_support($feature, $args);
	}
}

add_action('after_setup_theme', 'janeparris_post_type_support', 9);
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function janeparris_post_type_support()
{

	$post_type_supports = genesis_get_config('post-type-supports');

	foreach ($post_type_supports as $post_type => $args) {
		add_post_type_support($post_type, $args);
	}
}

// Adds image sizes.
add_image_size('sidebar-featured', 75, 75, true);
add_image_size('genesis-singular-images', 702, 526, true);

// Removes header right widget area.
unregister_sidebar('header-right');

// Removes secondary sidebar.
unregister_sidebar('sidebar-alt');

// Removes site layouts.
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-content-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');

// Repositions primary navigation menu.
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_header', 'genesis_do_nav', 12);

// Repositions the secondary navigation menu.
remove_action('genesis_after_header', 'genesis_do_subnav');
add_action('genesis_footer', 'genesis_do_subnav', 10);

add_filter('wp_nav_menu_args', 'janeparris_secondary_menu_args');
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function janeparris_secondary_menu_args($args)
{

	if ('secondary' === $args['theme_location']) {
		$args['depth'] = 1;
	}

	return $args;
}

add_filter('genesis_author_box_gravatar_size', 'janeparris_author_box_gravatar');
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function janeparris_author_box_gravatar($size)
{

	return 90;
}

add_filter('genesis_comment_list_args', 'janeparris_comments_gravatar');
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function janeparris_comments_gravatar($args)
{

	$args['avatar_size'] = 60;
	return $args;
}

// end Genesis boilerplate

// ----------------------------------------------------------------
// # ADD BLOCKS
// ----------------------------------------------------------------

if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_60f0c8ac875fe',
		'title' => 'Block: box testimonial',
		'fields' => array(
			array(
				'key' => 'field_60f0c8bb8301b',
				'label' => 'Testimonial text',
				'name' => 'testimonial_text',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'new_lines' => '',
				'acfe_textarea_code' => 0,
			),
			array(
				'key' => 'field_60f0c8d58301c',
				'label' => 'Testimonial author',
				'name' => 'testimonial_author',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_60f0c8df8301d',
				'label' => 'Testimonial author role and association',
				'name' => 'role',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/box-testimonial',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfe_display_title' => '',
		'acfe_autosync' => '',
		'acfe_form' => 0,
		'acfe_meta' => '',
		'acfe_note' => '',
	));

	acf_add_local_field_group(array(
		'key' => 'group_60f0ca57a9ef3',
		'title' => 'Block: FAQ',
		'fields' => array(
			array(
				'key' => 'field_60f0ca5eca83c',
				'label' => 'FAQ sections',
				'name' => 'faq_sections',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_repeater_stylised_button' => 0,
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_60f0ca74ca83d',
						'label' => 'FAQ heading',
						'name' => 'faq_heading',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0ca81ca83e',
						'label' => 'FAQ text',
						'name' => 'faq_text',
						'type' => 'textarea',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => '',
						'rows' => '',
						'new_lines' => '',
						'acfe_textarea_code' => 0,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/faq',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfe_display_title' => '',
		'acfe_autosync' => '',
		'acfe_form' => 0,
		'acfe_meta' => '',
		'acfe_note' => '',
	));

	acf_add_local_field_group(array(
		'key' => 'group_60f0b41f66aa1',
		'title' => 'Block: home page extended hero',
		'fields' => array(
			array(
				'key' => 'field_60f0b4b790911',
				'label' => 'Heading',
				'name' => 'heading',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_60f0b4c390912',
				'label' => 'Buttons',
				'name' => 'buttons',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_repeater_stylised_button' => 0,
				'collapsed' => '',
				'min' => 1,
				'max' => 2,
				'layout' => 'row',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_60f0b50890913',
						'label' => 'Button text',
						'name' => 'button_text',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0b51190914',
						'label' => 'Button link',
						'name' => 'button_link',
						'type' => 'page_link',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'page',
						),
						'taxonomy' => '',
						'allow_null' => 0,
						'allow_archives' => 1,
						'multiple' => 0,
					),
				),
			),
			array(
				'key' => 'field_60f0b53c90915',
				'label' => 'Background image',
				'name' => 'background_image',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'uploader' => '',
				'acfe_thumbnail' => 0,
				'return_format' => 'url',
				'preview_size' => 'medium',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'library' => 'all',
			),
			array(
				'key' => 'field_60f0b5480beb2',
				'label' => 'Testimonial slider',
				'name' => 'testimonial_slider',
				'type' => 'repeater',
				'instructions' => 'The testimonial slider should contain 3-5 testimonials.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_repeater_stylised_button' => 0,
				'collapsed' => '',
				'min' => 3,
				'max' => 5,
				'layout' => 'table',
				'button_label' => 'Add testimonial',
				'sub_fields' => array(
					array(
						'key' => 'field_60f0b55a0beb3',
						'label' => 'Testimonial',
						'name' => 'testimonial',
						'type' => 'textarea',
						'instructions' => 'Recommended maximum length: 270 characters/50 words',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => 270,
						'rows' => 4,
						'new_lines' => '',
						'acfe_textarea_code' => 0,
					),
					array(
						'key' => 'field_60f0b5b00beb4',
						'label' => 'Author',
						'name' => 'author',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0b5c60beb5',
						'label' => 'Author age (optional)',
						'name' => 'author_age',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0b5d70beb6',
						'label' => 'Role title and association (optional)',
						'name' => 'role',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
			),
			array(
				'key' => 'field_60f0b62cc0b79',
				'label' => 'Services',
				'name' => 'services',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_repeater_stylised_button' => 0,
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'row',
				'button_label' => 'Add testimonial to the slider',
				'sub_fields' => array(
					array(
						'key' => 'field_60f0b64bc0b7a',
						'label' => 'Service title',
						'name' => 'service_title',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0b657c0b7b',
						'label' => 'Service strapline',
						'name' => 'service_strapline',
						'type' => 'text',
						'instructions' => 'Recommended length for the subsidiary heading: 5-15 words',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0b6ddc0b7c',
						'label' => 'Service description',
						'name' => 'service_description',
						'type' => 'textarea',
						'instructions' => 'Recommended maximum length: 200 characters/25 words',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => '',
						'rows' => '',
						'new_lines' => '',
						'acfe_textarea_code' => 0,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/home-hero',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfe_display_title' => '',
		'acfe_autosync' => '',
		'acfe_form' => 0,
		'acfe_meta' => '',
		'acfe_note' => '',
	));

	acf_add_local_field_group(array(
		'key' => 'group_60f0cd3e106b9',
		'title' => 'Block: sortable testimonials',
		'fields' => array(
			array(
				'key' => 'field_60f0cd4ca293c',
				'label' => 'Testimonials',
				'name' => 'testimonials',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_repeater_stylised_button' => 0,
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'row',
				'button_label' => 'Add testimonial',
				'sub_fields' => array(
					array(
						'key' => 'field_60f0cdd9a2941',
						'label' => 'Testimonial category',
						'name' => 'testimonial_category',
						'type' => 'select',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'students' => 'What students say',
							'parents' => 'What parents say',
							'writing' => 'Writing classes',
							'college' => 'College essays',
						),
						'default_value' => false,
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'return_format' => 'value',
						'ajax' => 0,
						'placeholder' => '',
					),
					array(
						'key' => 'field_60f0cd70a293d',
						'label' => 'Testimonial text',
						'name' => 'testimonial_text',
						'type' => 'textarea',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => '',
						'rows' => '',
						'new_lines' => '',
						'acfe_textarea_code' => 0,
					),
					array(
						'key' => 'field_60f0cd96a293e',
						'label' => 'Testimonial author',
						'name' => 'testimonial_author',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0cd9fa293f',
						'label' => 'Testimonial author role and association (optional)',
						'name' => 'role',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_60f0cdbba2940',
						'label' => 'Student age (optional)',
						'name' => 'student_age',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/sortable-testimonial',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfe_display_title' => '',
		'acfe_autosync' => '',
		'acfe_form' => 0,
		'acfe_meta' => '',
		'acfe_note' => '',
	));

	acf_add_local_field_group(array(
		'key' => 'group_60f0bfe7ee978',
		'title' => 'Block: testimonial + image',
		'fields' => array(
			array(
				'key' => 'field_60f0c01c82f10',
				'label' => 'Layout position',
				'name' => 'layout',
				'type' => 'select',
				'instructions' => 'Choose whether to align the image on the right or left side of the text.',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'l' => 'Image on the left',
					'r' => 'Image on the right',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_60f0c0f482f11',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'image',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'uploader' => '',
				'acfe_thumbnail' => 0,
				'return_format' => 'url',
				'preview_size' => 'medium',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
				'library' => 'all',
			),
			array(
				'key' => 'field_60f0c11882f12',
				'label' => 'Text',
				'name' => 'text',
				'type' => 'textarea',
				'instructions' => 'Recommended maximum length: 230 characters/30 words',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'new_lines' => '',
				'acfe_textarea_code' => 0,
			),
			array(
				'key' => 'field_60f0c26527721',
				'label' => 'Testimonial author',
				'name' => 'author',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_60f0c27427722',
				'label' => 'Testimonial author role and association',
				'name' => 'role',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/testimonial-image',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfe_display_title' => '',
		'acfe_autosync' => '',
		'acfe_form' => 0,
		'acfe_meta' => '',
		'acfe_note' => '',
	));

endif;
