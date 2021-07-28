<?php

/**
 * Block template file: /www/kinsta/public/janeparriscom/wp-content/themes/janeparris/blocks/testimonial-image.php
 *
 * Testimonial Image Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'testimonial-image-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-testimonial-image';
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

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?> testimonial-image-wrapper">
    <?php $layout = 'testimonial-image--' . get_field('layout'); ?>
    <div class="testimonial-image <?php echo $layout; ?>">
        <div class="testimonial-image__image">
            <div class="testimonial-image__image-wrapper">
                <?php if (get_field('image')) : ?>
                    <img src="<?php the_field('image'); ?>" />
                <?php endif ?>
            </div>
        </div>
        <div class="testimonial-image__text">
            <p class="testimonial-image__text--main has-drop-cap">
                <?php the_field('text'); ?>
            </p>
            <p class="testimonial-image__text--meta">
                <span class="testimonial-image__text--author">
                    <?php the_field('author'); ?>
                </span>
                <?php if (get_field('role')) : ?>
                    <span class="testimonial-image__text--role">
                        <br />
                        <?php the_field('role'); ?>
                    </span>
                <?php endif ?>
            </p>
        </div>
    </div>
</div>