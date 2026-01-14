<?php
/**
 * TeepTrak Partner Portal Theme Functions
 *
 * @package TeepTrak_Partner
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
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
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
    
    // Dashboard specific styles
    if (is_page_template('page-dashboard.php') || teeptrak_is_portal_page()) {
        wp_enqueue_style(
            'teeptrak-dashboard',
            TEEPTRAK_ASSETS . '/css/dashboard.css',
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
    
    // Dashboard JavaScript
    if (teeptrak_is_portal_page()) {
        wp_enqueue_script(
            'teeptrak-dashboard',
            TEEPTRAK_ASSETS . '/js/dashboard.js',
            array('teeptrak-main'),
            TEEPTRAK_VERSION,
            true
        );
        
        // Chart.js for statistics
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
            array(),
            '4.4.1',
            true
        );
    }
    
    // Localize script
    wp_localize_script('teeptrak-main', 'teeptrakData', array(
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'apiUrl'    => rest_url('teeptrak/v1/'),
        'nonce'     => wp_create_nonce('teeptrak_nonce'),
        'isLoggedIn' => is_user_logged_in(),
        'currentUser' => teeptrak_get_current_partner_data(),
        'i18n' => array(
            'loading'   => __('Loading...', 'teeptrak-partner'),
            'error'     => __('An error occurred', 'teeptrak-partner'),
            'success'   => __('Success!', 'teeptrak-partner'),
            'confirm'   => __('Are you sure?', 'teeptrak-partner'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'teeptrak_enqueue_assets');

/**
 * Check if current page is a portal page
 */
function teeptrak_is_portal_page() {
    $portal_pages = array(
        'dashboard',
        'academy',
        'deals',
        'training',
        'resources',
        'commissions',
        'quiz',
        'agreement',
        'schedule',
    );
    
    $current_page = get_post_field('post_name', get_the_ID());
    return in_array($current_page, $portal_pages) || is_page_template('templates/portal.php');
}

/**
 * Get current partner data
 */
function teeptrak_get_current_partner_data() {
    if (!is_user_logged_in()) {
        return null;
    }
    
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return array(
            'id' => 0,
            'name' => wp_get_current_user()->display_name,
            'email' => wp_get_current_user()->user_email,
            'tier' => 'bronze',
            'commission_rate' => 15,
        );
    }
    
    return $partner;
}

/**
 * Get partner by user ID
 */
function teeptrak_get_partner_by_user($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'teeptrak_partners';
    
    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
        return null;
    }
    
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE user_id = %d",
        $user_id
    ), ARRAY_A);
}

/**
 * Get tier configuration
 */
function teeptrak_get_tier_config($tier = 'bronze') {
    $tiers = array(
        'bronze' => array(
            'name' => __('Bronze', 'teeptrak-partner'),
            'commission_rate' => 15,
            'color' => '#CD7F32',
            'gradient' => 'linear-gradient(135deg, #CD7F32 0%, #8B4513 100%)',
            'min_revenue' => 0,
        ),
        'silver' => array(
            'name' => __('Silver', 'teeptrak-partner'),
            'commission_rate' => 20,
            'color' => '#9CA3AF',
            'gradient' => 'linear-gradient(135deg, #D1D5DB 0%, #6B7280 100%)',
            'min_revenue' => 100000,
        ),
        'gold' => array(
            'name' => __('Gold', 'teeptrak-partner'),
            'commission_rate' => 25,
            'color' => '#EAB308',
            'gradient' => 'linear-gradient(135deg, #FDE047 0%, #CA8A04 100%)',
            'min_revenue' => 300000,
        ),
        'platinum' => array(
            'name' => __('Platinum', 'teeptrak-partner'),
            'commission_rate' => 30,
            'color' => '#E5E7EB',
            'gradient' => 'linear-gradient(135deg, #F3F4F6 0%, #9CA3AF 100%)',
            'min_revenue' => 500000,
        ),
    );
    
    return isset($tiers[$tier]) ? $tiers[$tier] : $tiers['bronze'];
}

