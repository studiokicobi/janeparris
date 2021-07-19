<?php

/**
 * Template Name: Writing
 * Description: Template for the Writing Tricks page
 */


//* Add custom body class
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes)
{
    $classes[] = 'hello-bar';
    return $classes;
}

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

// get_header(); // displays header

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'writing_custom_loop');
function writing_custom_loop()
{
    echo '<h1>' . get_the_title() . '</h1>';

    // Sticky posts
    echo '<h2>' . get_field('sticky') . '</h2>';

    $args_sticky = array(
        'post__in'            => get_option('sticky_posts'),
        'post_status'         => 'publish'
    );
    $query_sticky = new WP_Query($args_sticky);

    if ($query_sticky->have_posts()) {
        echo '<ul>';
        while ($query_sticky->have_posts()) {
            $query_sticky->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
        echo '</ul>';
    } else {
        // no posts found
    }
    // Reset 
    wp_reset_postdata();




    // List non-sticky posts
    echo '<div id="content">';
    echo '<h2>' . get_field('regular') . '</h2>';


    //Protect against arbitrary paged values
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

    $args_regular_posts = array(
        'posts_per_page' => 2,
        'paged' => $paged,
        'post_status'         => 'publish',
        'post__not_in' => get_option('sticky_posts')
    );
    $query_regular_posts = new WP_Query($args_regular_posts);

    if ($query_regular_posts->have_posts()) {
        echo '<ul>';
        while ($query_regular_posts->have_posts()) {
            $query_regular_posts->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
        echo '</ul>';
    } else {
        // no posts found
    }

    // Add pagination
    echo '<div id="pagination">';

    $big = 999999999;

    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $query_regular_posts->max_num_pages
    ));

    echo '</div>';
    echo '</div>';

    // Reset 
    wp_reset_postdata();



    // List categories
    echo '<h2>' . get_field('category') . '</h2>';
    echo '<ul>';
    wp_list_categories(array(
        'title_li' => ''
    ));
    echo '</ul>';
}

genesis();
