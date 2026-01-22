<?php
/**
 * Portal Header Template
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$partner_tier = get_user_meta($current_user->ID, 'partner_tier', true) ?: 'registered';
$brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
$logo_url = get_theme_mod('teeptrak_logo');
?>

<div class="tt-portal-layout">
    <!-- Sidebar -->
    <aside class="tt-sidebar">
        <div class="tt-sidebar-header">
            <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-logo">
                <?php if ($logo_url) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($brand_name); ?>">
                <?php else : ?>
                    <span class="tt-logo-text"><?php echo esc_html($brand_name); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <nav class="tt-nav">
            <ul class="tt-nav-list">
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-nav-link <?php echo is_page('dashboard') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('home', 20); ?>
                        <span><?php esc_html_e('Dashboard', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-nav-link <?php echo is_page('deals') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('briefcase', 20); ?>
                        <span><?php esc_html_e('Deals', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/commissions/')); ?>" class="tt-nav-link <?php echo is_page('commissions') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('dollar-sign', 20); ?>
                        <span><?php esc_html_e('Commissions', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-nav-link <?php echo is_page('training') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('book-open', 20); ?>
                        <span><?php esc_html_e('Training', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/resources/')); ?>" class="tt-nav-link <?php echo is_page('resources') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('folder', 20); ?>
                        <span><?php esc_html_e('Resources', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/support/')); ?>" class="tt-nav-link <?php echo is_page('support') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('help-circle', 20); ?>
                        <span><?php esc_html_e('Support', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
            </ul>

            <div class="tt-nav-divider"></div>

            <ul class="tt-nav-list tt-nav-secondary">
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="tt-nav-link <?php echo is_page('profile') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('user', 20); ?>
                        <span><?php esc_html_e('Profile', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
                <li class="tt-nav-item">
                    <a href="<?php echo esc_url(home_url('/settings/')); ?>" class="tt-nav-link <?php echo is_page('settings') ? 'tt-active' : ''; ?>">
                        <?php echo teeptrak_icon('settings', 20); ?>
                        <span><?php esc_html_e('Settings', 'teeptrak-partner'); ?></span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="tt-sidebar-footer">
            <div class="tt-partner-card">
                <?php echo teeptrak_tier_badge($partner_tier); ?>
                <span class="tt-partner-name"><?php echo esc_html($current_user->display_name); ?></span>
            </div>
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="tt-sidebar-overlay"></div>

    <!-- Main Content -->
    <div class="tt-main">
        <!-- Top Header -->
        <header class="tt-header">
            <div class="tt-header-left">
                <button class="tt-mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'teeptrak-partner'); ?>">
                    <?php echo teeptrak_icon('menu', 24); ?>
                </button>

                <div class="tt-search">
                    <div class="tt-search-wrapper">
                        <?php echo teeptrak_icon('search', 18); ?>
                        <input type="text" class="tt-search-input" placeholder="<?php esc_attr_e('Search deals, resources...', 'teeptrak-partner'); ?>">
                        <div class="tt-search-results"></div>
                    </div>
                </div>
            </div>

            <div class="tt-header-right">
                <!-- Language Switcher -->
                <?php echo teeptrak_language_switcher(); ?>

                <!-- Notifications -->
                <?php echo teeptrak_notification_bell(); ?>

                <!-- User Menu -->
                <div class="tt-dropdown tt-user-menu">
                    <button class="tt-dropdown-toggle tt-user-toggle">
                        <div class="tt-avatar tt-avatar-sm">
                            <?php echo get_avatar($current_user->ID, 32); ?>
                        </div>
                        <span class="tt-user-name"><?php echo esc_html($current_user->display_name); ?></span>
                        <?php echo teeptrak_icon('chevron-down', 16); ?>
                    </button>
                    <div class="tt-dropdown-menu">
                        <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="tt-dropdown-item">
                            <?php echo teeptrak_icon('user', 16); ?>
                            <?php esc_html_e('My Profile', 'teeptrak-partner'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/settings/')); ?>" class="tt-dropdown-item">
                            <?php echo teeptrak_icon('settings', 16); ?>
                            <?php esc_html_e('Settings', 'teeptrak-partner'); ?>
                        </a>
                        <div class="tt-dropdown-divider"></div>
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="tt-dropdown-item tt-text-danger">
                            <?php echo teeptrak_icon('log-out', 16); ?>
                            <?php esc_html_e('Sign Out', 'teeptrak-partner'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="tt-content">
