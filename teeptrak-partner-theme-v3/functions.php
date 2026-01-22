<?php
/**
 * TeepTrak Partner Theme V3 Functions
 *
 * @package TeepTrak_Partner_Theme_V3
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Constants
define('TEEPTRAK_VERSION', '3.0.0');
define('TEEPTRAK_DIR', get_template_directory());
define('TEEPTRAK_URI', get_template_directory_uri());
define('TEEPTRAK_ASSETS', TEEPTRAK_URI . '/assets');
define('TEEPTRAK_INC', TEEPTRAK_DIR . '/inc');

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

    // Load text domain for translations
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

    // Admin specific styles
    if (is_admin()) {
        wp_enqueue_style(
            'teeptrak-admin',
            TEEPTRAK_ASSETS . '/css/admin.css',
            array(),
            TEEPTRAK_VERSION
        );
    }

    // Chart.js for dashboard charts
    if (teeptrak_is_portal_page()) {
        wp_enqueue_script(
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
            array(),
            '4.4.1',
            true
        );

        // SortableJS for Kanban
        wp_enqueue_script(
            'sortable-js',
            'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js',
            array(),
            '1.15.0',
            true
        );

        // Charts module
        wp_enqueue_script(
            'teeptrak-charts',
            TEEPTRAK_ASSETS . '/js/charts.js',
            array('chart-js'),
            TEEPTRAK_VERSION,
            true
        );

        // Kanban module
        wp_enqueue_script(
            'teeptrak-kanban',
            TEEPTRAK_ASSETS . '/js/deals-kanban.js',
            array('sortable-js', 'teeptrak-main'),
            TEEPTRAK_VERSION,
            true
        );

        // Notifications module
        wp_enqueue_script(
            'teeptrak-notifications',
            TEEPTRAK_ASSETS . '/js/notifications.js',
            array('teeptrak-main'),
            TEEPTRAK_VERSION,
            true
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

    // Localize script with data
    wp_localize_script('teeptrak-main', 'teeptrakData', array(
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'restUrl'     => rest_url('teeptrak/v2/'),
        'nonce'       => wp_create_nonce('teeptrak_nonce'),
        'restNonce'   => wp_create_nonce('wp_rest'),
        'isLoggedIn'  => is_user_logged_in(),
        'currentUser' => teeptrak_get_current_partner(),
        'locale'      => teeptrak_get_current_locale(),
        'currency'    => teeptrak_get_currency(),
        'i18n'        => teeptrak_get_js_translations(),
    ));
}
add_action('wp_enqueue_scripts', 'teeptrak_enqueue_assets');

/**
 * Get JavaScript translations
 */
function teeptrak_get_js_translations() {
    return array(
        'loading'         => __('Loading...', 'teeptrak-partner'),
        'error'           => __('An error occurred', 'teeptrak-partner'),
        'success'         => __('Success!', 'teeptrak-partner'),
        'confirm'         => __('Are you sure?', 'teeptrak-partner'),
        'dealSuccess'     => __('Deal registered successfully! Your 90-day protection starts now.', 'teeptrak-partner'),
        'dealUpdated'     => __('Deal updated successfully', 'teeptrak-partner'),
        'stageUpdated'    => __('Deal stage updated', 'teeptrak-partner'),
        'noteAdded'       => __('Note added successfully', 'teeptrak-partner'),
        'payoutRequested' => __('Payout request submitted', 'teeptrak-partner'),
        'noResults'       => __('No results found', 'teeptrak-partner'),
        'days'            => __('days', 'teeptrak-partner'),
        'save'            => __('Save', 'teeptrak-partner'),
        'cancel'          => __('Cancel', 'teeptrak-partner'),
        'delete'          => __('Delete', 'teeptrak-partner'),
        'edit'            => __('Edit', 'teeptrak-partner'),
        'view'            => __('View', 'teeptrak-partner'),
        'close'           => __('Close', 'teeptrak-partner'),
    );
}

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
        'page-profile.php',
        'page-settings.php',
    );

    $portal_slugs = array(
        'dashboard',
        'deals',
        'training',
        'resources',
        'commissions',
        'profile',
        'settings',
    );

    $template = get_page_template_slug();
    $slug = get_post_field('post_name', get_the_ID());

    return in_array($template, $portal_templates) || in_array($slug, $portal_slugs);
}