/**
 * Get onboarding steps
 */
function teeptrak_get_onboarding_steps() {
    return array(
        1 => array(
            'slug' => 'agreement',
            'title' => __('Sign Agreement', 'teeptrak-partner'),
            'description' => __('Review and sign the partner agreement', 'teeptrak-partner'),
        ),
        2 => array(
            'slug' => 'profile',
            'title' => __('Complete Profile', 'teeptrak-partner'),
            'description' => __('Fill in your company information', 'teeptrak-partner'),
        ),
        3 => array(
            'slug' => 'intro_video',
            'title' => __('Watch Intro Video', 'teeptrak-partner'),
            'description' => __('Learn about TeepTrak solutions', 'teeptrak-partner'),
        ),
        4 => array(
            'slug' => 'sales_training',
            'title' => __('Sales Training', 'teeptrak-partner'),
            'description' => __('Complete the sales training module', 'teeptrak-partner'),
        ),
        5 => array(
            'slug' => 'certification',
            'title' => __('Pass Certification', 'teeptrak-partner'),
            'description' => __('Pass the certification quiz', 'teeptrak-partner'),
        ),
        6 => array(
            'slug' => 'first_deal',
            'title' => __('Register First Deal', 'teeptrak-partner'),
            'description' => __('Register your first opportunity', 'teeptrak-partner'),
        ),
        7 => array(
            'slug' => 'schedule_call',
            'title' => __('Schedule Call', 'teeptrak-partner'),
            'description' => __('Meet with your Partner Success Manager', 'teeptrak-partner'),
        ),
    );
}

/**
 * Redirect non-partners from portal pages
 */
function teeptrak_portal_access_control() {
    if (!teeptrak_is_portal_page()) {
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
    
    // Check if user is a partner
    $partner = teeptrak_get_partner_by_user(get_current_user_id());
    if (!$partner && !current_user_can('administrator')) {
        wp_redirect(home_url('/become-partner/'));
        exit;
    }
}
add_action('template_redirect', 'teeptrak_portal_access_control');

/**
 * Custom login redirect for partners
 */
function teeptrak_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('partner', $user->roles) || in_array('administrator', $user->roles)) {
            return home_url('/dashboard/');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'teeptrak_login_redirect', 10, 3);

/**
 * Add partner role
 */
function teeptrak_add_partner_role() {
    add_role('partner', __('Partner', 'teeptrak-partner'), array(
        'read' => true,
        'upload_files' => true,
    ));
}
add_action('init', 'teeptrak_add_partner_role');

/**
 * Register REST API routes
 */
function teeptrak_register_rest_routes() {
    // Partner routes
    register_rest_route('teeptrak/v1', '/partner', array(
        'methods' => 'GET',
        'callback' => 'teeptrak_api_get_partner',
        'permission_callback' => 'teeptrak_api_check_permission',
    ));
    
    register_rest_route('teeptrak/v1', '/partner/stats', array(
        'methods' => 'GET',
        'callback' => 'teeptrak_api_get_partner_stats',
        'permission_callback' => 'teeptrak_api_check_permission',
    ));
    
    // Deals routes
    register_rest_route('teeptrak/v1', '/deals', array(
        array(
            'methods' => 'GET',
            'callback' => 'teeptrak_api_get_deals',
            'permission_callback' => 'teeptrak_api_check_permission',
        ),
        array(
            'methods' => 'POST',
            'callback' => 'teeptrak_api_create_deal',
            'permission_callback' => 'teeptrak_api_check_permission',
        ),
    ));
    
    register_rest_route('teeptrak/v1', '/deals/(?P<id>\d+)', array(
        array(
            'methods' => 'GET',
            'callback' => 'teeptrak_api_get_deal',
            'permission_callback' => 'teeptrak_api_check_permission',
        ),
        array(
            'methods' => 'PUT',
            'callback' => 'teeptrak_api_update_deal',
            'permission_callback' => 'teeptrak_api_check_permission',
        ),
        array(
            'methods' => 'DELETE',
            'callback' => 'teeptrak_api_delete_deal',
            'permission_callback' => 'teeptrak_api_check_permission',
        ),
    ));
    
    // Commissions routes
    register_rest_route('teeptrak/v1', '/commissions', array(
        'methods' => 'GET',
        'callback' => 'teeptrak_api_get_commissions',
        'permission_callback' => 'teeptrak_api_check_permission',
    ));
    
    register_rest_route('teeptrak/v1', '/commissions/summary', array(
        'methods' => 'GET',
        'callback' => 'teeptrak_api_get_commissions_summary',
        'permission_callback' => 'teeptrak_api_check_permission',
    ));
    
    // Resources routes
    register_rest_route('teeptrak/v1', '/resources', array(
        'methods' => 'GET',
        'callback' => 'teeptrak_api_get_resources',
        'permission_callback' => 'teeptrak_api_check_permission',
    ));
}
add_action('rest_api_init', 'teeptrak_register_rest_routes');

