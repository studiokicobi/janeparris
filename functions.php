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


/**
 * Enqueue custom scripts and styles
 */

// Scripts
add_action('wp_enqueue_scripts', function () {
	// Swiper
	wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js', [], false, true,);
	wp_enqueue_script('swiper-script', get_stylesheet_directory_uri() . '/js/swiper-main.js', ['swiper'], false, true);

	//Isotope
	wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', [], false, true,);

	// Flickity
	wp_enqueue_script('flickity', get_stylesheet_directory_uri() . '/js/flickity.pkgd.min.js', [], false, true,);
	wp_enqueue_script('flickity-fade', get_stylesheet_directory_uri() . '/js/flickity-fade.js', ['flickity'], false, true);

	// Custom
	wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'),  '1.0.0', true);

	// Ajax pagination
	// wp_enqueue_script('ajax-pagination.js', get_stylesheet_directory_uri() . "/js/ajax-pagination.js", array('jquery'));

	// CSS
	$themecsspath = get_stylesheet_directory() . '/css/custom.css';
	wp_enqueue_style(
		'child-theme',
		get_stylesheet_directory_uri() . '/css/custom.css',
		array(),
		filemtime($themecsspath)
	);

	// Adobe Typekit CSS
	wp_enqueue_style('style-name', 'https://use.typekit.net/wco8mot.css');
});


// Preload local fonts
add_action('wp_head', function () {
	echo '
    <link rel="preload" href="' . get_stylesheet_directory() . 'fonts/tiempos-text-web-regular.woff2" 
    as="font" type="font/woff2" crossorigin="anonymous">
   
    <link rel="preload" href="' . get_stylesheet_directory() . 'fonts/tiempos-text-web-regular-italic.woff2" 
    as="font" type="font/woff2" crossorigin="anonymous">

    <link rel="preload" href="' . get_stylesheet_directory() . 'fonts/tiempos-headline-web-black.woff2" 
    as="font" type="font/woff2" crossorigin="anonymous">
	';
});


/**
 * Make last space in a sentence a non breaking space to prevent typographic widows.
 *
 * @param type $str
 * @return string
 */

//https://www.binarymoon.co.uk/2017/04/fixing-typographic-widows-wordpress/
function theme_widont($str = '')
{

	// Strip spaces.
	$str = trim($str);
	// Find the last space.
	$space = strrpos($str, ' ');

	// If there's a space then replace the last on with a non breaking space.
	if (false !== $space) {
		$str = substr($str, 0, $space) . '&nbsp;' . substr($str, $space + 1);
	}

	// Return the string.
	return $str;
}

add_filter('the_title', 'theme_widont');


/**
 * Responsive Image Helper Function
 */
/**
 * @param string $image_id the id of the image (from ACF or similar)
 * @param string $image_size the size of the thumbnail image or custom image size
 * @param string $max_width the max width this image will be shown to build the sizes attribute 
 * 
 * https://www.awesomeacf.com/responsive-images-wordpress-acf/
 * Usage example:
 * <img class="my_class" <?php awesome_acf_responsive_image(get_field( 'image_1' ),'thumb-640','640px'); ?>  alt="text" /> 
 */

function awesome_acf_responsive_image($image_id, $image_size, $max_width)
{
	// check the image ID is not blank
	if ($image_id != '') {
		// set the default src image size
		$image_src = wp_get_attachment_image_url($image_id, $image_size);
		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset($image_id, $image_size);
		// generate the markup for the responsive image
		echo 'src="' . $image_src . '" srcset="' . $image_srcset . '" sizes="(max-width: ' . $max_width . ') 100vw, ' . $max_width . '"';
	}
}

/**
 * Edit meta info in post headers 
 */
add_filter('genesis_post_info', 'janeparris_entry_meta_header');
function janeparris_entry_meta_header($post_info)
{
	echo '<p class="entry-meta">';
	echo do_shortcode('[post_date]');
	echo do_shortcode('[post_categories sep=" · " before=""]');
	echo '</p>';
}


/**
 * Remove meta from footer
 */
add_action('genesis_entry_content', 'janeparris_remove_post_meta');
function janeparris_remove_post_meta()
{
	if (is_singular('page')) {
		return;
	}

	remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
	remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
	remove_action('genesis_entry_footer', 'genesis_post_meta');
}