/**
 * Get the partner application URL
 */
function teeptrak_get_application_url() {
    $application_page = get_page_by_path('apply');
    if ($application_page) {
        return get_permalink($application_page);
    }

    $custom_url = get_theme_mod('partner_application_url');
    if ($custom_url) {
        return $custom_url;
    }

    return wp_registration_url();
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
        'last_name'           => $user->last_name ?: '',
        'email'               => $user->user_email,
        'tier'                => get_user_meta($user_id, 'teeptrak_partner_tier', true) ?: 'bronze',
        'partner_score'       => (int) get_user_meta($user_id, 'teeptrak_partner_score', true) ?: 0,
        'commission_rate'     => (int) get_user_meta($user_id, 'teeptrak_commission_rate', true) ?: 15,
        'onboarding_step'     => (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true) ?: 1,
        'available_balance'   => (float) get_user_meta($user_id, 'teeptrak_available_balance', true) ?: 0,
        'pending_commissions' => (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true) ?: 0,
        'total_paid'          => (float) get_user_meta($user_id, 'teeptrak_total_paid', true) ?: 0,
        'total_earned'        => (float) get_user_meta($user_id, 'teeptrak_total_earned', true) ?: 0,
        'join_date'           => $user->user_registered,
        'company'             => get_user_meta($user_id, 'teeptrak_company', true) ?: '',
        'phone'               => get_user_meta($user_id, 'teeptrak_phone', true) ?: '',
        'country'             => get_user_meta($user_id, 'teeptrak_country', true) ?: '',
        'odoo_partner_id'     => get_user_meta($user_id, 'teeptrak_odoo_partner_id', true) ?: null,
        'locale'              => get_user_meta($user_id, 'teeptrak_locale', true) ?: teeptrak_get_current_locale(),
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
 * Get deal statistics
 */
function teeptrak_get_deal_stats($user_id = null) {
    $deals = teeptrak_get_partner_deals($user_id);

    $stats = array(
        'total'          => count($deals),
        'active'         => 0,
        'won'            => 0,
        'lost'           => 0,
        'pipeline_value' => 0,
        'won_value'      => 0,
        'by_stage'       => array(),
    );

    $stages = teeptrak_get_deal_stages();
    foreach ($stages as $stage_key => $stage) {
        $stats['by_stage'][$stage_key] = 0;
    }

    foreach ($deals as $deal) {
        $stage = $deal['stage'] ?? 'registered';
        $value = (float) ($deal['deal_value'] ?? 0);

        if (isset($stats['by_stage'][$stage])) {
            $stats['by_stage'][$stage]++;
        }

        if ($stage === 'closed_won') {
            $stats['won']++;
            $stats['won_value'] += $value;
        } elseif ($stage === 'closed_lost') {
            $stats['lost']++;
        } else {
            $stats['active']++;
            $stats['pipeline_value'] += $value;
        }
    }

    return $stats;
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
            'min_deals'       => 0,
            'min_pipeline'    => 0,
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
            'min_deals'       => 2,
            'min_pipeline'    => 50000,
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
            'requirements'    => __('5+ deals, 100K+ pipeline, full certification', 'teeptrak-partner'),
            'min_deals'       => 5,
            'min_pipeline'    => 100000,
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
            'requirements'    => __('10+ deals, 250K+ pipeline, strategic alignment', 'teeptrak-partner'),
            'min_deals'       => 10,
            'min_pipeline'    => 250000,
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
            'description' => __('Submit your partner application', 'teeptrak-partner'),
        ),
        2 => array(
            'key'   => 'agreement',
            'title' => __('Agreement', 'teeptrak-partner'),
            'description' => __('Sign the partner agreement', 'teeptrak-partner'),
        ),
        3 => array(
            'key'   => 'account',
            'title' => __('Account', 'teeptrak-partner'),
            'description' => __('Complete your profile setup', 'teeptrak-partner'),
        ),
        4 => array(
            'key'   => 'training',
            'title' => __('Training', 'teeptrak-partner'),
            'description' => __('Complete basic training modules', 'teeptrak-partner'),
        ),
        5 => array(
            'key'   => 'first_deal',
            'title' => __('First Deal', 'teeptrak-partner'),
            'description' => __('Register your first opportunity', 'teeptrak-partner'),
        ),
        6 => array(
            'key'   => 'certified',
            'title' => __('Certified', 'teeptrak-partner'),
            'description' => __('Achieve certification status', 'teeptrak-partner'),
        ),
        7 => array(
            'key'   => 'first_close',
            'title' => __('First Close', 'teeptrak-partner'),
            'description' => __('Close your first deal', 'teeptrak-partner'),
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
            'order' => 1,
        ),
        'qualified'      => array(
            'label' => __('Qualified', 'teeptrak-partner'),
            'color' => '#3B82F6',
            'order' => 2,
        ),
        'demo_scheduled' => array(
            'label' => __('Demo Scheduled', 'teeptrak-partner'),
            'color' => '#F59E0B',
            'order' => 3,
        ),
        'proposal_sent'  => array(
            'label' => __('Proposal Sent', 'teeptrak-partner'),
            'color' => '#8B5CF6',
            'order' => 4,
        ),
        'negotiation'    => array(
            'label' => __('Negotiation', 'teeptrak-partner'),
            'color' => '#F97316',
            'order' => 5,
        ),
        'closed_won'     => array(
            'label' => __('Closed Won', 'teeptrak-partner'),
            'color' => '#22C55E',
            'order' => 6,
        ),
        'closed_lost'    => array(
            'label' => __('Closed Lost', 'teeptrak-partner'),
            'color' => '#DC2626',
            'order' => 7,
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
            'description' => __('Master the principles of Overall Equipment Effectiveness.', 'teeptrak-partner'),
            'cert_level'  => 1,
        ),
        array(
            'id'          => 3,
            'title'       => __('TeepTrak Product Features', 'teeptrak-partner'),
            'duration'    => 45,
            'level'       => __('Basic', 'teeptrak-partner'),
            'description' => __("Deep dive into TeepTrak's product capabilities and features.", 'teeptrak-partner'),
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
            'description' => __('Understand the competitive landscape and positioning.', 'teeptrak-partner'),
            'cert_level'  => 2,
            'prerequisite' => 1,
        ),
        array(
            'id'          => 6,
            'title'       => __('ROI & Business Case', 'teeptrak-partner'),
            'duration'    => 45,
            'level'       => __('Intermediate', 'teeptrak-partner'),
            'description' => __('Build compelling business cases for customers.', 'teeptrak-partner'),
            'cert_level'  => 2,
            'prerequisite' => 1,
        ),
        array(
            'id'          => 7,
            'title'       => __('Technical Architecture', 'teeptrak-partner'),
            'duration'    => 60,
            'level'       => __('Advanced', 'teeptrak-partner'),
            'description' => __("Understand TeepTrak's technical infrastructure.", 'teeptrak-partner'),
            'cert_level'  => 3,
            'prerequisite' => 2,
        ),
        array(
            'id'          => 8,
            'title'       => __('Installation & Configuration', 'teeptrak-partner'),
            'duration'    => 90,
            'level'       => __('Advanced', 'teeptrak-partner'),
            'description' => __('Hands-on guide to deploying TeepTrak.', 'teeptrak-partner'),
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
            'id'          => 'res_001',
            'title'       => __('TeepTrak Product Brochure (2026)', 'teeptrak-partner'),
            'description' => __('Complete product overview with features, benefits, and use cases', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '2.4 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
            'downloads'   => 245,
        ),
        array(
            'id'          => 'res_002',
            'title'       => __('Partner Sales Deck', 'teeptrak-partner'),
            'description' => __('Customizable presentation for customer meetings', 'teeptrak-partner'),
            'type'        => 'PPTX',
            'size'        => '8.1 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
            'downloads'   => 189,
        ),
        array(
            'id'          => 'res_003',
            'title'       => __('OEE ROI Calculator', 'teeptrak-partner'),
            'description' => __('Interactive spreadsheet to calculate customer ROI', 'teeptrak-partner'),
            'type'        => 'XLSX',
            'size'        => '1.1 MB',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
            'downloads'   => 312,
        ),
        array(
            'id'          => 'res_004',
            'title'       => __('Technical Specifications', 'teeptrak-partner'),
            'description' => __('Hardware requirements, connectivity, and system architecture', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '3.8 MB',
            'category'    => 'technical',
            'min_tier'    => 'bronze',
            'downloads'   => 156,
        ),
        array(
            'id'          => 'res_005',
            'title'       => __('Competitive Battle Cards', 'teeptrak-partner'),
            'description' => __('Positioning against MES, manual tracking, competitors', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.8 MB',
            'category'    => 'sales',
            'min_tier'    => 'silver',
            'downloads'   => 98,
        ),
        array(
            'id'          => 'res_006',
            'title'       => __('Integration Guide', 'teeptrak-partner'),
            'description' => __('Connecting TeepTrak with MES, ERP, and SCADA systems', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '5.2 MB',
            'category'    => 'technical',
            'min_tier'    => 'silver',
            'downloads'   => 67,
        ),
        array(
            'id'          => 'res_007',
            'title'       => __('Co-Branded Email Templates', 'teeptrak-partner'),
            'description' => __('Ready-to-use outreach emails with customization spots', 'teeptrak-partner'),
            'type'        => 'ZIP',
            'size'        => '850 KB',
            'category'    => 'marketing',
            'min_tier'    => 'silver',
            'downloads'   => 124,
        ),
        array(
            'id'          => 'res_008',
            'title'       => __('Stellantis Case Study', 'teeptrak-partner'),
            'description' => __('How Stellantis achieved 23% OEE improvement across 12 plants', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '1.8 MB',
            'category'    => 'case_study',
            'min_tier'    => 'bronze',
            'downloads'   => 278,
        ),
        array(
            'id'          => 'res_009',
            'title'       => __('API Documentation', 'teeptrak-partner'),
            'description' => __('REST API reference for custom integrations', 'teeptrak-partner'),
            'type'        => 'PDF',
            'size'        => '2.1 MB',
            'category'    => 'technical',
            'min_tier'    => 'gold',
            'downloads'   => 45,
        ),
        array(
            'id'          => 'res_010',
            'title'       => __('Product Demo Video', 'teeptrak-partner'),
            'description' => __('Overview demo suitable for sharing with prospects', 'teeptrak-partner'),
            'type'        => 'VIDEO',
            'size'        => '5:23',
            'category'    => 'sales',
            'min_tier'    => 'bronze',
            'downloads'   => 534,
        ),
    );
}

/**
 * Get resource categories
 */
function teeptrak_get_resource_categories() {
    return array(
        'all'        => __('All Resources', 'teeptrak-partner'),
        'sales'      => __('Sales Materials', 'teeptrak-partner'),
        'technical'  => __('Technical Docs', 'teeptrak-partner'),
        'marketing'  => __('Marketing Assets', 'teeptrak-partner'),
        'case_study' => __('Case Studies', 'teeptrak-partner'),
    );
}

/**
 * Format currency
 */
function teeptrak_format_currency($amount, $currency = null) {
    if (!$currency) {
        $currency = teeptrak_get_currency();
    }

    $symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
        'CNY' => '¥',
    );

    $symbol = isset($symbols[$currency]) ? $symbols[$currency] : $currency . ' ';
    return $symbol . number_format($amount, 0, ',', ' ');
}

/**
 * Get currency based on region
 */
function teeptrak_get_currency() {
    $region = get_theme_mod('teeptrak_region', 'EU');

    $currencies = array(
        'EU'   => 'EUR',
        'US'   => 'USD',
        'APAC' => 'CNY',
        'UK'   => 'GBP',
    );

    return isset($currencies[$region]) ? $currencies[$region] : 'EUR';
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

    // Add locale class
    $locale = teeptrak_get_current_locale();
    $classes[] = 'tt-locale-' . str_replace('_', '-', strtolower($locale));

    return $classes;
}
add_filter('body_class', 'teeptrak_body_classes');

// =============================================================================
// MULTILINGUAL SUPPORT
// =============================================================================

/**
 * Get current locale
 */
function teeptrak_get_current_locale() {
    // Check user preference first
    if (is_user_logged_in()) {
        $user_locale = get_user_meta(get_current_user_id(), 'teeptrak_locale', true);
        if ($user_locale) {
            return $user_locale;
        }
    }

    // Check cookie
    if (isset($_COOKIE['teeptrak_locale'])) {
        return sanitize_text_field($_COOKIE['teeptrak_locale']);
    }

    // Check theme setting
    $theme_locale = get_theme_mod('teeptrak_default_locale');
    if ($theme_locale) {
        return $theme_locale;
    }

    // Fall back to WordPress locale
    return get_locale();
}

/**
 * Get available languages
 */
function teeptrak_get_available_languages() {
    return array(
        'en_US' => array(
            'name'   => 'English',
            'native' => 'English',
            'flag'   => 'us',
        ),
        'fr_FR' => array(
            'name'   => 'French',
            'native' => 'Francais',
            'flag'   => 'fr',
        ),
        'zh_CN' => array(
            'name'   => 'Chinese (Simplified)',
            'native' => '简体中文',
            'flag'   => 'cn',
        ),
        'de_DE' => array(
            'name'   => 'German',
            'native' => 'Deutsch',
            'flag'   => 'de',
        ),
        'es_ES' => array(
            'name'   => 'Spanish',
            'native' => 'Espanol',
            'flag'   => 'es',
        ),
    );
}

/**
 * Set user locale
 */
function teeptrak_set_locale($locale) {
    $available = teeptrak_get_available_languages();

    if (!isset($available[$locale])) {
        return false;
    }

    // Set cookie
    setcookie('teeptrak_locale', $locale, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

    // Update user meta if logged in
    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), 'teeptrak_locale', $locale);
    }

    return true;
}

/**
 * Switch locale filter
 */
function teeptrak_switch_locale($locale) {
    $teeptrak_locale = teeptrak_get_current_locale();

    if ($teeptrak_locale && $teeptrak_locale !== $locale) {
        return $teeptrak_locale;
    }

    return $locale;
}
add_filter('locale', 'teeptrak_switch_locale');

/**
 * AJAX: Set locale
 */
function teeptrak_ajax_set_locale() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    $locale = sanitize_text_field($_POST['locale'] ?? '');

    if (teeptrak_set_locale($locale)) {
        wp_send_json_success(array('locale' => $locale));
    } else {
        wp_send_json_error(array('message' => __('Invalid locale', 'teeptrak-partner')));
    }
}
add_action('wp_ajax_teeptrak_set_locale', 'teeptrak_ajax_set_locale');
add_action('wp_ajax_nopriv_teeptrak_set_locale', 'teeptrak_ajax_set_locale');

