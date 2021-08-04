<!-- Related articles -->
<div class="related">
    <h2 class="related__heading">Related writing</h2>

    <?php
    $related = new WP_Query(
        array(
            'category__in'   => wp_get_post_categories($post->ID),
            'posts_per_page' => 3,
            'post__not_in'   => array($post->ID),
            'post_status'    => 'publish',
            'orderby'        => 'rand'
        )
    );

    if ($related->have_posts()) {
        echo '<div class="featured push">';
        while ($related->have_posts()) {
            $related->the_post();



            // Get the first letter of the title in order to create a custom class
            $string = get_the_title();
            $firstChar = mb_substr($string, 0, 1, "UTF-8");

            echo '<div class="featured__item">';
            echo '<div class="featured__item-wrapper bg-letter" style="background-image: url(' . get_stylesheet_directory_uri() . '/images/alphabet/' . $firstChar . '.svg);">';
            echo '<div class="featured__item-content">';
            echo '<h3 class="featured__item-heading">';
            echo '<a class="featured__item-link" href="' . get_the_permalink() . '" rel="bookmark" title="Read ' . esc_html(get_the_title()) . '">' . get_the_title() . '</a>';
            echo '</h3>';
            echo '<div class="featured__item-label" aria-hidden="true"><span>Read</span></div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    // Count the number of related posts returned
    $related_count = $related->found_posts;

    // If number of posts is less than 3, set the amount of posts to add
    if ($related_count < '3') {
        if ($related_count = '2') {
            $add_count = '1';
        } else {
            $add_count = '1';
        }
    }

    // Get the current cat ID
    $post_categories = wp_get_post_categories($post_id);
    $cats = array();

    // foreach ($post_categories as $c) {
    //     $cat = get_category($c);
    //     $cats[] = array('name' => $cat->name, 'slug' => $cat->slug);
    // }


    // // New query to add posts
    // $add_related = new WP_Query(
    //     array(
    //         'category__not_in' => array($cats),
    //         'posts_per_page' => 1,
    //         'post__not_in'   => array($related->ID),
    //         'post_status'    => 'publish',
    //         'orderby'        => 'rand'
    //     )
    // );

    // // Run the loop to add the missing posts

    // if ($add_related->have_posts()) {
    //     while ($add_related->have_posts()) {
    //         $add_related->the_post();

    //         // Get the first letter of the title in order to create a custom class
    //         $string = get_the_title();
    //         $firstChar = mb_substr($string, 0, 1, "UTF-8");

    //         echo '<div class="featured__item">';
    //         echo '<div class="featured__item-wrapper bg-letter" style="background-image: url(' . get_stylesheet_directory_uri() . '/images/alphabet/' . $firstChar . '.svg);">';
    //         echo '<div class="featured__item-content">';
    //         echo '<h3 class="featured__item-heading">';
    //         echo '<a class="featured__item-link" href="' . get_the_permalink() . '" rel="bookmark" title="Read ' . esc_html(get_the_title()) . '">' . get_the_title() . '</a>';
    //         echo '</h3>';
    //         echo '<div class="featured__item-label" aria-hidden="true"><span>Read</span></div>';
    //         echo '</div>';
    //         echo '</div>';
    //         echo '</div>';
    //     }
    // }

    echo '</div>'; // .featured

    // Reset 
    wp_reset_postdata();
    ?>

</div>