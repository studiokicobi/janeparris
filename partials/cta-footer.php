<?php if (have_rows('footer_call_to_action', 'option')) : ?>
    <div class="cta-footer container">
        <?php while (have_rows('footer_call_to_action', 'option')) : the_row(); ?>
            <div class="cta-footer__image">
                <img <?php awesome_acf_responsive_image(get_sub_field('background_image'), 'medium', '640px'); ?> alt="" />
            </div>

            <div class="cta-footer__content">
                <h2 class="cta-footer__heading"><?php the_sub_field('heading'); ?></h2>
                <p class="cta-footer__text"><?php the_sub_field('text'); ?></p>

                <?php $button_link = get_sub_field('button_link'); ?>
                <?php if ($button_link) : ?>
                    <a class="cta-footer__link" href="<?php echo esc_url($button_link['url']); ?>" target="<?php echo esc_attr($button_link['target']); ?>"><?php echo esc_html($button_link['title']); ?></a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>