// =============================================================================
// INCLUDE MODULES
// =============================================================================

// Template Tags
require_once TEEPTRAK_INC . '/template-tags.php';

// Partner Functions
require_once TEEPTRAK_INC . '/partner-functions.php';

// Notification System
require_once TEEPTRAK_INC . '/notifications.php';

// REST API v2
require_once TEEPTRAK_INC . '/api/rest-api.php';

// Webhooks
require_once TEEPTRAK_INC . '/api/webhooks.php';

// Odoo Integration (only if configured)
if (defined('TEEPTRAK_ODOO_URL') || get_theme_mod('teeptrak_odoo_url')) {
    require_once TEEPTRAK_INC . '/integrations/odoo-integration.php';
}

// LearnPress Integration (only if plugin active)
if (class_exists('LearnPress')) {
    require_once TEEPTRAK_INC . '/integrations/learnpress-integration.php';
}

// PWA Support
require_once TEEPTRAK_INC . '/pwa.php';

// Admin pages (only in admin)
if (is_admin()) {
    require_once TEEPTRAK_INC . '/admin/admin-dashboard.php';
    require_once TEEPTRAK_INC . '/admin/admin-settings.php';
}

// =============================================================================
// CUSTOMIZER SETTINGS
// =============================================================================

/**
 * Register Customizer settings
 */
