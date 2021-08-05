<?php

/**
 * Block template file: /www/kinsta/public/janeparriscom/wp-content/themes/janeparris/blocks/box-testimonial.php
 *
 * Box Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
?>

<div class="box-testimonial__text-wrapper">
    <div class="box-testimonial__text">
        <p class="box-testimonial__text--main has-drop-cap">
            <?php the_field('testimonial_text'); ?>
        </p>
        <p class="box-testimonial__text--meta">
            <span class="box-testimonial__text--author">
                <?php the_field('testimonial_author'); ?>
            </span>
            <?php if (get_field('role')) : ?>
                <span class="box-testimonial__text--role">
                    <br />
                    <?php the_field('role'); ?>
                </span>
            <?php endif ?>
        </p>
    </div>
</div>