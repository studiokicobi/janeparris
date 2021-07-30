<?php

/**
 * Blog template
 */


//* Add custom body class
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes)
{
    $classes[] = 'blog';
    return $classes;
}

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'writing_custom_loop');
function writing_custom_loop()
{
    // echo '<h1>' . get_the_title() . '</h1>';

    echo '<div class="writing__lists-wrapper">';

    // List non-sticky posts
    echo '<div class="writing__latest-list">';
    //Protect against arbitrary paged values
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

    $args_regular_posts = array(
        'posts_per_page' => 10,
        'paged' => $paged,
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

    // Add pagination
    echo '<div id="pagination" class="pagination">';

    $big = 999999999;

    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'prev_next' => false,
        'current' => max(1, get_query_var('paged')),
        'total' => $query_regular_posts->max_num_pages
    ));

    echo '</div>';
}

echo '</div>'; // .writing__lists-wrapper

genesis();