function teeptrak_customize_register($wp_customize) {
    // TeepTrak Settings Section
    $wp_customize->add_section('teeptrak_settings', array(
        'title'    => __('TeepTrak Partner Portal', 'teeptrak-partner'),
        'priority' => 30,
    ));

    // Brand Name
    $wp_customize->add_setting('teeptrak_brand_name', array(
        'default'           => 'TeepTrak',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_brand_name', array(
        'label'   => __('Brand Name', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
        'type'    => 'text',
    ));

    // Primary Color
    $wp_customize->add_setting('teeptrak_primary_color', array(
        'default'           => '#E63946',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'teeptrak_primary_color', array(
        'label'   => __('Primary Color', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
    )));

    // Region
    $wp_customize->add_setting('teeptrak_region', array(
        'default'           => 'EU',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_region', array(
        'label'   => __('Region', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
        'type'    => 'select',
        'choices' => array(
            'EU'   => __('Europe', 'teeptrak-partner'),
            'US'   => __('United States', 'teeptrak-partner'),
            'APAC' => __('Asia Pacific', 'teeptrak-partner'),
            'UK'   => __('United Kingdom', 'teeptrak-partner'),
        ),
    ));

    // Default Locale
    $wp_customize->add_setting('teeptrak_default_locale', array(
        'default'           => 'en_US',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_default_locale', array(
        'label'   => __('Default Language', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
        'type'    => 'select',
        'choices' => array(
            'en_US' => 'English',
            'fr_FR' => 'Francais',
            'zh_CN' => '中文',
            'de_DE' => 'Deutsch',
            'es_ES' => 'Espanol',
        ),
    ));

    // Support Email
    $wp_customize->add_setting('teeptrak_support_email', array(
        'default'           => 'partners@teeptrak.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('teeptrak_support_email', array(
        'label'   => __('Support Email', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
        'type'    => 'email',
    ));

    // Support Phone
    $wp_customize->add_setting('teeptrak_support_phone', array(
        'default'           => '+33 1 XX XX XX XX',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_support_phone', array(
        'label'   => __('Support Phone', 'teeptrak-partner'),
        'section' => 'teeptrak_settings',
        'type'    => 'text',
    ));

    // Odoo Settings Section
    $wp_customize->add_section('teeptrak_odoo_settings', array(
        'title'    => __('Odoo CRM Integration', 'teeptrak-partner'),
        'priority' => 31,
    ));

    // Odoo URL
    $wp_customize->add_setting('teeptrak_odoo_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('teeptrak_odoo_url', array(
        'label'   => __('Odoo URL', 'teeptrak-partner'),
        'section' => 'teeptrak_odoo_settings',
        'type'    => 'url',
    ));

    // Odoo Database
    $wp_customize->add_setting('teeptrak_odoo_db', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_odoo_db', array(
        'label'   => __('Odoo Database', 'teeptrak-partner'),
        'section' => 'teeptrak_odoo_settings',
        'type'    => 'text',
    ));

    // Odoo Sync Frequency
    $wp_customize->add_setting('teeptrak_odoo_sync_frequency', array(
        'default'           => 'hourly',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('teeptrak_odoo_sync_frequency', array(
        'label'   => __('Sync Frequency', 'teeptrak-partner'),
        'section' => 'teeptrak_odoo_settings',
        'type'    => 'select',
        'choices' => array(
            'realtime' => __('Real-time', 'teeptrak-partner'),
            'hourly'   => __('Hourly', 'teeptrak-partner'),
            'daily'    => __('Daily', 'teeptrak-partner'),
            'manual'   => __('Manual Only', 'teeptrak-partner'),
        ),
    ));
}
add_action('customize_register', 'teeptrak_customize_register');

/**
 * Output custom CSS for customizer settings
 */
function teeptrak_customizer_css() {
    $primary_color = get_theme_mod('teeptrak_primary_color', '#E63946');

    if ($primary_color !== '#E63946') {
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

// =============================================================================
// DEMO DATA SETUP
// =============================================================================

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
    update_user_meta($user_id, 'teeptrak_total_earned', 25800);

    // Demo deals
    $demo_deals = array(
        array(
            'id'             => 'deal_001',
            'company_name'   => 'Acme Manufacturing',
            'industry'       => 'Automotive',
            'contact_name'   => 'Pierre Martin',
            'contact_email'  => 'p.martin@acme.fr',
            'contact_phone'  => '+33 6 12 34 56 78',
            'deal_value'     => 45000,
            'stage'          => 'proposal_sent',
            'protection_end' => date('Y-m-d', strtotime('+53 days')),
            'created_at'     => date('Y-m-d', strtotime('-37 days')),
            'notes'          => array(),
        ),
        array(
            'id'             => 'deal_002',
            'company_name'   => 'TechParts GmbH',
            'industry'       => 'Electronics',
            'contact_name'   => 'Hans Schmidt',
            'contact_email'  => 'h.schmidt@techparts.de',
            'contact_phone'  => '+49 30 12345678',
            'deal_value'     => 72000,
            'stage'          => 'demo_scheduled',
            'protection_end' => date('Y-m-d', strtotime('+71 days')),
            'created_at'     => date('Y-m-d', strtotime('-19 days')),
            'notes'          => array(),
        ),
        array(
            'id'             => 'deal_003',
            'company_name'   => 'Precision Industries',
            'industry'       => 'Aerospace & Defense',
            'contact_name'   => 'Sarah Johnson',
            'contact_email'  => 's.johnson@precision.com',
            'contact_phone'  => '+1 555 123 4567',
            'deal_value'     => 28000,
            'stage'          => 'qualified',
            'protection_end' => date('Y-m-d', strtotime('+82 days')),
            'created_at'     => date('Y-m-d', strtotime('-8 days')),
            'notes'          => array(),
        ),
        array(
            'id'             => 'deal_004',
            'company_name'   => 'Global Foods Inc',
            'industry'       => 'Food & Beverage',
            'contact_name'   => 'Marie Chen',
            'contact_email'  => 'm.chen@globalfoods.com',
            'deal_value'     => 55000,
            'stage'          => 'registered',
            'protection_end' => date('Y-m-d', strtotime('+88 days')),
            'created_at'     => date('Y-m-d', strtotime('-2 days')),
            'notes'          => array(),
        ),
    );
    update_user_meta($user_id, 'teeptrak_deals', $demo_deals);

    // Demo transactions
    $demo_transactions = array(
        array(
            'id'          => 'txn_001',
            'date'        => date('Y-m-d', strtotime('-6 days')),
            'type'        => 'commission',
            'description' => 'Stellantis Plant Lyon - Deal #1234',
            'amount'      => 3200,
            'status'      => 'paid',
        ),
        array(
            'id'          => 'txn_002',
            'date'        => date('Y-m-d', strtotime('-11 days')),
            'type'        => 'withdrawal',
            'description' => 'Bank Transfer to ****1234',
            'amount'      => -2500,
            'status'      => 'paid',
        ),
        array(
            'id'          => 'txn_003',
            'date'        => date('Y-m-d', strtotime('-24 days')),
            'type'        => 'commission',
            'description' => 'Renault Flins - Deal #1189',
            'amount'      => 1800,
            'status'      => 'paid',
        ),
        array(
            'id'          => 'txn_004',
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
 * Auto-setup demo data for new users (optional)
 */
function teeptrak_auto_setup_demo($user_id) {
    if (defined('TEEPTRAK_DEMO_MODE') && TEEPTRAK_DEMO_MODE) {
        teeptrak_setup_demo_user($user_id);
    }
}
add_action('user_register', 'teeptrak_auto_setup_demo');
