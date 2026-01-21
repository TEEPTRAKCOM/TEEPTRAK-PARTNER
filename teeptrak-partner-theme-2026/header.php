<?php
/**
 * Header Template
 *
 * @package TeepTrak_Partner_Theme_2026
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_portal = teeptrak_is_portal_page();
$partner = $is_portal ? teeptrak_get_current_partner() : null;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ($is_portal && $partner) : ?>
    <!-- Portal Layout -->
    <div class="tt-portal">
        <!-- Sidebar -->
        <aside class="tt-portal-sidebar" id="sidebar">
            <div class="tt-sidebar-logo">
                <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-sidebar-logo-text">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        TeepTrak
                    <?php endif; ?>
                </a>
                <button class="tt-sidebar-close" id="sidebar-close" aria-label="<?php esc_attr_e('Close menu', 'teeptrak-partner'); ?>">
                    <?php echo teeptrak_icon('x', 20); ?>
                </button>
            </div>

            <nav class="tt-sidebar-nav">
                <!-- Main Section -->
                <div class="tt-nav-section">
                    <div class="tt-nav-section-label"><?php esc_html_e('Main', 'teeptrak-partner'); ?></div>
                    <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-nav-item <?php echo is_page('dashboard') ? 'is-active' : ''; ?>">
                        <?php echo teeptrak_icon('grid', 20); ?>
                        <span><?php esc_html_e('Dashboard', 'teeptrak-partner'); ?></span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-nav-item <?php echo is_page('deals') ? 'is-active' : ''; ?>">
                        <?php echo teeptrak_icon('file-plus', 20); ?>
                        <span><?php esc_html_e('Deal Registration', 'teeptrak-partner'); ?></span>
                    </a>
                </div>

                <!-- Learning Section -->
                <div class="tt-nav-section">
                    <div class="tt-nav-section-label"><?php esc_html_e('Learning', 'teeptrak-partner'); ?></div>
                    <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-nav-item <?php echo is_page('training') ? 'is-active' : ''; ?>">
                        <?php echo teeptrak_icon('graduation-cap', 20); ?>
                        <span><?php esc_html_e('Training', 'teeptrak-partner'); ?></span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/resources/')); ?>" class="tt-nav-item <?php echo is_page('resources') ? 'is-active' : ''; ?>">
                        <?php echo teeptrak_icon('folder', 20); ?>
                        <span><?php esc_html_e('Resources', 'teeptrak-partner'); ?></span>
                    </a>
                </div>

                <!-- Finance Section -->
                <div class="tt-nav-section">
                    <div class="tt-nav-section-label"><?php esc_html_e('Finance', 'teeptrak-partner'); ?></div>
                    <a href="<?php echo esc_url(home_url('/commissions/')); ?>" class="tt-nav-item <?php echo is_page('commissions') ? 'is-active' : ''; ?>">
                        <?php echo teeptrak_icon('dollar-sign', 20); ?>
                        <span><?php esc_html_e('Commissions', 'teeptrak-partner'); ?></span>
                    </a>
                </div>
            </nav>

            <div class="tt-sidebar-footer">
                <div class="tt-sidebar-user">
                    <div class="tt-sidebar-avatar">
                        <?php echo esc_html(teeptrak_get_initials($partner['name'])); ?>
                    </div>
                    <div class="tt-sidebar-user-info">
                        <div class="tt-sidebar-user-name"><?php echo esc_html($partner['first_name']); ?></div>
                        <div class="tt-sidebar-user-tier">
                            <?php
                            $tier_config = teeptrak_get_tier_config($partner['tier']);
                            echo esc_html($tier_config['name'] . ' ' . __('Partner', 'teeptrak-partner'));
                            ?>
                        </div>
                    </div>
                </div>
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="tt-nav-item">
                    <?php echo teeptrak_icon('log-out', 20); ?>
                    <span><?php esc_html_e('Logout', 'teeptrak-partner'); ?></span>
                </a>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div class="tt-sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Main Content Area -->
        <main class="tt-portal-main">
            <!-- Portal Header -->
            <header class="tt-portal-header">
                <button class="tt-btn tt-btn-ghost tt-btn-icon lg:tt-hidden" id="sidebar-toggle" aria-label="<?php esc_attr_e('Open menu', 'teeptrak-partner'); ?>">
                    <?php echo teeptrak_icon('menu', 24); ?>
                </button>
                <div class="tt-flex-1"></div>
                <div class="tt-flex tt-items-center tt-gap-4">
                    <?php teeptrak_tier_badge($partner['tier'], 'sm'); ?>
                    <span class="tt-text-sm tt-text-gray-600 tt-hidden sm:tt-block">
                        <?php
                        printf(
                            /* translators: %s: user's first name */
                            esc_html__('Welcome, %s', 'teeptrak-partner'),
                            esc_html($partner['first_name'])
                        );
                        ?>
                    </span>
                </div>
            </header>

            <!-- Page Content -->
            <div class="tt-portal-content">

<?php else : ?>
    <!-- Landing Page Layout -->
    <header class="tt-landing-nav">
        <div class="tt-container tt-landing-nav-inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tt-landing-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    TeepTrak
                <?php endif; ?>
            </a>

            <nav class="tt-landing-menu">
                <a href="#benefits"><?php esc_html_e('Benefits', 'teeptrak-partner'); ?></a>
                <a href="#tiers"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></a>
                <a href="#process"><?php esc_html_e('How It Works', 'teeptrak-partner'); ?></a>
            </nav>

            <div class="tt-landing-actions">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-btn tt-btn-primary">
                        <?php esc_html_e('Go to Dashboard', 'teeptrak-partner'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>" class="tt-btn tt-btn-ghost tt-hidden sm:tt-inline-flex">
                        <?php esc_html_e('Login', 'teeptrak-partner'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn tt-btn-primary">
                        <?php esc_html_e('Become a Partner', 'teeptrak-partner'); ?>
                    </a>
                <?php endif; ?>

                <button class="tt-mobile-toggle" id="mobile-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'teeptrak-partner'); ?>">
                    <?php echo teeptrak_icon('menu', 24); ?>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="tt-mobile-menu" id="mobile-menu">
        <a href="#benefits"><?php esc_html_e('Benefits', 'teeptrak-partner'); ?></a>
        <a href="#tiers"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></a>
        <a href="#process"><?php esc_html_e('How It Works', 'teeptrak-partner'); ?></a>
        <?php if (!is_user_logged_in()) : ?>
            <a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>"><?php esc_html_e('Login', 'teeptrak-partner'); ?></a>
        <?php endif; ?>
    </div>

    <main>
<?php endif; ?>
