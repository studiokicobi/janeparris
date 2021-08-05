<?php

/**
 * Archive template
 */

// Forces excerpts
add_filter('genesis_pre_get_option_content_archive', 'sk_show_excerpts');
function sk_show_excerpts()
{
    return 'excerpts';
}

// Removes meta from entry header.
remove_filter('genesis_post_info', 'janeparris_entry_meta_header');
remove_action('genesis_before_entry', 'genesis_post_meta');

// Remove entry content
remove_action('genesis_entry_content', 'genesis_do_post_content');

// Wrap post in div
add_action('genesis_before_while', 'custom_posts_wrap_open');
function custom_posts_wrap_open()
{
    echo '<div class="writing-archive">';
}

add_action('genesis_after_endwhile', 'custom_posts_wrap_close');
function custom_posts_wrap_close()
{
    echo '</div>';
}

// Genesis
genesis();