/**
 * Add pagination to single posts
 */
add_action('genesis_after_entry', 'janeparris_post_meta');
function janeparris_post_meta()
{
	if (is_single()) {
		echo '<p class="single-pagination">';
		previous_post_link('%link', __('Previously'));
		next_post_link('%link', __('Next'));
		echo '</p>';
	}
}

/**
 * Gutenberg custom stylesheet
 */
add_theme_support('editor-styles');
add_editor_style('css/editor-style.css');


// ----------------------------------------------------------------
// Add partials to pages
// ----------------------------------------------------------------

/**
 * Add Related Posts partial if singular 
 */
add_action('genesis_after_entry', 'related_posts');
function related_posts()
{
	if (is_single()) {
		get_template_part('partials/related', 'posts');
	}
}


/**
 * Add Testimonial partial if page != Testimonial 
 */
add_action('genesis_before_footer', 'random_testimonial', 5);
function random_testimonial()
{
	if (is_page('testimonials')) {
		// do nothing
	} else {
		get_template_part('partials/testimonial', 'footer');
	}
}


/**
 * Add Consultation Booking partial if page != Book a Consultion
 */
add_action('genesis_before_footer', 'footer_cta', 5);
function footer_cta()
{
	if (is_page('book-a-consultation')) {
		// do nothing
	} else {
		get_template_part('partials/cta', 'footer');
	}
}

/**
 * Add footer nav
 */
add_action('genesis_footer', 'footer_content', 5);
function footer_content()
{
	$items = wp_get_nav_menu_items('footer1');

	if (!empty($items)) : ?>
		<nav class="footer-primary-nav">
			<a href="/" title="Home" class="footer-primary-nav__logo">Jane Parris – Home</a>
			<ul class="footer-primary-nav__list">
				<?php foreach ($items as $item) : ?>
					<li class="footer-primary-nav__list-item">
						<a class="footer-primary-nav__list-item-link" href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php
			// Get the social links from the ACF options page
			if (have_rows('social_links', 'option')) :
				echo '<ul class="footer-primary-nav__list-social">';
				while (have_rows('social_links', 'option')) : the_row();
					$channel_icon = get_sub_field('channel_icon');
					echo '<li class="footer-primary-nav__list-item-social" id="social-link-' . get_row_index() . '">';
					echo '<a class="footer-primary-nav__list-item-link-social" href="' . get_sub_field('channel_link') . '">';
					echo '<img src="' . esc_url($channel_icon['url']) . '" alt= "' . get_sub_field('channel_name') . '" />';
					echo '</a>';
					echo '</li>';
				endwhile;
				echo '</ul>';
			else :
			// no rows found 	
			endif;
			?>
		</nav>

	<?php
	else : // if no menu items
		echo '<p class="msg msg--error">(This menu contains no items.)</p>';
	endif;
}


/**
 * Add post footer copyright & nav
 */
add_action('genesis_after_footer', 'footer_post_content', 5);
function footer_post_content()
{
	echo '<div class="site-footer__secondary">';
	echo '<div class="wrap">';
	echo '<div class="site-footer__secondary-content">';
	// Add copyright
	echo '<div class="copyright">Copyright © ' . date("Y") . ' Jane Parris</div>';
	// Add second footer nav (Privacy, etc.)
	wp_nav_menu(
		array(
			'menu' => 'footer2',
			'container_class' => 'footer-nav-2',
			// do not fall back to first non-empty menu
			'theme_location' => '__no_such_location',
			// do not fall back to wp_page_menu()
			'fallback_cb' => false
		)
	);
	echo '</div>';
	echo '</div>';
	echo '</div>';
}


/**
 * Add inline styles to editor
 */

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


/**
 * Disable fullscreen editor
 */

$user = wp_get_current_user();

if ($user = 'Colin Lewis') {
	function disable_editor_fullscreen_by_default()
	{
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script('wp-blocks', $script);
	}
	add_action('enqueue_block_editor_assets', 'disable_editor_fullscreen_by_default');
}


/**
 * Allow SVG upload
 */
