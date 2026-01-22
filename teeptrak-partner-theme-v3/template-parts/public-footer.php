<?php
/**
 * Public Footer Template (for landing page)
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

$brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
$logo_url = get_theme_mod('teeptrak_logo');
?>
    </main><!-- .tt-public-content -->

    <footer class="tt-public-footer">
        <div class="tt-container">
            <div class="tt-footer-grid">
                <!-- Brand Column -->
                <div class="tt-footer-brand">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="tt-logo">
                        <?php if ($logo_url) : ?>
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($brand_name); ?>">
                        <?php else : ?>
                            <span class="tt-logo-text"><?php echo esc_html($brand_name); ?></span>
                        <?php endif; ?>
                    </a>
                    <p class="tt-footer-tagline">
                        <?php esc_html_e('Industrial IoT solutions powering 360+ factories worldwide.', 'teeptrak-partner'); ?>
                    </p>
                    <div class="tt-footer-social">
                        <a href="#" class="tt-social-link" aria-label="LinkedIn">
                            <?php echo teeptrak_icon('linkedin', 20); ?>
                        </a>
                        <a href="#" class="tt-social-link" aria-label="Twitter">
                            <?php echo teeptrak_icon('twitter', 20); ?>
                        </a>
                        <a href="#" class="tt-social-link" aria-label="YouTube">
                            <?php echo teeptrak_icon('youtube', 20); ?>
                        </a>
                    </div>
                </div>

                <!-- Partner Program -->
                <div class="tt-footer-column">
                    <h4><?php esc_html_e('Partner Program', 'teeptrak-partner'); ?></h4>
                    <ul>
                        <li><a href="#benefits"><?php esc_html_e('Benefits', 'teeptrak-partner'); ?></a></li>
                        <li><a href="#tiers"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/register/')); ?>"><?php esc_html_e('Become a Partner', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/login/')); ?>"><?php esc_html_e('Partner Login', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="tt-footer-column">
                    <h4><?php esc_html_e('Resources', 'teeptrak-partner'); ?></h4>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/training/')); ?>"><?php esc_html_e('Training Center', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/resources/')); ?>"><?php esc_html_e('Sales Materials', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>"><?php esc_html_e('FAQ', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/support/')); ?>"><?php esc_html_e('Support', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="tt-footer-column">
                    <h4><?php esc_html_e('Company', 'teeptrak-partner'); ?></h4>
                    <ul>
                        <li><a href="https://teeptrak.com/about" target="_blank"><?php esc_html_e('About Us', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/products" target="_blank"><?php esc_html_e('Products', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/case-studies" target="_blank"><?php esc_html_e('Case Studies', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/contact" target="_blank"><?php esc_html_e('Contact', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="tt-footer-column">
                    <h4><?php esc_html_e('Contact', 'teeptrak-partner'); ?></h4>
                    <address class="tt-footer-contact">
                        <p><?php echo teeptrak_icon('mail', 16); ?> partners@teeptrak.com</p>
                        <p><?php echo teeptrak_icon('phone', 16); ?> +33 1 23 45 67 89</p>
                        <p><?php echo teeptrak_icon('map-pin', 16); ?> Paris, France</p>
                    </address>
                </div>
            </div>

            <div class="tt-footer-bottom">
                <p class="tt-footer-copy">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($brand_name); ?>.
                    <?php esc_html_e('All rights reserved.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-footer-legal">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms of Service', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/cookies/')); ?>"><?php esc_html_e('Cookie Policy', 'teeptrak-partner'); ?></a>
                </div>
            </div>
        </div>
    </footer>
</div><!-- .tt-public-layout -->
