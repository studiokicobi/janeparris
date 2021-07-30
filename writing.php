<?php

/**
 * Template Name: Writing
 * Description: Template for the Writing Tricks page
 */


//* Add custom body class
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes)
{
    $classes[] = 'writing';
    return $classes;
}

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

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
        echo '<div class="featured">';
        while ($query_sticky->have_posts()) {
            $query_sticky->the_post();

            // Get the first letter of the title in order to create a custom class
            $string = get_the_title();
            $firstChar = mb_substr($string, 0, 1, "UTF-8");

            echo '<div class="featured__item">';
            echo '<div class="featured__item-wrapper bg-letter" style="background-image: url(' . get_stylesheet_directory_uri() . '/images/alphabet/' . $firstChar . '.svg);">';
            echo '<div class="featured__item-content">';
            echo '<h2 class="featured__item-heading">';
            echo '<a class="featured__item-link" href="' . get_the_permalink() . '" rel="bookmark" title="Read ' . esc_html(get_the_title()) . '">' . get_the_title() . '</a>';
            echo '</h2>';
            echo '<div class="featured__item-label" aria-hidden="true"><span>Read</span></div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        // no posts found
    }
    // Reset 
    wp_reset_postdata();

    echo '<div class="writing__lists-wrapper clearfix">';

    // List non-sticky posts
    echo '<div class="writing__latest-list">';
    echo '<h2>' . get_field('regular') . '</h2>';
    //Protect against arbitrary paged values
    // $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

    $args_regular_posts = array(
        'posts_per_page' => 10,
        // 'paged' => $paged,
        'post_status'         => 'publish',
        'post__not_in' => get_option('sticky_posts')
    );
    $query_regular_posts = new WP_Query($args_regular_posts);

    // echo '<div id="content">';

    if ($query_regular_posts->have_posts()) {
        echo '<ul>';
        while ($query_regular_posts->have_posts()) {
            $query_regular_posts->the_post();
            echo '<li><a href="' . get_the_permalink() . '" rel="bookmark" title="Permanent link to ' . esc_html(get_the_title()) . '">' . get_the_title() . '</a></li>';
        }
        echo '</ul>';
    } else {
        // no posts found
    }

    // Query the non-sticky posts
    $args_count_posts = array(
        'posts_per_page' => -1, // all posts
        'post_status'         => 'publish',
        'post__not_in' => get_option('sticky_posts')
    );
    $query_count_posts = new WP_Query($args_count_posts);

    // Count the total number of posts
    $total_posts = $query_count_posts->post_count;

    // If greater than 10 posts, show the archive link
    if ($total_posts > 10) {
        echo '<a href="' . get_site_url() . '/writing-archive/" class="button archive-link">See all writing</a>';
    } else {
        // Fewer than 10 posts – do nothing
    }


    // Add pagination
    // echo '<div id="pagination" class="pagination">';

    // $big = 999999999;

    // echo paginate_links(array(
    //     'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    //     'format' => '?paged=%#%',
    //     'prev_next' => false,
    //     'current' => max(1, get_query_var('paged')),
    //     'total' => $query_regular_posts->max_num_pages
    // ));

    echo '</div>';
    // echo '</div>';

    // Reset 
    wp_reset_postdata();

    // List categories
    echo '<div class="writing__category-list">';
    echo '<h2>' . get_field('category') . '</h2>';
    echo '<ul>';
    wp_list_categories(array(
        'title_li' => ''
    ));
    echo '</ul>';
    echo '</div>';
}

echo '</div>'; // .writing__lists-wrapper

genesis();