/**
 * API permission callback
 */
function teeptrak_api_check_permission() {
    return is_user_logged_in();
}

/**
 * API: Get partner profile
 */
function teeptrak_api_get_partner($request) {
    $partner = teeptrak_get_current_partner_data();
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    return rest_ensure_response($partner);
}

/**
 * API: Get partner stats
 */
function teeptrak_api_get_partner_stats($request) {
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    global $wpdb;
    $partner_id = $partner['id'];
    
    // Get deals count
    $active_deals = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_deals 
         WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
        $partner_id
    ));
    
    // Get pipeline value
    $pipeline_value = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(deal_value) FROM {$wpdb->prefix}teeptrak_deals 
         WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
        $partner_id
    ));
    
    // Get available commissions
    $available_commissions = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(amount) FROM {$wpdb->prefix}teeptrak_commissions 
         WHERE partner_id = %d AND status = 'approved' AND type = 'commission'",
        $partner_id
    ));
    
    return rest_ensure_response(array(
        'partner_score' => intval($partner['partner_score']),
        'active_deals' => intval($active_deals),
        'pipeline_value' => floatval($pipeline_value) ?: 0,
        'available_commissions' => floatval($available_commissions) ?: 0,
        'onboarding_step' => intval($partner['onboarding_step']),
        'tier' => $partner['tier'],
    ));
}

/**
 * API: Get deals
 */
function teeptrak_api_get_deals($request) {
    global $wpdb;
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    $deals = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_deals 
         WHERE partner_id = %d 
         ORDER BY created_at DESC",
        $partner['id']
    ), ARRAY_A);
    
    // Add protection days remaining
    foreach ($deals as &$deal) {
        if ($deal['protection_end']) {
            $protection_end = new DateTime($deal['protection_end']);
            $now = new DateTime();
            $diff = $now->diff($protection_end);
            $deal['protection_days_left'] = $diff->invert ? 0 : $diff->days;
        } else {
            $deal['protection_days_left'] = 0;
        }
    }
    
    return rest_ensure_response($deals);
}

/**
 * API: Create deal
 */
