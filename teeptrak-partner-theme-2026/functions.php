<?php
/**
 * TeepTrak Partner Theme 2026 Functions
 *
 * @package TeepTrak_Partner_Theme_2026
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Constants
define('TEEPTRAK_VERSION', '1.0.0');
define('TEEPTRAK_DIR', get_template_directory());
define('TEEPTRAK_URI', get_template_directory_uri());
define('TEEPTRAK_ASSETS', TEEPTRAK_URI . '/assets');

/**
 * Theme Setup
 */
function teeptrak_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Register nav menus
    register_nav_menus(array(
        'primary'     => __('Primary Navigation', 'teeptrak-partner'),
        'portal'      => __('Portal Navigation', 'teeptrak-partner'),
        'footer'      => __('Footer Navigation', 'teeptrak-partner'),
    ));

    // Load text domain
    load_theme_textdomain('teeptrak-partner', TEEPTRAK_DIR . '/languages');

    // Add image sizes
    add_image_size('teeptrak-card', 400, 250, true);
    add_image_size('teeptrak-hero', 1200, 600, true);
    add_image_size('teeptrak-thumbnail', 100, 100, true);
}
add_action('after_setup_theme', 'teeptrak_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function teeptrak_enqueue_assets() {
    // Google Fonts - Inter
    wp_enqueue_style(
        'teeptrak-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'teeptrak-style',
        get_stylesheet_uri(),
        array('teeptrak-google-fonts'),
        TEEPTRAK_VERSION
    );

    // Portal specific styles
    if (teeptrak_is_portal_page()) {
        wp_enqueue_style(
            'teeptrak-portal',
            TEEPTRAK_ASSETS . '/css/portal.css',
            array('teeptrak-style'),
            TEEPTRAK_VERSION
        );
    }

    // Main JavaScript
    wp_enqueue_script(
        'teeptrak-main',
        TEEPTRAK_ASSETS . '/js/main.js',
        array(),
        TEEPTRAK_VERSION,
        true
    );

    // Localize script
    wp_localize_script('teeptrak-main', 'teeptrakData', array(
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'restUrl'     => rest_url('teeptrak/v1/'),
        'nonce'       => wp_create_nonce('teeptrak_nonce'),
        'restNonce'   => wp_create_nonce('wp_rest'),
        'isLoggedIn'  => is_user_logged_in(),
        'currentUser' => teeptrak_get_current_partner(),
        'i18n'        => array(
            'loading'     => __('Loading...', 'teeptrak-partner'),
            'error'       => __('An error occurred', 'teeptrak-partner'),
            'success'     => __('Success!', 'teeptrak-partner'),
            'confirm'     => __('Are you sure?', 'teeptrak-partner'),
            'dealSuccess' => __('Deal registered successfully! Your 90-day protection starts now.', 'teeptrak-partner'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'teeptrak_enqueue_assets');

/**
 * Check if current page is a portal page (requires login)
 */
function teeptrak_is_portal_page() {
    $portal_templates = array(
        'page-dashboard.php',
        'page-deals.php',
        'page-training.php',
        'page-resources.php',
        'page-commissions.php',
    );

    $portal_slugs = array(
        'dashboard',
        'deals',
        'training',
        'resources',
        'commissions',
    );

    $template = get_page_template_slug();
    $slug = get_post_field('post_name', get_the_ID());

    return in_array($template, $portal_templates) || in_array($slug, $portal_slugs);
}

/**
 * Get current partner data
 */
function teeptrak_get_current_partner() {
    if (!is_user_logged_in()) {
        return null;
    }

    $user_id = get_current_user_id();
    $user = wp_get_current_user();

    return array(
        'id'                  => $user_id,
        'name'                => $user->display_name,
        'first_name'          => $user->first_name ?: $user->display_name,
        'email'               => $user->user_email,
        'tier'                => get_user_meta($user_id, 'teeptrak_partner_tier', true) ?: 'bronze',
        'partner_score'       => (int) get_user_meta($user_id, 'teeptrak_partner_score', true) ?: 0,
        'commission_rate'     => (int) get_user_meta($user_id, 'teeptrak_commission_rate', true) ?: 15,
        'onboarding_step'     => (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true) ?: 1,
        'available_balance'   => (float) get_user_meta($user_id, 'teeptrak_available_balance', true) ?: 0,
        'pending_commissions' => (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true) ?: 0,
        'total_paid'          => (float) get_user_meta($user_id, 'teeptrak_total_paid', true) ?: 0,
        'join_date'           => $user->user_registered,
    );
}

/**
 * Get partner deals
 */
function teeptrak_get_partner_deals($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $deals = get_user_meta($user_id, 'teeptrak_deals', true);
    return is_array($deals) ? $deals : array();
}

/**
 * Get partner transactions
 */
function teeptrak_get_partner_transactions($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $transactions = get_user_meta($user_id, 'teeptrak_transactions', true);
    return is_array($transactions) ? $transactions : array();
}

/**
 * Get tier configuration
 */
function teeptrak_get_tier_config($tier = null) {
    $tiers = array(
        'bronze' => array(
            'name'            => __('Bronze', 'teeptrak-partner'),
            'commission_rate' => 15,
            'color'           => '#CD7F32',
            'gradient'        => 'linear-gradient(135deg, #CD7F32 0%, #8B4513 100%)',
            'requirements'    => __('Complete basic certification', 'teeptrak-partner'),
            'benefits'        => array(
                __('Deal Registration & Protection', 'teeptrak-partner'),
                __('Basic Training Access', 'teeptrak-partner'),
                __('Sales Resource Library', 'teeptrak-partner'),
                __('Email Support (48h response)', 'teeptrak-partner'),
            ),
        ),
        'silver' => array(
            'name'            => __('Silver', 'teeptrak-partner'),
            'commission_rate' => 20,
            'color'           => '#A8A8A8',
            'gradient'        => 'linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%)',
            'requirements'    => __('2+ closed deals, advanced certification', 'teeptrak-partner'),
            'benefits'        => array(
                __('Everything in Bronze', 'teeptrak-partner'),
                __('Co-Marketing Materials', 'teeptrak-partner'),
                __('Priority Technical Support', 'teeptrak-partner'),
                __('Quarterly Business Reviews', 'teeptrak-partner'),
            ),
        ),
        'gold' => array(
            'name'            => __('Gold', 'teeptrak-partner'),
            'commission_rate' => 25,
            'color'           => '#FFA500',
            'gradient'        => 'linear-gradient(135deg, #FFD700 0%, #FFA500 100%)',
            'requirements'    => __('5+ deals, €100K+ pipeline, full certification', 'teeptrak-partner'),
            'featured'        => true,
            'benefits'        => array(
                __('Everything in Silver', 'teeptrak-partner'),
                __('Dedicated Partner Success Manager', 'teeptrak-partner'),
                __('Lead Sharing from TeepTrak Marketing', 'teeptrak-partner'),
                __('Joint Customer Presentations', 'teeptrak-partner'),
                __('Early Access to New Features', 'teeptrak-partner'),
            ),
        ),
        'platinum' => array(
            'name'            => __('Platinum', 'teeptrak-partner'),
            'commission_rate' => 30,
            'color'           => '#6B7280',
            'gradient'        => 'linear-gradient(135deg, #E5E4E2 0%, #B4B4B4 100%)',
            'requirements'    => __('10+ deals, €250K+ pipeline, strategic alignment', 'teeptrak-partner'),
            'benefits'        => array(
                __('Everything in Gold', 'teeptrak-partner'),
                __('Strategic Planning Sessions', 'teeptrak-partner'),
                __('Executive Access & Sponsorship', 'teeptrak-partner'),
                __('Custom Marketing Campaigns', 'teeptrak-partner'),
                __('Preferred Implementation Partner Status', 'teeptrak-partner'),
            ),
        ),
    );

    if ($tier && isset($tiers[$tier])) {
        return $tiers[$tier];
    }

    return $tiers;
}

/**
 * Get onboarding steps
 */
function teeptrak_get_onboarding_steps() {
    return array(
        1 => array(
            'key'   => 'application',
            'title' => __('Application', 'teeptrak-partner'),
        ),
        2 => array(
            'key'   => 'agreement',
            'title' => __('Agreement', 'teeptrak-partner'),
        ),
        3 => array(
            'key'   => 'account',
            'title' => __('Account', 'teeptrak-partner'),
        ),
        4 => array(
            'key'   => 'training',
            'title' => __('Training', 'teeptrak-partner'),
        ),
        5 => array(
            'key'   => 'first_deal',
            'title' => __('First Deal', 'teeptrak-partner'),
        ),
        6 => array(
            'key'   => 'certified',
            'title' => __('Certified', 'teeptrak-partner'),
        ),
        7 => array(
            'key'   => 'first_close',
            'title' => __('First Close', 'teeptrak-partner'),
        ),
    );
}

/**
 * Get deal stages
 */
function teeptrak_get_deal_stages() {
    return array(
        'registered'     => array(
            'label' => __('Registered', 'teeptrak-partner'),
            'color' => '#9CA3AF',
        ),
        'qualified'      => array(
            'label' => __('Qualified', 'teeptrak-partner'),
            'color' => '#3B82F6',
        ),
        'demo_scheduled' => array(
            'label' => __('Demo Scheduled', 'teeptrak-partner'),
            'color' => '#F59E0B',
        ),
        'proposal_sent'  => array(
            'label' => __('Proposal Sent', 'teeptrak-partner'),
            'color' => '#8B5CF6',
        ),
        'negotiation'    => array(
            'label' => __('Negotiation', 'teeptrak-partner'),
            'color' => '#F97316',
        ),
        'closed_won'     => array(
            'label' => __('Closed Won', 'teeptrak-partner'),
            'color' => '#22C55E',
        ),
        'closed_lost'    => array(
            'label' => __('Closed Lost', 'teeptrak-partner'),
            'color' => '#DC2626',
        ),
    );
}

/**
 * Get training modules
 */
function teeptrak_get_training_modules() {
    return array(
        array(
            'id'          => 1,
            'title'       => __('Introduction to TeepTrak', 'teeptrak-partner'),
            'duration'    => 15,
            'level'       => __('Basic', 'teeptrak-partner'),
            'description' => __("Learn about TeepTrak's mission, history, and position in the Industrial IoT market.", 'teeptrak-partner'),
            'cert_level'  => 1,
        ),
        array(
            'id'          => 2,
            'title'       => __('OEE Fundamentals', 'teeptrak-partner'),
            'duration'    => 30,
            'level'       => __('Basic', 'teeptrak-partner'),
            'description' => __('Master the principles of Overall Equipment Effectiveness—the key metric our solutions optimize.', 'teeptrak-partner'),
            'cert_level'  => 1,
        ),
        array(
            'id'          => 3,
            'title'       => __('TeepTrak Product Features', 'teeptrak-partner'),
            'duration'    => 45,
            'level'       => __('Basic', 'teeptrak-partner'),
            'description' => __("Deep dive into TeepTrak's product capabilities, dashboards, and customer-facing features.", 'teeptrak-partner'),
            'cert_level'  => 1,
        ),
        array(
            'id'          => 4,
            'title'       => __('Sales Methodology', 'teeptrak-partner'),
            'duration'    => 60,
            'level'       => __('Intermediate', 'teeptrak-partner'),
            'description' => __('Learn how to identify, qualify, and close TeepTrak opportunities.', 'teeptrak-partner'),
            'cert_level'  => 2,
            'prerequisite' => 1,
        ),
        array(
            'id'          => 5,
            'title'       => __('Competitive Positioning', 'teeptrak-partner'),
            'duration'    => 45,
            'level'       => __('Intermediate', 'teeptrak-partner'),
            'description' => __('Understand the competitive landscape and how to position TeepTrak against alternatives.', 'teeptrak-partner'),
            'cert_level'  => 2,
            'prerequisite' => 1,
        ),
        array(
            'id'          => 6,
            'title'       => __('ROI & Business Case', 'teeptrak-partner'),
            'duration'    => 45,
            'level'       => __('Intermediate', 'teeptrak-partner'),
            'description' => __('Build compelling business cases that justify investment to CFOs and plant managers.', 'teeptrak-partner'),
            'cert_level'  => 2,
            'prerequisite' => 1,
        ),
        array(
            'id'          => 7,
            'title'       => __('Technical Architecture', 'teeptrak-partner'),
            'duration'    => 60,
            'level'       => __('Advanced', 'teeptrak-partner'),
            'description' => __("Understand TeepTrak's technical infrastructure for implementation discussions.", 'teeptrak-partner'),
            'cert_level'  => 3,
            'prerequisite' => 2,
        ),
        array(
            'id'          => 8,
            'title'       => __('Installation & Configuration', 'teeptrak-partner'),
            'duration'    => 90,
            'level'       => __('Advanced', 'teeptrak-partner'),
            'description' => __('Hands-on guide to deploying TeepTrak at customer sites.', 'teeptrak-partner'),
            'cert_level'  => 3,
            'prerequisite' => 2,
        ),
    );
}

/**
 * Get resources
 */
function teeptrak_get_resources() {
    return array(
        array(
            'title'       => __('TeepTrak Product Brochure (2026)', 'teeptrak-partner'),
            'description' => __('Complete product overview with features, benefits, and use cases', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '2.4 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Partner Sales Deck', 'teeptrak-partner'),
            'description' => __('Customizable presentation for customer meetings', 'teeptrak-partner'),
            'type'        => 'PPTX',
            'size'        => '8.1 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('OEE ROI Calculator', 'teeptrak-partner'),
            'description' => __('Interactive spreadsheet to calculate customer ROI', 'teeptrak-partner'),
            'type'        => 'XLSX',
            'size'        => '1.1 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Pricing Guide (Partner)', 'teeptrak-partner'),
            'description' => __('Current pricing, discounts, and deal structure', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '450 KB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Competitive Battle Cards', 'teeptrak-partner'),
            'description' => __('Positioning against MES, manual tracking, competitors', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.8 MB',
            'category'    => 'sales',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Discovery Call Script', 'teeptrak-partner'),
            'description' => __('Qualifying questions and call framework', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '380 KB',
            'category'    => 'sales',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Technical Specifications', 'teeptrak-partner'),
            'description' => __('Hardware requirements, connectivity, and system architecture', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '3.8 MB',
            'category'    => 'technical',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Integration Guide', 'teeptrak-partner'),
            'description' => __('Connecting TeepTrak with MES, ERP, and SCADA systems', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '5.2 MB',
            'category'    => 'technical',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('API Documentation', 'teeptrak-partner'),
            'description' => __('REST API reference for custom integrations', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '2.1 MB',
            'category'    => 'technical',
            'min_tier'    => 'gold',
        ),
        array(
            'title'       => __('Installation Checklist', 'teeptrak-partner'),
            'description' => __('Site readiness and deployment checklist', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '680 KB',
            'category'    => 'technical',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Co-Branded Email Templates', 'teeptrak-partner'),
            'description' => __('Ready-to-use outreach emails with customization spots', 'teeptrak-partner'),
            'type'        => 'ZIP',
            'size'        => '850 KB',
            'category'    => 'marketing',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Social Media Assets', 'teeptrak-partner'),
            'description' => __('LinkedIn posts, banners, and graphics', 'teeptrak-partner'),
            'type'        => 'ZIP',
            'size'        => '12.4 MB',
            'category'    => 'marketing',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Partner Logo Kit', 'teeptrak-partner'),
            'description' => __('TeepTrak Partner badges and logo files', 'teeptrak-partner'),
            'type'        => 'ZIP',
            'size'        => '4.2 MB',
            'category'    => 'marketing',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Webinar Slides Template', 'teeptrak-partner'),
            'description' => __('Presentation template for partner webinars', 'teeptrak-partner'),
            'type'        => 'PPTX',
            'size'        => '6.8 MB',
            'category'    => 'marketing',
            'min_tier'    => 'gold',
        ),
        array(
            'title'       => __('Stellantis Case Study', 'teeptrak-partner'),
            'description' => __('How Stellantis achieved 23% OEE improvement across 12 plants', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.8 MB',
            'category'    => 'case_study',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Alstom Case Study', 'teeptrak-partner'),
            'description' => __('Railway component manufacturing optimization', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.5 MB',
            'category'    => 'case_study',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Thales Case Study', 'teeptrak-partner'),
            'description' => __('Defense electronics assembly line transformation', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.6 MB',
            'category'    => 'case_study',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Mid-Market Success Stories', 'teeptrak-partner'),
            'description' => __('Compilation of SME implementations', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '2.8 MB',
            'category'    => 'case_study',
            'min_tier'    => 'silver',
        ),
        array(
            'title'       => __('Product Demo Video', 'teeptrak-partner'),
            'description' => __('Overview demo suitable for sharing with prospects', 'teeptrak-partner'),
            'type'        => 'VIDEO',
            'size'        => '5:23',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Customer Testimonial Reel', 'teeptrak-partner'),
            'description' => __('Plant managers sharing their TeepTrak experience', 'teeptrak-partner'),
            'type'        => 'VIDEO',
            'size'        => '3:45',
            'category'    => 'marketing',
            'min_tier'    => 'bronze',
        ),
        array(
            'title'       => __('Technical Deep Dive Webinar', 'teeptrak-partner'),
            'description' => __('Recorded session on architecture and integrations', 'teeptrak-partner'),
            'type'        => 'VIDEO',
            'size'        => '45:00',
            'category'    => 'technical',
            'min_tier'    => 'silver',
        ),
    );
}

/**
 * Format currency
 */
function teeptrak_format_currency($amount, $currency = 'EUR') {
    $symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
    );

    $symbol = isset($symbols[$currency]) ? $symbols[$currency] : $currency . ' ';
    return $symbol . number_format($amount, 0, ',', ' ');
}

/**
 * Calculate protection days remaining
 */
function teeptrak_get_protection_days($protection_end) {
    if (empty($protection_end)) {
        return 0;
    }

    $end = new DateTime($protection_end);
    $now = new DateTime();

    if ($now > $end) {
        return 0;
    }

    $diff = $now->diff($end);
    return $diff->days;
}

/**
 * Get protection status class
 */
function teeptrak_get_protection_status($days_left) {
    if ($days_left > 60) {
        return 'is-safe';
    } elseif ($days_left > 30) {
        return 'is-warning';
    }
    return 'is-danger';
}

/**
 * Check if tier has access to resource
 */
function teeptrak_tier_has_access($user_tier, $required_tier) {
    $tier_levels = array(
        'bronze'   => 1,
        'silver'   => 2,
        'gold'     => 3,
        'platinum' => 4,
    );

    $user_level = isset($tier_levels[$user_tier]) ? $tier_levels[$user_tier] : 0;
    $required_level = isset($tier_levels[$required_tier]) ? $tier_levels[$required_tier] : 0;

    return $user_level >= $required_level;
}

/**
 * Get file type icon color
 */
function teeptrak_get_file_type_colors($type) {
    $colors = array(
        'PDF'   => array('bg' => '#FEE2E2', 'color' => '#DC2626'),
        'XLSX'  => array('bg' => '#DCFCE7', 'color' => '#16A34A'),
        'ZIP'   => array('bg' => '#DBEAFE', 'color' => '#2563EB'),
        'PPTX'  => array('bg' => '#FEF3C7', 'color' => '#F59E0B'),
        'VIDEO' => array('bg' => '#F3E8FF', 'color' => '#9333EA'),
    );

    return isset($colors[$type]) ? $colors[$type] : array('bg' => '#F3F4F6', 'color' => '#6B7280');
}

/**
 * Portal access control
 */
function teeptrak_portal_access_control() {
    if (!teeptrak_is_portal_page()) {
        return;
    }

    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
}
add_action('template_redirect', 'teeptrak_portal_access_control');

/**
 * Custom login redirect
 */
function teeptrak_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        return home_url('/dashboard/');
    }
    return $redirect_to;
}
add_filter('login_redirect', 'teeptrak_login_redirect', 10, 3);

/**
 * Add body classes
 */
function teeptrak_body_classes($classes) {
    if (teeptrak_is_portal_page()) {
        $classes[] = 'tt-portal-body';
    }
    return $classes;
}
add_filter('body_class', 'teeptrak_body_classes');

/**
 * Register REST API routes
 */
function teeptrak_register_rest_routes() {
    // Register deal
    register_rest_route('teeptrak/v1', '/deals', array(
        array(
            'methods'             => 'GET',
            'callback'            => 'teeptrak_rest_get_deals',
            'permission_callback' => 'is_user_logged_in',
        ),
        array(
            'methods'             => 'POST',
            'callback'            => 'teeptrak_rest_create_deal',
            'permission_callback' => 'is_user_logged_in',
        ),
    ));

    // Partner stats
    register_rest_route('teeptrak/v1', '/partner/stats', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_stats',
        'permission_callback' => 'is_user_logged_in',
    ));
}
add_action('rest_api_init', 'teeptrak_register_rest_routes');

/**
 * REST: Get deals
 */
function teeptrak_rest_get_deals($request) {
    $deals = teeptrak_get_partner_deals();
    return rest_ensure_response($deals);
}

/**
 * REST: Create deal
 */
function teeptrak_rest_create_deal($request) {
    $params = $request->get_params();

    // Validate required fields
    if (empty($params['company_name'])) {
        return new WP_Error('missing_field', __('Company name is required', 'teeptrak-partner'), array('status' => 400));
    }

    $user_id = get_current_user_id();
    $deals = teeptrak_get_partner_deals($user_id);

    // Create new deal
    $deal_id = 'deal_' . uniqid();
    $protection_end = date('Y-m-d', strtotime('+90 days'));

    $new_deal = array(
        'id'              => $deal_id,
        'company_name'    => sanitize_text_field($params['company_name']),
        'industry'        => sanitize_text_field($params['industry'] ?? ''),
        'company_size'    => sanitize_text_field($params['company_size'] ?? ''),
        'num_plants'      => (int) ($params['num_plants'] ?? 1),
        'contact_name'    => sanitize_text_field($params['contact_name'] ?? ''),
        'contact_title'   => sanitize_text_field($params['contact_title'] ?? ''),
        'contact_email'   => sanitize_email($params['contact_email'] ?? ''),
        'contact_phone'   => sanitize_text_field($params['contact_phone'] ?? ''),
        'deal_value'      => (float) ($params['deal_value'] ?? 0),
        'expected_close'  => sanitize_text_field($params['expected_close'] ?? ''),
        'notes'           => sanitize_textarea_field($params['notes'] ?? ''),
        'stage'           => 'registered',
        'protection_end'  => $protection_end,
        'created_at'      => current_time('Y-m-d H:i:s'),
    );

    $deals[] = $new_deal;
    update_user_meta($user_id, 'teeptrak_deals', $deals);

    // Update onboarding step if needed
    $current_step = (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true);
    if ($current_step < 5) {
        update_user_meta($user_id, 'teeptrak_onboarding_step', 5);
    }

    return rest_ensure_response(array(
        'success' => true,
        'deal'    => $new_deal,
        'message' => __('Deal registered successfully', 'teeptrak-partner'),
    ));
}

/**
 * REST: Get partner stats
 */
function teeptrak_rest_get_stats($request) {
    $partner = teeptrak_get_current_partner();
    $deals = teeptrak_get_partner_deals();

    $active_deals = 0;
    $pipeline_value = 0;

    foreach ($deals as $deal) {
        if (!in_array($deal['stage'], array('closed_won', 'closed_lost'))) {
            $active_deals++;
            $pipeline_value += (float) $deal['deal_value'];
        }
    }

    return rest_ensure_response(array(
        'partner_score'   => $partner['partner_score'],
        'active_deals'    => $active_deals,
        'total_deals'     => count($deals),
        'pipeline_value'  => $pipeline_value,
        'commission_rate' => $partner['commission_rate'],
        'tier'            => $partner['tier'],
    ));
}

/**
 * AJAX: Register deal
 */
function teeptrak_ajax_register_deal() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $user_id = get_current_user_id();
    $deals = teeptrak_get_partner_deals($user_id);

    // Create new deal
    $deal_id = 'deal_' . uniqid();
    $protection_end = date('Y-m-d', strtotime('+90 days'));

    $new_deal = array(
        'id'              => $deal_id,
        'company_name'    => sanitize_text_field($_POST['company_name'] ?? ''),
        'industry'        => sanitize_text_field($_POST['industry'] ?? ''),
        'company_size'    => sanitize_text_field($_POST['company_size'] ?? ''),
        'num_plants'      => (int) ($_POST['num_plants'] ?? 1),
        'contact_name'    => sanitize_text_field($_POST['contact_name'] ?? ''),
        'contact_title'   => sanitize_text_field($_POST['contact_title'] ?? ''),
        'contact_email'   => sanitize_email($_POST['contact_email'] ?? ''),
        'contact_phone'   => sanitize_text_field($_POST['contact_phone'] ?? ''),
        'deal_value'      => (float) ($_POST['deal_value'] ?? 0),
        'expected_close'  => sanitize_text_field($_POST['expected_close'] ?? ''),
        'notes'           => sanitize_textarea_field($_POST['notes'] ?? ''),
        'stage'           => 'registered',
        'protection_end'  => $protection_end,
        'created_at'      => current_time('Y-m-d H:i:s'),
    );

    $deals[] = $new_deal;
    update_user_meta($user_id, 'teeptrak_deals', $deals);

    wp_send_json_success(array(
        'deal'    => $new_deal,
        'message' => __('Deal registered successfully! Your 90-day protection starts now.', 'teeptrak-partner'),
    ));
}
add_action('wp_ajax_teeptrak_register_deal', 'teeptrak_ajax_register_deal');

/**
 * Setup demo user data
 */
function teeptrak_setup_demo_user($user_id) {
    // Set partner meta
    update_user_meta($user_id, 'teeptrak_partner_tier', 'gold');
    update_user_meta($user_id, 'teeptrak_partner_score', 78);
    update_user_meta($user_id, 'teeptrak_commission_rate', 25);
    update_user_meta($user_id, 'teeptrak_onboarding_step', 5);
    update_user_meta($user_id, 'teeptrak_available_balance', 4500);
    update_user_meta($user_id, 'teeptrak_pending_commissions', 2800);
    update_user_meta($user_id, 'teeptrak_total_paid', 18500);

    // Demo deals
    $demo_deals = array(
        array(
            'id'             => 'deal_001',
            'company_name'   => 'Acme Manufacturing',
            'industry'       => 'Automotive',
            'contact_name'   => 'Pierre Martin',
            'contact_email'  => 'p.martin@acme.fr',
            'deal_value'     => 45000,
            'stage'          => 'proposal_sent',
            'protection_end' => date('Y-m-d', strtotime('+53 days')),
            'created_at'     => date('Y-m-d', strtotime('-37 days')),
        ),
        array(
            'id'             => 'deal_002',
            'company_name'   => 'TechParts GmbH',
            'industry'       => 'Electronics',
            'contact_name'   => 'Hans Schmidt',
            'contact_email'  => 'h.schmidt@techparts.de',
            'deal_value'     => 72000,
            'stage'          => 'demo_scheduled',
            'protection_end' => date('Y-m-d', strtotime('+71 days')),
            'created_at'     => date('Y-m-d', strtotime('-19 days')),
        ),
        array(
            'id'             => 'deal_003',
            'company_name'   => 'Precision Industries',
            'industry'       => 'Aerospace & Defense',
            'contact_name'   => 'Sarah Johnson',
            'contact_email'  => 's.johnson@precision.com',
            'deal_value'     => 28000,
            'stage'          => 'qualified',
            'protection_end' => date('Y-m-d', strtotime('+82 days')),
            'created_at'     => date('Y-m-d', strtotime('-8 days')),
        ),
    );
    update_user_meta($user_id, 'teeptrak_deals', $demo_deals);

    // Demo transactions
    $demo_transactions = array(
        array(
            'date'        => date('Y-m-d', strtotime('-6 days')),
            'type'        => 'commission',
            'description' => 'Stellantis Plant Lyon - Deal #1234',
            'amount'      => 3200,
            'status'      => 'paid',
        ),
        array(
            'date'        => date('Y-m-d', strtotime('-11 days')),
            'type'        => 'withdrawal',
            'description' => 'Bank Transfer to ****1234',
            'amount'      => -2500,
            'status'      => 'paid',
        ),
        array(
            'date'        => date('Y-m-d', strtotime('-24 days')),
            'type'        => 'commission',
            'description' => 'Renault Flins - Deal #1189',
            'amount'      => 1800,
            'status'      => 'paid',
        ),
        array(
            'date'        => date('Y-m-d', strtotime('-32 days')),
            'type'        => 'commission',
            'description' => 'Alstom Belfort - Deal #1156',
            'amount'      => 4500,
            'status'      => 'pending',
        ),
    );
    update_user_meta($user_id, 'teeptrak_transactions', $demo_transactions);

    // Demo training progress
    $training_progress = array(
        1 => 100,
        2 => 100,
        3 => 100,
        4 => 60,
        5 => 0,
        6 => 0,
        7 => 0,
        8 => 0,
    );
    update_user_meta($user_id, 'teeptrak_training_progress', $training_progress);
}

/**
 * Get training progress
 */
function teeptrak_get_training_progress($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $progress = get_user_meta($user_id, 'teeptrak_training_progress', true);

    if (empty($progress) || !is_array($progress)) {
        return array(
            'modules'   => array(),
            'completed' => 0,
            'total'     => 8,
            'percent'   => 0,
        );
    }

    $completed = 0;
    foreach ($progress as $module_id => $percent) {
        if ($percent >= 100) {
            $completed++;
        }
    }

    $total = 8;
    $overall_percent = $total > 0 ? round(($completed / $total) * 100) : 0;

    return array(
        'modules'   => $progress,
        'completed' => $completed,
        'total'     => $total,
        'percent'   => $overall_percent,
    );
}

/**
 * Include template files
 */
require_once TEEPTRAK_DIR . '/inc/template-tags.php';
require_once TEEPTRAK_DIR . '/inc/partner-functions.php';
require_once TEEPTRAK_DIR . '/inc/learnpress-integration.php';

/**
 * Admin notice for demo setup
 */
function teeptrak_admin_notices() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $screen = get_current_screen();
    if ($screen && $screen->id === 'users') {
        echo '<div class="notice notice-info">';
        echo '<p><strong>' . esc_html__('TeepTrak Partner Theme:', 'teeptrak-partner') . '</strong> ';
        echo esc_html__('To set up demo data for a user, add this code to your functions.php or run it once:', 'teeptrak-partner');
        echo ' <code>teeptrak_setup_demo_user($user_id);</code></p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'teeptrak_admin_notices');

/**
 * Auto-setup demo data for new users (optional - disable in production)
 */
function teeptrak_auto_setup_demo($user_id) {
    // Check if TEEPTRAK_DEMO_MODE is defined and true
    if (defined('TEEPTRAK_DEMO_MODE') && TEEPTRAK_DEMO_MODE) {
        teeptrak_setup_demo_user($user_id);
    }
}
add_action('user_register', 'teeptrak_auto_setup_demo');
