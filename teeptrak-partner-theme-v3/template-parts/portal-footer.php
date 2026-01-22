<?php
/**
 * Portal Footer Template
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

$brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
?>
        </main><!-- .tt-content -->

        <!-- Portal Footer -->
        <footer class="tt-footer">
            <div class="tt-footer-content">
                <p class="tt-footer-copy">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($brand_name); ?>.
                    <?php esc_html_e('All rights reserved.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-footer-links">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms of Service', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/support/')); ?>"><?php esc_html_e('Support', 'teeptrak-partner'); ?></a>
                </div>
            </div>
        </footer>

    </div><!-- .tt-main -->
</div><!-- .tt-portal-layout -->
