<?php
/**
 * Plugin Name: TeepTrak Partner Portal
 * Plugin URI: https://teeptrak.com/partner-portal
 * Description: Complete partner portal management system with deal registration, commissions, and LMS integration.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: TeepTrak
 * Author URI: https://teeptrak.com
 * License: Proprietary
 * License URI: https://teeptrak.com/license
 * Text Domain: teeptrak-portal
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin Constants
define('TEEPTRAK_PORTAL_VERSION', '1.0.0');
define('TEEPTRAK_PORTAL_FILE', __FILE__);
define('TEEPTRAK_PORTAL_DIR', plugin_dir_path(__FILE__));
define('TEEPTRAK_PORTAL_URL', plugin_dir_url(__FILE__));
define('TEEPTRAK_PORTAL_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
final class TeepTrak_Partner_Portal {
    
    /**
     * Single instance
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Core classes
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-partner.php';
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-deal.php';
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-commission.php';
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-resource.php';
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-api.php';
        require_once TEEPTRAK_PORTAL_DIR . 'includes/class-notifications.php';
        
        // Admin
        if (is_admin()) {
            require_once TEEPTRAK_PORTAL_DIR . 'admin/class-admin.php';
        }
        
        // Public
        require_once TEEPTRAK_PORTAL_DIR . 'public/class-public.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Activation/Deactivation
        register_activation_hook(TEEPTRAK_PORTAL_FILE, array($this, 'activate'));
        register_deactivation_hook(TEEPTRAK_PORTAL_FILE, array($this, 'deactivate'));
        
        // Init
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Admin init
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }
        
        // Cron jobs
        add_action('teeptrak_daily_tasks', array($this, 'run_daily_tasks'));
        add_action('teeptrak_check_deal_protection', array($this, 'check_deal_protection'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Add capabilities
        $this->add_capabilities();
        
        // Schedule cron jobs
        if (!wp_next_scheduled('teeptrak_daily_tasks')) {
            wp_schedule_event(time(), 'daily', 'teeptrak_daily_tasks');
        }
        
        if (!wp_next_scheduled('teeptrak_check_deal_protection')) {
            wp_schedule_event(time(), 'daily', 'teeptrak_check_deal_protection');
        }
        
        // Create default pages
        $this->create_pages();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clear scheduled hooks
        wp_clear_scheduled_hook('teeptrak_daily_tasks');
        wp_clear_scheduled_hook('teeptrak_check_deal_protection');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = array();
        
        // Partners table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_partners (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            company_website VARCHAR(255),
            company_address TEXT,
            company_country VARCHAR(100),
            company_size VARCHAR(50),
            tier ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
            commission_rate DECIMAL(5,2) DEFAULT 15.00,
            partner_score INT DEFAULT 0,
            onboarding_step INT DEFAULT 1,
            agreement_signed TINYINT(1) DEFAULT 0,
            agreement_date DATETIME,
            psm_id BIGINT(20) UNSIGNED,
            status ENUM('pending', 'active', 'suspended', 'terminated') DEFAULT 'pending',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY user_id (user_id),
            KEY tier (tier),
            KEY status (status)
        ) $charset_collate;";
        
        // Deals table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_deals (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            partner_id BIGINT(20) UNSIGNED NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            contact_name VARCHAR(255),
            contact_email VARCHAR(255),
            contact_phone VARCHAR(50),
            company_industry VARCHAR(100),
            company_size VARCHAR(50),
            deal_value DECIMAL(15,2),
            currency VARCHAR(3) DEFAULT 'EUR',
            stage ENUM('registered', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost') DEFAULT 'registered',
            probability INT DEFAULT 10,
            expected_close_date DATE,
            protection_start DATE,
            protection_end DATE,
            lost_reason VARCHAR(255),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
            KEY partner_id (partner_id),
            KEY stage (stage),
            KEY protection_end (protection_end)
        ) $charset_collate;";
        
        // Commissions table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_commissions (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            partner_id BIGINT(20) UNSIGNED NOT NULL,
            deal_id BIGINT(20) UNSIGNED,
            type ENUM('commission', 'bonus', 'adjustment', 'withdrawal') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'EUR',
            status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
            approved_by BIGINT(20) UNSIGNED,
            approved_at DATETIME,
            payment_date DATETIME,
            payment_reference VARCHAR(255),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY partner_id (partner_id),
            KEY deal_id (deal_id),
            KEY status (status),
            KEY type (type)
        ) $charset_collate;";
        
        // Resources table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_resources (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            category ENUM('sales', 'technical', 'marketing', 'case_study', 'training') NOT NULL,
            file_url VARCHAR(500),
            file_type VARCHAR(10),
            file_size VARCHAR(20),
            thumbnail_url VARCHAR(500),
            min_tier ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
            language VARCHAR(5) DEFAULT 'en',
            is_featured TINYINT(1) DEFAULT 0,
            download_count INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
            KEY category (category),
            KEY min_tier (min_tier),
            KEY language (language)
        ) $charset_collate;";
        
        // Badges table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_badges (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            partner_id BIGINT(20) UNSIGNED NOT NULL,
            badge_type VARCHAR(50) NOT NULL,
            earned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY partner_id (partner_id),
            UNIQUE KEY partner_badge (partner_id, badge_type)
        ) $charset_collate;";
        
        // Scheduled calls table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_scheduled_calls (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            partner_id BIGINT(20) UNSIGNED NOT NULL,
            psm_id BIGINT(20) UNSIGNED NOT NULL,
            scheduled_date DATETIME NOT NULL,
            duration INT DEFAULT 30,
            meeting_type VARCHAR(50),
            meeting_url VARCHAR(500),
            status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY partner_id (partner_id),
            KEY psm_id (psm_id),
            KEY scheduled_date (scheduled_date),
            KEY status (status)
        ) $charset_collate;";
        
        // Activity log table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_activity_log (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            partner_id BIGINT(20) UNSIGNED,
            user_id BIGINT(20) UNSIGNED,
            action VARCHAR(100) NOT NULL,
            object_type VARCHAR(50),
            object_id BIGINT(20) UNSIGNED,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY partner_id (partner_id),
            KEY user_id (user_id),
            KEY action (action),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Notifications table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}teeptrak_notifications (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            type VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT,
            link VARCHAR(500),
            is_read TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY user_id (user_id),
            KEY is_read (is_read),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        foreach ($sql as $query) {
            dbDelta($query);
        }
        
        // Store database version
        update_option('teeptrak_portal_db_version', TEEPTRAK_PORTAL_VERSION);
    }
    
    /**
     * Add capabilities
     */
    private function add_capabilities() {
        // Add partner role if not exists
        if (!get_role('partner')) {
            add_role('partner', __('Partner', 'teeptrak-portal'), array(
                'read' => true,
                'upload_files' => true,
            ));
        }
        
        // Add PSM role
        if (!get_role('psm')) {
            add_role('psm', __('Partner Success Manager', 'teeptrak-portal'), array(
                'read' => true,
                'upload_files' => true,
                'manage_partners' => true,
                'view_partner_deals' => true,
                'approve_commissions' => true,
            ));
        }
        
        // Add capabilities to admin
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('manage_partners');
            $admin->add_cap('view_partner_deals');
            $admin->add_cap('approve_commissions');
            $admin->add_cap('manage_partner_resources');
            $admin->add_cap('view_partner_reports');
        }
    }
    
    /**
     * Create default pages
     */
    private function create_pages() {
        $pages = array(
            'dashboard' => array(
                'title' => __('Dashboard', 'teeptrak-portal'),
                'template' => 'page-dashboard.php',
            ),
            'academy' => array(
                'title' => __('Academy', 'teeptrak-portal'),
                'template' => 'page-academy.php',
            ),
            'deals' => array(
                'title' => __('Deal Registration', 'teeptrak-portal'),
                'template' => 'page-deals.php',
            ),
            'training' => array(
                'title' => __('Training', 'teeptrak-portal'),
                'template' => 'page-training.php',
            ),
            'resources' => array(
                'title' => __('Resources', 'teeptrak-portal'),
                'template' => 'page-resources.php',
            ),
            'commissions' => array(
                'title' => __('Commissions', 'teeptrak-portal'),
                'template' => 'page-commissions.php',
            ),
            'quiz' => array(
                'title' => __('Certification Quiz', 'teeptrak-portal'),
                'template' => 'page-quiz.php',
            ),
            'agreement' => array(
                'title' => __('Partner Agreement', 'teeptrak-portal'),
                'template' => 'page-agreement.php',
            ),
            'schedule' => array(
                'title' => __('Schedule Call', 'teeptrak-portal'),
                'template' => 'page-schedule.php',
            ),
            'become-partner' => array(
                'title' => __('Become a Partner', 'teeptrak-portal'),
                'template' => 'page-become-partner.php',
            ),
        );
        
        foreach ($pages as $slug => $page) {
            // Check if page exists
            $existing = get_page_by_path($slug);
            
            if (!$existing) {
                wp_insert_post(array(
                    'post_title' => $page['title'],
                    'post_name' => $slug,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => $page['template'],
                ));
            }
        }
    }
    
    /**
     * Init
     */
    public function init() {
        // Register shortcodes
        $this->register_shortcodes();
    }
    
    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'teeptrak-portal',
            false,
            dirname(TEEPTRAK_PORTAL_BASENAME) . '/languages'
        );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __('Partner Portal', 'teeptrak-portal'),
            __('Partner Portal', 'teeptrak-portal'),
            'manage_partners',
            'teeptrak-portal',
            array($this, 'render_admin_dashboard'),
            'dashicons-groups',
            30
        );
        
        // Submenus
        add_submenu_page(
            'teeptrak-portal',
            __('Partners', 'teeptrak-portal'),
            __('Partners', 'teeptrak-portal'),
            'manage_partners',
            'teeptrak-partners',
            array($this, 'render_partners_page')
        );
        
        add_submenu_page(
            'teeptrak-portal',
            __('Deals', 'teeptrak-portal'),
            __('Deals', 'teeptrak-portal'),
            'view_partner_deals',
            'teeptrak-deals',
            array($this, 'render_deals_page')
        );
        
        add_submenu_page(
            'teeptrak-portal',
            __('Commissions', 'teeptrak-portal'),
            __('Commissions', 'teeptrak-portal'),
            'approve_commissions',
            'teeptrak-commissions',
            array($this, 'render_commissions_page')
        );
        
        add_submenu_page(
            'teeptrak-portal',
            __('Resources', 'teeptrak-portal'),
            __('Resources', 'teeptrak-portal'),
            'manage_partner_resources',
            'teeptrak-resources',
            array($this, 'render_resources_page')
        );
        
        add_submenu_page(
            'teeptrak-portal',
            __('Reports', 'teeptrak-portal'),
            __('Reports', 'teeptrak-portal'),
            'view_partner_reports',
            'teeptrak-reports',
            array($this, 'render_reports_page')
        );
        
        add_submenu_page(
            'teeptrak-portal',
            __('Settings', 'teeptrak-portal'),
            __('Settings', 'teeptrak-portal'),
            'manage_options',
            'teeptrak-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Admin enqueue scripts
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'teeptrak') === false) {
            return;
        }
        
        wp_enqueue_style(
            'teeptrak-admin',
            TEEPTRAK_PORTAL_URL . 'admin/css/admin.css',
            array(),
            TEEPTRAK_PORTAL_VERSION
        );
        
        wp_enqueue_script(
            'teeptrak-admin',
            TEEPTRAK_PORTAL_URL . 'admin/js/admin.js',
            array('jquery', 'wp-api'),
            TEEPTRAK_PORTAL_VERSION,
            true
        );
        
        wp_localize_script('teeptrak-admin', 'teeptrakAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('teeptrak_admin_nonce'),
        ));
    }
    
    /**
     * Register shortcodes
     */
    private function register_shortcodes() {
        add_shortcode('teeptrak_partner_dashboard', array($this, 'shortcode_dashboard'));
        add_shortcode('teeptrak_deal_form', array($this, 'shortcode_deal_form'));
        add_shortcode('teeptrak_resources', array($this, 'shortcode_resources'));
        add_shortcode('teeptrak_commissions', array($this, 'shortcode_commissions'));
        add_shortcode('teeptrak_partner_registration', array($this, 'shortcode_registration'));
    }
    
    /**
     * Dashboard shortcode
     */
    public function shortcode_dashboard($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your dashboard.', 'teeptrak-portal') . '</p>';
        }
        
        ob_start();
        include TEEPTRAK_PORTAL_DIR . 'public/views/dashboard.php';
        return ob_get_clean();
    }
    
    /**
     * Deal form shortcode
     */
    public function shortcode_deal_form($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to register deals.', 'teeptrak-portal') . '</p>';
        }
        
        ob_start();
        include TEEPTRAK_PORTAL_DIR . 'public/views/deal-form.php';
        return ob_get_clean();
    }
    
    /**
     * Resources shortcode
     */
    public function shortcode_resources($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 10,
        ), $atts);
        
        ob_start();
        include TEEPTRAK_PORTAL_DIR . 'public/views/resources.php';
        return ob_get_clean();
    }
    
    /**
     * Commissions shortcode
     */
    public function shortcode_commissions($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view commissions.', 'teeptrak-portal') . '</p>';
        }
        
        ob_start();
        include TEEPTRAK_PORTAL_DIR . 'public/views/commissions.php';
        return ob_get_clean();
    }
    
    /**
     * Registration shortcode
     */
    public function shortcode_registration($atts) {
        if (is_user_logged_in()) {
            $partner = $this->get_partner_by_user(get_current_user_id());
            if ($partner) {
                return '<p>' . __('You are already a partner.', 'teeptrak-portal') . 
                       ' <a href="' . home_url('/dashboard/') . '">' . __('Go to Dashboard', 'teeptrak-portal') . '</a></p>';
            }
        }
        
        ob_start();
        include TEEPTRAK_PORTAL_DIR . 'public/views/registration.php';
        return ob_get_clean();
    }
    
    /**
     * Run daily tasks
     */
    public function run_daily_tasks() {
        // Update partner scores
        $this->update_partner_scores();
        
        // Check tier upgrades
        $this->check_tier_upgrades();
        
        // Send digest emails
        $this->send_digest_emails();
    }
    
    /**
     * Check deal protection expiry
     */
    public function check_deal_protection() {
        global $wpdb;
        
        // Find deals with protection expiring in 7 days
        $expiring_deals = $wpdb->get_results($wpdb->prepare(
            "SELECT d.*, p.user_id 
             FROM {$wpdb->prefix}teeptrak_deals d
             JOIN {$wpdb->prefix}teeptrak_partners p ON d.partner_id = p.id
             WHERE d.protection_end = DATE_ADD(CURDATE(), INTERVAL 7 DAY)
             AND d.stage NOT IN ('closed_won', 'closed_lost')"
        ));
        
        foreach ($expiring_deals as $deal) {
            // Send notification
            do_action('teeptrak_deal_protection_expiring', $deal);
        }
    }
    
    /**
     * Update partner scores
     */
    private function update_partner_scores() {
        global $wpdb;
        
        $partners = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE status = 'active'"
        );
        
        foreach ($partners as $partner) {
            $score = $this->calculate_partner_score($partner->id);
            
            $wpdb->update(
                $wpdb->prefix . 'teeptrak_partners',
                array('partner_score' => $score),
                array('id' => $partner->id),
                array('%d'),
                array('%d')
            );
        }
    }
    
    /**
     * Calculate partner score
     */
    private function calculate_partner_score($partner_id) {
        global $wpdb;
        
        $score = 0;
        
        // Active deals (max 30 points)
        $active_deals = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_deals 
             WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
            $partner_id
        ));
        $score += min(30, $active_deals * 5);
        
        // Won deals (max 30 points)
        $won_deals = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_deals 
             WHERE partner_id = %d AND stage = 'closed_won'",
            $partner_id
        ));
        $score += min(30, $won_deals * 10);
        
        // Training completion (max 20 points)
        $partner = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE id = %d",
            $partner_id
        ));
        
        if ($partner && defined('LEARNDASH_VERSION')) {
            $courses = learndash_user_get_enrolled_courses($partner->user_id);
            $completed = 0;
            foreach ($courses as $course_id) {
                if (learndash_course_completed($partner->user_id, $course_id)) {
                    $completed++;
                }
            }
            $score += min(20, $completed * 5);
        }
        
        // Badges (max 20 points)
        $badges = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_badges WHERE partner_id = %d",
            $partner_id
        ));
        $score += min(20, $badges * 4);
        
        return min(100, $score);
    }
    
    /**
     * Check tier upgrades
     */
    private function check_tier_upgrades() {
        global $wpdb;
        
        $partners = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE status = 'active'"
        );
        
        $tier_requirements = array(
            'platinum' => 500000,
            'gold' => 300000,
            'silver' => 100000,
            'bronze' => 0,
        );
        
        foreach ($partners as $partner) {
            // Calculate annual revenue
            $annual_revenue = $wpdb->get_var($wpdb->prepare(
                "SELECT SUM(d.deal_value) 
                 FROM {$wpdb->prefix}teeptrak_deals d
                 WHERE d.partner_id = %d 
                 AND d.stage = 'closed_won'
                 AND d.updated_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)",
                $partner->id
            ));
            
            // Determine new tier
            $new_tier = 'bronze';
            foreach ($tier_requirements as $tier => $min_revenue) {
                if ($annual_revenue >= $min_revenue) {
                    $new_tier = $tier;
                    break;
                }
            }
            
            // Update if changed
            if ($new_tier !== $partner->tier) {
                $old_tier = $partner->tier;
                
                // Get new commission rate
                $tier_config = array(
                    'bronze' => 15,
                    'silver' => 20,
                    'gold' => 25,
                    'platinum' => 30,
                );
                
                $wpdb->update(
                    $wpdb->prefix . 'teeptrak_partners',
                    array(
                        'tier' => $new_tier,
                        'commission_rate' => $tier_config[$new_tier],
                    ),
                    array('id' => $partner->id),
                    array('%s', '%f'),
                    array('%d')
                );
                
                // Trigger notification
                do_action('teeptrak_tier_changed', $partner->id, $old_tier, $new_tier);
            }
        }
    }
    
    /**
     * Send digest emails
     */
    private function send_digest_emails() {
        // Implementation for weekly/monthly digest emails
    }
    
    /**
     * Get partner by user
     */
    public function get_partner_by_user($user_id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE user_id = %d",
            $user_id
        ));
    }
    
    /**
     * Admin page renders
     */
    public function render_admin_dashboard() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/dashboard.php';
    }
    
    public function render_partners_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/partners.php';
    }
    
    public function render_deals_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/deals.php';
    }
    
    public function render_commissions_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/commissions.php';
    }
    
    public function render_resources_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/resources.php';
    }
    
    public function render_reports_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/reports.php';
    }
    
    public function render_settings_page() {
        include TEEPTRAK_PORTAL_DIR . 'admin/views/settings.php';
    }
}

/**
 * Initialize plugin
 */
function teeptrak_portal() {
    return TeepTrak_Partner_Portal::get_instance();
}

// Start the plugin
teeptrak_portal();
