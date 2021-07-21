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

// ----------------------------------------------------------------
// end Genesis boilerplate
// ----------------------------------------------------------------


/* Enqueue custom scripts and styles
------------------------------------*/

// Scripts
add_action('wp_enqueue_scripts', function () {
	// Swiper
	// wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js', array('jquery'), '1.0.0', true);
	// wp_enqueue_script('swiper-script', get_stylesheet_directory_uri() . '/js/swiper-main.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js', [], false, true,);
	wp_enqueue_script('swiper-script', get_stylesheet_directory_uri() . '/js/swiper-main.js', ['swiper'], false, true);
	// Filterizr 
	wp_enqueue_script('filterizr', get_stylesheet_directory_uri() . '/js/vanilla.filterizr.min.js', [], false, true,);
	wp_enqueue_script('filtering-script', get_stylesheet_directory_uri() . '/js/filtering-main.js', ['filterizr'], false, true);

	// Custom
	wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'),  '1.0.0', true);

	// Ajax pagination
	wp_enqueue_script('ajax-pagination.js', get_stylesheet_directory_uri() . "/js/ajax-pagination.js", array('jquery'));
});


// CSS
add_action('wp_enqueue_scripts', function () {
	$themecsspath = get_stylesheet_directory() . '/css/custom.css';
	wp_enqueue_style(
		'child-theme',
		get_stylesheet_directory_uri() . '/css/custom.css',
		array(),
		filemtime($themecsspath)
	);
});


/* Add partials to pages
------------------------------------*/

// Add Testimonial partial if page != Testimonial 
add_action('genesis_before_footer', 'test1', 5);
function test1()
{
	if (is_page('testimonials')) {
		// do nothing
	} else {
		echo '<p style="background: red;">Testimonial partial here</p>';
		// get_template_part('partials/footer');
	}
}

// Add Consultation Booking partial if page != Book a Consultion 
add_action('genesis_before_footer', 'test2', 5);
function test2()
{
	if (is_page('book-a-consultation')) {
		// do nothing
	} else {
		echo '<p style="background: pink;">Book a consultation partial here</p>';
		// get_template_part('partials/footer');
	}
}

// Add footer nav
add_action('genesis_footer', 'footer_content', 5);
function footer_content()
{
	// Add first footer nav (primary nav)
	wp_nav_menu(
		array(
			'menu' => 'primary',
			'container_class' => 'footer-nav-1',
			// do not fall back to first non-empty menu
			'theme_location' => '__no_such_location',
			// do not fall back to wp_page_menu()
			'fallback_cb' => false
		)
	);
}

// Add post footer copyright & nav
add_action('genesis_after_footer', 'footer_post_content', 5);
function footer_post_content()
{
	echo '<div class="footer__after-footer>';
	// Add copyright
	echo '<div class="copyright">Copyright © 2020-' . date("Y") . ' Jane Parris</div>';
	// Add second footer nav (Privacy, etc.)
	wp_nav_menu(
		array(
			'menu' => 'footer',
			'container_class' => 'footer-nav-2',
			// do not fall back to first non-empty menu
			'theme_location' => '__no_such_location',
			// do not fall back to wp_page_menu()
			'fallback_cb' => false
		)
	);
	echo '</div>';
}


/* Add inline styles to editor
------------------------------------*/

// Fix the ACF button colors
// There is a conflict where the repeater buttons
// (among others) get blue text on blue bgs

add_action('admin_head', 'custom_acf_styles');

function custom_acf_styles()
{
	echo '<style>
		.acf-actions .acf-button {
			background-color: #f6f7f7;
			color: #0a4b78;
		}
        .acf-actions .acf-button:hover {
            background-color: #f0f0f1;
			color: #0a4b78;
        }
        .acf-actions .acf-button:hover,
		.acf-actions .acf-button:focus {
            background-color: #f0f0f1;
			color: #0a4b78;
        }
    </style>';
}