function teeptrak_api_create_deal($request) {
    global $wpdb;
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    $params = $request->get_params();
    
    // Validate required fields
    if (empty($params['company_name'])) {
        return new WP_Error('missing_field', __('Company name is required', 'teeptrak-partner'), array('status' => 400));
    }
    
    // Calculate protection end date (90 days)
    $protection_start = current_time('Y-m-d');
    $protection_end = date('Y-m-d', strtotime('+90 days'));
    
    $result = $wpdb->insert(
        $wpdb->prefix . 'teeptrak_deals',
        array(
            'partner_id' => $partner['id'],
            'company_name' => sanitize_text_field($params['company_name']),
            'contact_name' => sanitize_text_field($params['contact_name'] ?? ''),
            'contact_email' => sanitize_email($params['contact_email'] ?? ''),
            'deal_value' => floatval($params['deal_value'] ?? 0),
            'currency' => sanitize_text_field($params['currency'] ?? 'EUR'),
            'stage' => 'registered',
            'protection_start' => $protection_start,
            'protection_end' => $protection_end,
            'notes' => sanitize_textarea_field($params['notes'] ?? ''),
            'created_at' => current_time('mysql'),
        ),
        array('%d', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s')
    );
    
    if (!$result) {
        return new WP_Error('insert_failed', __('Failed to create deal', 'teeptrak-partner'), array('status' => 500));
    }
    
    $deal_id = $wpdb->insert_id;
    
    // Send notification
    do_action('teeptrak_deal_created', $deal_id, $partner['id']);
    
    return rest_ensure_response(array(
        'id' => $deal_id,
        'message' => __('Deal registered successfully', 'teeptrak-partner'),
    ));
}

/**
 * API: Get commissions
 */
function teeptrak_api_get_commissions($request) {
    global $wpdb;
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    $commissions = $wpdb->get_results($wpdb->prepare(
        "SELECT c.*, d.company_name as deal_company 
         FROM {$wpdb->prefix}teeptrak_commissions c
         LEFT JOIN {$wpdb->prefix}teeptrak_deals d ON c.deal_id = d.id
         WHERE c.partner_id = %d 
         ORDER BY c.created_at DESC
         LIMIT 50",
        $partner['id']
    ), ARRAY_A);
    
    return rest_ensure_response($commissions);
}

/**
 * API: Get commissions summary
 */
function teeptrak_api_get_commissions_summary($request) {
    global $wpdb;
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    if (!$partner) {
        return new WP_Error('no_partner', __('Partner not found', 'teeptrak-partner'), array('status' => 404));
    }
    
    $partner_id = $partner['id'];
    
    $available = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}teeptrak_commissions 
         WHERE partner_id = %d AND status = 'approved' AND type = 'commission'",
        $partner_id
    ));
    
    $pending = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}teeptrak_commissions 
         WHERE partner_id = %d AND status = 'pending' AND type = 'commission'",
        $partner_id
    ));
    
    $total_paid = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}teeptrak_commissions 
         WHERE partner_id = %d AND status = 'paid' AND type = 'commission'",
        $partner_id
    ));
    
    return rest_ensure_response(array(
        'available' => floatval($available),
        'pending' => floatval($pending),
        'total_paid' => floatval($total_paid),
        'currency' => 'EUR',
    ));
}

/**
 * API: Get resources
 */
function teeptrak_api_get_resources($request) {
    global $wpdb;
    $user_id = get_current_user_id();
    $partner = teeptrak_get_partner_by_user($user_id);
    
    $tier = $partner ? $partner['tier'] : 'bronze';
    $tier_order = array('bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4);
    $current_tier_level = $tier_order[$tier] ?? 1;
    
    // Get resources accessible to partner's tier
    $resources = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}teeptrak_resources 
         WHERE min_tier IN ('bronze'" . 
         ($current_tier_level >= 2 ? ", 'silver'" : "") .
         ($current_tier_level >= 3 ? ", 'gold'" : "") .
         ($current_tier_level >= 4 ? ", 'platinum'" : "") .
         ") ORDER BY created_at DESC",
        ARRAY_A
    );
    
    return rest_ensure_response($resources);
}

/**
 * Include template parts
 */
function teeptrak_get_template_part($slug, $name = null, $args = array()) {
    get_template_part('template-parts/' . $slug, $name, $args);
}

/**
 * Format currency
 */
function teeptrak_format_currency($amount, $currency = 'EUR') {
    $symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
        'CNY' => '¥',
    );
    
    $symbol = $symbols[$currency] ?? $currency;
    return $symbol . number_format($amount, 0, ',', ' ');
}

