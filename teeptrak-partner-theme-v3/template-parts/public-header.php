<?php
/**
 * Public Header Template (for landing page)
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

$brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
$logo_url = get_theme_mod('teeptrak_logo');
?>

<div class="tt-public-layout">
    <header class="tt-public-header">
        <div class="tt-container">
            <div class="tt-public-header-inner">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="tt-logo">
                    <?php if ($logo_url) : ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($brand_name); ?>">
                    <?php else : ?>
                        <span class="tt-logo-text"><?php echo esc_html($brand_name); ?></span>
                    <?php endif; ?>
                    <span class="tt-logo-suffix"><?php esc_html_e('Partner Portal', 'teeptrak-partner'); ?></span>
                </a>

                <!-- Navigation -->
                <nav class="tt-public-nav">
                    <a href="#benefits" class="tt-nav-link"><?php esc_html_e('Benefits', 'teeptrak-partner'); ?></a>
                    <a href="#tiers" class="tt-nav-link"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></a>
                    <a href="#resources" class="tt-nav-link"><?php esc_html_e('Resources', 'teeptrak-partner'); ?></a>
                    <a href="#faq" class="tt-nav-link"><?php esc_html_e('FAQ', 'teeptrak-partner'); ?></a>
                </nav>

                <!-- Actions -->
                <div class="tt-public-header-actions">
                    <?php echo teeptrak_language_switcher(); ?>

                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" class="tt-btn tt-btn-primary">
                            <?php esc_html_e('Go to Dashboard', 'teeptrak-partner'); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="tt-btn tt-btn-secondary">
                            <?php esc_html_e('Sign In', 'teeptrak-partner'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/register/')); ?>" class="tt-btn tt-btn-primary">
                            <?php esc_html_e('Become a Partner', 'teeptrak-partner'); ?>
                        </a>
                    <?php endif; ?>

                    <button class="tt-mobile-menu-toggle" aria-label="<?php esc_attr_e('Menu', 'teeptrak-partner'); ?>">
                        <?php echo teeptrak_icon('menu', 24); ?>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="tt-public-content">
