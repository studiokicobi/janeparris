<?php

/**
 * Block template file: /www/kinsta/public/janeparriscom/wp-content/themes/janeparris/blocks/sortable-testimonials.php
 *
 * Sortable Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'sortable-testimonial-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-sortable-testimonial';
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}
?>

<style type="text/css">
    <?php echo '#' . $id; ?> {
        /* Add styles that use ACF values here */
    }
</style>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">

    <?php
    // data-category key
    // students : What students say
    // parents : What parents say
    // writing : Writing classes
    // college : College essays
    ?>


    <!-- Filterizr.js controls -->
    <ul class="filter-controls">
        <li data-filter="students">What students say</li>
        <li data-filter="parents">What parents say</li>
        <li data-filter="writing">Writing classes</li>
        <li data-filter="college">College essays</li>
        <li data-filter="all"> All items </li>
    </ul>


    <?php if (have_rows('testimonials')) : ?>
        <div class="filter-container sortable-testimonial">
            <?php while (have_rows('testimonials')) : the_row(); ?>
                <div class="filtr-item" data-category="<?php the_sub_field('testimonial_category'); ?>" data-sort="value">
                    <div class="sortable-testimonial__content">
                        <p>
                            <?php the_sub_field('testimonial_text'); ?>
                        </p>
                    </div>
                    <div class="sortable-testimonial__meta">
                        <div class="sortable-testimonial__dropcap"></div>
                        <div class="sortable-testimonial__meta--content">
                            <p>
                                <?php the_sub_field('testimonial_author');
                                if (get_sub_field('student_age')) {
                                    echo ' , ' . get_sub_field('student_age');
                                }
                                if (get_sub_field('role')) {
                                    echo '<br />' . get_sub_field('role');
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>