function additional_mime($mime_types)
{
	$mime_types['svg'] = 'image/svg+xml';
	return $mime_types;
}
add_filter('upload_mimes', 'additional_mime', 1, 1);


/**
 * Register taxonomy for testimonials
 */
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

/** 
 * Automatically generate a Table of Contents
 */

//<p><a href="#top">Top</a></p>


// 1. Automatically add IDs and anchors to headings
add_action('genesis_before_header', 'auto_id_headings');
function auto_id_headings($content)
{
	$content = preg_replace_callback('/(\<h[2-3](.*?))\>(.*)(<\/h[2-3]>)/i', function ($matches) {

		if (stripos($matches[0], '<h3')) :
			$tag = 'subheading';
		else :
			$tag = '';
		endif;

		if (!stripos($matches[0], 'id=')) :
			$heading_link = '<a href="#' . sanitize_title($matches[3]) . '"></a>';
			$matches[0] = $matches[1] . $matches[2] . ' id="' . sanitize_title($matches[3]) . '" class="' . $tag . '">' . $heading_link . $matches[3] . $matches[4];
		endif;

		return $matches[0];
	}, $content);

	return $content;
}
add_filter('the_content', 'auto_id_headings');


// 2. Add ToC filter
add_action('genesis_before_header', 'get_table_of_content', 5);

function get_table_of_content($content)
{
	ob_start();
	preg_match_all("/<h[2,3](?:\sid=\"(.*)\")?(?:.*)?>(.*)<\/h[2,3]>/", $content, $matches);
	$tags = $matches[0];
	$ids = $matches[1];
	$names = $matches[2];
	?>
	<div id="top" class="toc">
		<p class="toc__title visuallyhidden"><strong>Table of Contents</strong></p>
		<ul class="toc__list">
			<?php for ($i = 0; $i < count($names); $i++) { ?>
				<?php if (strpos($tags[$i], "h2") === false || strpos($tags[$i], "class=\"nitoc\"") !== false) continue;
				?>

				<li class="toc__list-item">
					<?php
					$raw_title = $names[$i];
					$clean_title = sanitize_title_with_dashes($raw_title);
					?>
					<a class="toc__list-item-link" href="#<?php echo $clean_title; ?>"><?php echo $raw_title; ?></a>

					<?php if ($i !== count($names) && strpos($tags[$i + 1], "h3") !== false) { ?>
						<ul class="toc__list toc__list--secondary">
							<?php for ($j = 0; $j < count($names) - 1; $j++) { ?>
								<?php $sub_index = $i + $j; ?>
								<?php if ($j != 0 && strpos($tags[$sub_index], "h2") !== false) break; ?>
								<?php if (strpos($tags[$sub_index], "h3") === false || strpos($tags[$sub_index], "class=\"nitoc\"") !== false) continue; ?>
								<li class="toc__list-item toc__list-item--secondary">
									<?php
									$raw_sub_title = $names[$sub_index];
									$clean_sub_title = sanitize_title_with_dashes($raw_sub_title);
									?>
									<a class="toc__list-item-link toc__list-item-link--secondary" href="#<?php echo $clean_sub_title; ?>"><?php echo $raw_sub_title; ?></a>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>

				</li>
			<?php } ?>
		</ul>
	</div>
<?php
	return ob_get_clean();
}

// 3. Add the Table of Contents filter for use in blocks/toc.php

add_action('genesis_entry_content', 'add_table_of_content');
function add_table_of_content($content)
{
	return str_replace("{{TABLE_OF_CONTENTS}}", get_table_of_content($content), $content);
}
add_filter('the_content', 'add_table_of_content');


// End of Table of Contents functions


// ----------------------------------------------------------------
// # Remove comments support completely
// ----------------------------------------------------------------
// https://wordpress.stackexchange.com/questions/11222/is-there-any-way-to-remove-comments-function-and-section-totally

// Removes from admin menu
add_action('admin_menu', 'janeparris_admin_menus');
function janeparris_admin_menus()
{
	remove_menu_page('edit-comments.php');
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 100);

function remove_comment_support()
{
	remove_post_type_support('post', 'comments');
	remove_post_type_support('page', 'comments');
}
// Removes from admin bar
function janeparris_admin_bar_render()
{
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'janeparris_admin_bar_render');


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
