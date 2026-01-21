<?php
/**
 * Footer Template
 *
 * @package TeepTrak_Partner_Theme_2026
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_portal = teeptrak_is_portal_page();
?>

<?php if ($is_portal) : ?>
            </div><!-- .tt-portal-content -->

            <footer class="tt-portal-footer">
                <p class="tt-text-sm tt-text-gray-500 tt-text-center tt-m-0">
                    &copy; <?php echo esc_html(date('Y')); ?> TeepTrak. <?php esc_html_e('All rights reserved.', 'teeptrak-partner'); ?>
                </p>
            </footer>
        </main><!-- .tt-portal-main -->
    </div><!-- .tt-portal -->

<?php else : ?>
    </main>

    <!-- Footer -->
    <footer class="tt-footer">
        <div class="tt-container">
            <div class="tt-footer-grid">
                <!-- Brand Column -->
                <div class="tt-footer-brand">
                    <div class="tt-footer-brand-name">TeepTrak</div>
                    <p><?php esc_html_e('Industrial IoT solutions for manufacturing performance.', 'teeptrak-partner'); ?></p>
                    <p class="tt-text-xs"><?php esc_html_e('Offices: Paris | Shenzhen | Chicago', 'teeptrak-partner'); ?></p>
                </div>

                <!-- Partner Program Column -->
                <div>
                    <h4 class="tt-footer-title"><?php esc_html_e('Partner Program', 'teeptrak-partner'); ?></h4>
                    <ul class="tt-footer-links">
                        <li><a href="#benefits"><?php esc_html_e('Benefits', 'teeptrak-partner'); ?></a></li>
                        <li><a href="#tiers"><?php esc_html_e('Tiers', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/resources/')); ?>"><?php esc_html_e('Resources', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/training/')); ?>"><?php esc_html_e('Training', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>

                <!-- Resources Column -->
                <div>
                    <h4 class="tt-footer-title"><?php esc_html_e('Resources', 'teeptrak-partner'); ?></h4>
                    <ul class="tt-footer-links">
                        <li><a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>"><?php esc_html_e('Partner Portal Login', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/resources/?cat=case_study')); ?>"><?php esc_html_e('Case Studies', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/resources/?cat=sales')); ?>"><?php esc_html_e('Product Brochures', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div>
                    <h4 class="tt-footer-title"><?php esc_html_e('Contact', 'teeptrak-partner'); ?></h4>
                    <div class="tt-footer-contact">
                        <p>partners@teeptrak.com</p>
                        <p>+33 X XX XX XX XX</p>
                    </div>
                    <div class="tt-footer-social">
                        <a href="https://linkedin.com/company/teeptrak" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <?php echo teeptrak_icon('linkedin', 20); ?>
                        </a>
                        <a href="https://youtube.com/@teeptrak" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <?php echo teeptrak_icon('youtube', 20); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="tt-footer-bottom">
                <p class="tt-footer-copyright">
                    &copy; <?php echo esc_html(date('Y')); ?> TeepTrak. <?php esc_html_e('All rights reserved.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-footer-legal">
                    <a href="<?php echo esc_url(home_url('/privacy/')); ?>"><?php esc_html_e('Privacy Policy', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms of Service', 'teeptrak-partner'); ?></a>
                    <a href="<?php echo esc_url(home_url('/partner-agreement/')); ?>"><?php esc_html_e('Partner Agreement', 'teeptrak-partner'); ?></a>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

<!-- Toast Container -->
<div class="tt-toast-container" id="toast-container"></div>

<?php wp_footer(); ?>
</body>
</html>