/**
 * Calculate protection progress
 */
function teeptrak_get_protection_progress($protection_end) {
    if (empty($protection_end)) {
        return 0;
    }
    
    $end = new DateTime($protection_end);
    $now = new DateTime();
    $start = clone $end;
    $start->modify('-90 days');
    
    $total_days = $start->diff($end)->days;
    $remaining_days = $now->diff($end)->days;
    
    if ($now > $end) {
        return 0;
    }
    
    return min(100, max(0, ($remaining_days / $total_days) * 100));
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
 * LearnDash Integration
 */
if (defined('LEARNDASH_VERSION')) {
    /**
     * Get partner's course progress
     */
    function teeptrak_get_partner_course_progress($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        $courses = learndash_user_get_enrolled_courses($user_id);
        $progress = array();
        
        foreach ($courses as $course_id) {
            $course_progress = learndash_course_progress(array(
                'user_id' => $user_id,
                'course_id' => $course_id,
                'array' => true,
            ));
            
            $progress[] = array(
                'course_id' => $course_id,
                'title' => get_the_title($course_id),
                'completed' => $course_progress['completed'],
                'total' => $course_progress['total'],
                'percentage' => $course_progress['percentage'],
            );
        }
        
        return $progress;
    }
    
    /**
     * Award badge on course completion
     */
    function teeptrak_on_course_completed($data) {
        $user_id = $data['user']->ID;
        $course_id = $data['course']->ID;
        
        // Check if this is a certification course
        $is_certification = get_post_meta($course_id, '_teeptrak_is_certification', true);
        
        if ($is_certification) {
            // Update partner's onboarding step
            global $wpdb;
            $wpdb->update(
                $wpdb->prefix . 'teeptrak_partners',
                array('onboarding_step' => 5),
                array('user_id' => $user_id),
                array('%d'),
                array('%d')
            );
            
            // Award certification badge
            $wpdb->insert(
                $wpdb->prefix . 'teeptrak_badges',
                array(
                    'partner_id' => teeptrak_get_partner_by_user($user_id)['id'],
                    'badge_type' => 'certified_pro',
                    'earned_at' => current_time('mysql'),
                ),
                array('%d', '%s', '%s')
            );
            
            // Send notification
            do_action('teeptrak_certification_passed', $user_id, $course_id);
        }
    }
    add_action('learndash_course_completed', 'teeptrak_on_course_completed');
}

/**
 * WPML/Polylang Integration
 */
function teeptrak_get_current_language() {
    // WPML
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    
    // Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    return 'en';
}

/**
 * Get available languages
 */
function teeptrak_get_languages() {
    // WPML
    if (function_exists('icl_get_languages')) {
        return icl_get_languages('skip_missing=0');
    }
    
    // Polylang
    if (function_exists('pll_the_languages')) {
        return pll_the_languages(array('raw' => 1));
    }
    
    // Default
    return array(
        'en' => array('name' => 'English', 'code' => 'EN'),
        'fr' => array('name' => 'Français', 'code' => 'FR'),
        'zh' => array('name' => '中文', 'code' => 'CN'),
    );
}

/**
 * Include additional files
 */
require_once TEEPTRAK_DIR . '/inc/customizer.php';
require_once TEEPTRAK_DIR . '/inc/template-tags.php';
require_once TEEPTRAK_DIR . '/inc/partner-functions.php';

/**
 * Plugin activation notice
 */
function teeptrak_admin_notices() {
    // Check for required plugins
    $missing_plugins = array();
    
    if (!defined('LEARNDASH_VERSION') && !defined('TUTOR_VERSION')) {
        $missing_plugins[] = 'LearnDash or Tutor LMS';
    }
    
    if (!empty($missing_plugins)) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>TeepTrak Partner Portal:</strong> ' . 
             sprintf(__('The following plugins are recommended: %s', 'teeptrak-partner'), implode(', ', $missing_plugins)) .
             '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'teeptrak_admin_notices');
