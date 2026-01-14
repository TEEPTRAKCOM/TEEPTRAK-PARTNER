<?php
/**
 * Theme Footer
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_portal = teeptrak_is_portal_page();
?>

<?php if ($is_portal && is_user_logged_in()) : ?>
            </main>
            
            <!-- Portal Footer -->
            <footer class="tt-portal-footer">
                <div class="tt-flex tt-flex-col md:tt-flex-row tt-items-center tt-justify-between tt-gap-4">
                    <p class="tt-text-sm tt-text-gray-500">
                        © <?php echo date('Y'); ?> TeepTrak. <?php _e('All rights reserved.', 'teeptrak-partner'); ?>
                    </p>
                    <div class="tt-flex tt-items-center tt-gap-6">
                        <a href="#" class="tt-text-sm tt-text-gray-500 hover:tt-text-red"><?php _e('Support', 'teeptrak-partner'); ?></a>
                        <a href="#" class="tt-text-sm tt-text-gray-500 hover:tt-text-red"><?php _e('Documentation', 'teeptrak-partner'); ?></a>
                        <a href="#" class="tt-text-sm tt-text-gray-500 hover:tt-text-red"><?php _e('Contact Us', 'teeptrak-partner'); ?></a>
                    </div>
                </div>
            </footer>
        </div><!-- .tt-portal-main -->
    </div><!-- .tt-portal -->

<?php else : ?>
    </main>
    
    <!-- Landing Footer -->
    <footer class="tt-footer">
        <div class="tt-container">
            <div class="tt-footer-grid">
                <!-- Company Info -->
                <div class="tt-footer-brand">
                    <a href="<?php echo home_url('/'); ?>" class="tt-logo tt-flex tt-items-center tt-gap-2 tt-mb-4">
                        <span class="tt-logo-icon">
                            <svg width="40" height="40" viewBox="0 0 32 32" fill="none">
                                <rect width="32" height="32" rx="6" fill="#EB352B"/>
                                <path d="M10 12H22V14H10V12ZM10 16H22V18H10V16ZM10 20H18V22H10V20Z" fill="white"/>
                            </svg>
                        </span>
                        <span class="tt-logo-text tt-text-xl">
                            <span style="color: white;">teep</span><span style="color: #EB352B;">trak</span>
                        </span>
                    </a>
                    <p class="tt-text-gray-400 tt-mb-6">
                        <?php _e('Plug-and-play OEE solutions for smart manufacturing', 'teeptrak-partner'); ?>
                    </p>
                    <!-- Social Links -->
                    <div class="tt-social-links tt-flex tt-gap-4">
                        <a href="https://linkedin.com/company/teeptrak" target="_blank" class="tt-social-link" aria-label="LinkedIn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                        <a href="https://youtube.com/@teeptrak" target="_blank" class="tt-social-link" aria-label="YouTube">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/teeptrak" target="_blank" class="tt-social-link" aria-label="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Company Links -->
                <div>
                    <h4 class="tt-font-semibold tt-mb-4"><?php _e('Company', 'teeptrak-partner'); ?></h4>
                    <ul class="tt-footer-links">
                        <li><a href="https://teeptrak.com/about"><?php _e('About TeepTrak', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/careers"><?php _e('Careers', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/press"><?php _e('Press', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/contact"><?php _e('Contact', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>
                
                <!-- Resources Links -->
                <div>
                    <h4 class="tt-font-semibold tt-mb-4"><?php _e('Resources', 'teeptrak-partner'); ?></h4>
                    <ul class="tt-footer-links">
                        <li><a href="https://docs.teeptrak.com"><?php _e('Documentation', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/case-studies"><?php _e('Case Studies', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/blog"><?php _e('Blog', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/webinars"><?php _e('Webinars', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>
                
                <!-- Partners Links -->
                <div>
                    <h4 class="tt-font-semibold tt-mb-4"><?php _e('Partners', 'teeptrak-partner'); ?></h4>
                    <ul class="tt-footer-links">
                        <li><a href="<?php echo home_url('/dashboard/'); ?>"><?php _e('Partner Portal', 'teeptrak-partner'); ?></a></li>
                        <li><a href="<?php echo home_url('/become-partner/'); ?>"><?php _e('Become a Partner', 'teeptrak-partner'); ?></a></li>
                        <li><a href="https://teeptrak.com/find-partner"><?php _e('Find a Partner', 'teeptrak-partner'); ?></a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="tt-footer-bottom">
                <p class="tt-text-sm tt-text-gray-400">
                    © <?php echo date('Y'); ?> TeepTrak SAS. <?php _e('All rights reserved.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-flex tt-gap-6">
                    <a href="https://teeptrak.com/privacy" class="tt-text-sm tt-text-gray-400 hover:tt-text-white">
                        <?php _e('Privacy Policy', 'teeptrak-partner'); ?>
                    </a>
                    <a href="https://teeptrak.com/terms" class="tt-text-sm tt-text-gray-400 hover:tt-text-white">
                        <?php _e('Terms of Service', 'teeptrak-partner'); ?>
                    </a>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