/* Disable fullscreen editor
------------------------------------*/

$user = wp_get_current_user();

if ($user = 'Colin Lewis') {
	function disable_editor_fullscreen_by_default()
	{
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script('wp-blocks', $script);
	}
	add_action('enqueue_block_editor_assets', 'disable_editor_fullscreen_by_default');
}


/* Allow SVG upload
------------------------------------*/
function additional_mime($mime_types)
{
	$mime_types['svg'] = 'image/svg+xml';
	return $mime_types;
}
add_filter('upload_mimes', 'additional_mime', 1, 1);


/* Register taxonomy for testimonials
------------------------------------*/

add_action('init', 'create_taxonomy', 0);

function create_taxonomy()
{
	// Labels
	$labels = array(
		'name' => _x('Testimonial Categories', 'categories'),
		'singular_name' => _x('Testimonial Category', 'category'),
		'search_items' => __('Search Testimonial Categories'),
		'popular_items' => __('Popular Testimonial Categories'),
		'all_items' => __('All Testimonial Categories'),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __('Edit Testimonial Category'),
		'update_item' => __('Update Testimonial Category'),
		'add_new_item' => __('Add New Testimonial Category'),
		'new_item_name' => __('New Testimonial Category Name'),
		'separate_items_with_commas' => __('Separate testimonial categories with commas'),
		'add_or_remove_items' => __('Add or remove testimonial categories'),
		'choose_from_most_used' => __('Choose from the most used testimonial categories'),
		'menu_name' => __('Categories'),
	);

	// Register the taxonomy
	register_taxonomy('services', 'testimonials', array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array('slug' => 'category'),
	));
}

// ----------------------------------------------------------------
// # ADD BLOCKS
// ----------------------------------------------------------------

if (function_exists('acf_register_block_type')) :

	acf_register_block_type(array(
		'name' => 'box-testimonial',
		'title' => 'Box testimonial',
		'description' => '',
		'category' => 'common',
		'keywords' => array(),
		'post_types' => array(),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/box-testimonial.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'editor-quote',
		),
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

	acf_register_block_type(array(
		'name' => 'faq',
		'title' => 'FAQ',
		'description' => '',
		'category' => 'common',
		'keywords' => array(),
		'post_types' => array(),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/faq.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'format-chat',
		),
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

	acf_register_block_type(array(
		'name' => 'home-hero',
		'title' => 'Home page extended hero',
		'description' => '',
		'category' => 'common',
		'keywords' => array(),
		'post_types' => array(
			0 => 'page',
		),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/home-hero.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'align-full-width',
		),
		'supports' => array(
			'align' => false,
			'mode' => true,
			'multiple' => false,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

	acf_register_block_type(array(
		'name' => 'sortable-testimonial',
		'title' => 'Sortable testimonial',
		'description' => '',
		'category' => 'common',
		'keywords' => array(),
		'post_types' => array(),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/sortable-testimonials.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'editor-quote',
		),
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

	acf_register_block_type(array(
		'name' => 'testimonial-image',
		'title' => 'Testimonial + image',
		'description' => '',
		'category' => 'common',
		'keywords' => array(),
		'post_types' => array(),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/testimonial-image.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'editor-quote',
		),
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

	acf_register_block_type(array(
		'name' => 'toc',
		'title' => 'Table of Contents',
		'description' => '',
		'category' => 'common',
		'keywords' => array(
			0 => 'toc',
			1 => 'table',
			2 => 'contents',
		),
		'post_types' => array(),
		'mode' => 'edit',
		'align' => '',
		'align_content' => NULL,
		'render_template' => 'blocks/toc.php',
		'render_callback' => '',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => array(
			'background' => '#8224e3',
			'foreground' => '#ffffff',
			'src' => 'list-view',
		),
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
			'jsx' => false,
			'align_content' => false,
			'anchor' => false,
		),
	));

endif;
