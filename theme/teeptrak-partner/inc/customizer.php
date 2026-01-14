<?php
/**
 * Theme Customizer
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register customizer settings
 */
function teeptrak_customize_register($wp_customize) {
    // TeepTrak Settings Panel
    $wp_customize->add_panel('teeptrak_settings', array(
        'title' => __('TeepTrak Settings', 'teeptrak-partner'),
        'priority' => 30,
    ));
    
    // Brand Section
    $wp_customize->add_section('teeptrak_brand', array(
        'title' => __('Brand Settings', 'teeptrak-partner'),
        'panel' => 'teeptrak_settings',
        'priority' => 10,
    ));
    
    // Primary Color
    $wp_customize->add_setting('teeptrak_primary_color', array(
        'default' => '#EB352B',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'teeptrak_primary_color', array(
        'label' => __('Primary Color', 'teeptrak-partner'),
        'section' => 'teeptrak_brand',
    )));
    
    // Partner Portal Section
    $wp_customize->add_section('teeptrak_portal', array(
        'title' => __('Partner Portal', 'teeptrak-partner'),
        'panel' => 'teeptrak_settings',
        'priority' => 20,
    ));
    
    // Academy URL
    $wp_customize->add_setting('teeptrak_academy_url', array(
        'default' => 'https://academy.teeptrak.net',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('teeptrak_academy_url', array(
        'label' => __('Academy Portal URL', 'teeptrak-partner'),
        'section' => 'teeptrak_portal',
        'type' => 'url',
    ));
    
    // Support Email
    $wp_customize->add_setting('teeptrak_support_email', array(
        'default' => 'partners@teeptrak.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('teeptrak_support_email', array(
        'label' => __('Partner Support Email', 'teeptrak-partner'),
        'section' => 'teeptrak_portal',
        'type' => 'email',
    ));
    
    // Calendly URL for scheduling
    $wp_customize->add_setting('teeptrak_calendly_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('teeptrak_calendly_url', array(
        'label' => __('Calendly URL', 'teeptrak-partner'),
        'description' => __('URL for scheduling calls with PSM', 'teeptrak-partner'),
        'section' => 'teeptrak_portal',
        'type' => 'url',
    ));
    
    // Landing Page Section
    $wp_customize->add_section('teeptrak_landing', array(
        'title' => __('Landing Page', 'teeptrak-partner'),
        'panel' => 'teeptrak_settings',
        'priority' => 30,
    ));
    
    // Hero Title
    $wp_customize->add_setting('teeptrak_hero_title', array(
        'default' => __('Grow Your Business with', 'teeptrak-partner'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('teeptrak_hero_title', array(
        'label' => __('Hero Title', 'teeptrak-partner'),
        'section' => 'teeptrak_landing',
        'type' => 'text',
    ));
    
    // Hero Subtitle
    $wp_customize->add_setting('teeptrak_hero_subtitle', array(
        'default' => __('Join our global partner network and help manufacturers achieve 5-30% productivity gains with plug-and-play OEE solutions', 'teeptrak-partner'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('teeptrak_hero_subtitle', array(
        'label' => __('Hero Subtitle', 'teeptrak-partner'),
        'section' => 'teeptrak_landing',
        'type' => 'textarea',
    ));
    
    // Footer Section
    $wp_customize->add_section('teeptrak_footer', array(
        'title' => __('Footer', 'teeptrak-partner'),
        'panel' => 'teeptrak_settings',
        'priority' => 40,
    ));
    
    // LinkedIn URL
    $wp_customize->add_setting('teeptrak_linkedin_url', array(
        'default' => 'https://linkedin.com/company/teeptrak',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('teeptrak_linkedin_url', array(
        'label' => __('LinkedIn URL', 'teeptrak-partner'),
        'section' => 'teeptrak_footer',
        'type' => 'url',
    ));
    
    // YouTube URL
    $wp_customize->add_setting('teeptrak_youtube_url', array(
        'default' => 'https://youtube.com/@teeptrak',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('teeptrak_youtube_url', array(
        'label' => __('YouTube URL', 'teeptrak-partner'),
        'section' => 'teeptrak_footer',
        'type' => 'url',
    ));
    
    // Twitter URL
    $wp_customize->add_setting('teeptrak_twitter_url', array(
        'default' => 'https://twitter.com/teeptrak',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('teeptrak_twitter_url', array(
        'label' => __('Twitter URL', 'teeptrak-partner'),
        'section' => 'teeptrak_footer',
        'type' => 'url',
    ));
}
add_action('customize_register', 'teeptrak_customize_register');

/**
 * Output custom CSS from customizer settings
 */
function teeptrak_customizer_css() {
    $primary_color = get_theme_mod('teeptrak_primary_color', '#EB352B');
    
    if ($primary_color !== '#EB352B') {
        ?>
        <style type="text/css">
            :root {
                --tt-red: <?php echo esc_attr($primary_color); ?>;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'teeptrak_customizer_css');

/**
 * Customizer preview JS
 */
function teeptrak_customize_preview_js() {
    wp_enqueue_script(
        'teeptrak-customizer',
        TEEPTRAK_ASSETS . '/js/customizer.js',
        array('customize-preview'),
        TEEPTRAK_VERSION,
        true
    );
}
add_action('customize_preview_init', 'teeptrak_customize_preview_js');
