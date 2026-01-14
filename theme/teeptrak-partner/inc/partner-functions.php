<?php
/**
 * Partner-specific functions
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create new partner from user registration
 */
function teeptrak_create_partner($user_id, $data = array()) {
    global $wpdb;
    
    $defaults = array(
        'company_name' => '',
        'company_website' => '',
        'company_country' => '',
        'tier' => 'bronze',
        'commission_rate' => 15.00,
    );
    
    $data = wp_parse_args($data, $defaults);
    
    $result = $wpdb->insert(
        $wpdb->prefix . 'teeptrak_partners',
        array(
            'user_id' => $user_id,
            'company_name' => sanitize_text_field($data['company_name']),
            'company_website' => esc_url_raw($data['company_website']),
            'company_country' => sanitize_text_field($data['company_country']),
            'tier' => $data['tier'],
            'commission_rate' => floatval($data['commission_rate']),
            'partner_score' => 0,
            'onboarding_step' => 1,
            'status' => 'pending',
            'created_at' => current_time('mysql'),
        ),
        array('%d', '%s', '%s', '%s', '%s', '%f', '%d', '%d', '%s', '%s')
    );
    
    if ($result) {
        // Assign partner role
        $user = new WP_User($user_id);
        $user->add_role('partner');
        
        // Send welcome email
        do_action('teeptrak_partner_created', $wpdb->insert_id, $user_id);
        
        return $wpdb->insert_id;
    }
    
    return false;
}

/**
 * Update partner tier based on revenue
 */
function teeptrak_update_partner_tier($partner_id) {
    global $wpdb;
    
    // Calculate annual revenue from closed deals
    $annual_revenue = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(deal_value) FROM {$wpdb->prefix}teeptrak_deals 
         WHERE partner_id = %d 
         AND stage = 'closed_won' 
         AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)",
        $partner_id
    ));
    
    // Determine tier
    $tier_thresholds = array(
        'platinum' => 500000,
        'gold' => 300000,
        'silver' => 100000,
        'bronze' => 0,
    );
    
    $new_tier = 'bronze';
    foreach ($tier_thresholds as $tier => $threshold) {
        if ($annual_revenue >= $threshold) {
            $new_tier = $tier;
            break;
        }
    }
    
    // Get commission rate
    $commission_rates = array(
        'bronze' => 15,
        'silver' => 20,
        'gold' => 25,
        'platinum' => 30,
    );
    
    // Update partner
    $wpdb->update(
        $wpdb->prefix . 'teeptrak_partners',
        array(
            'tier' => $new_tier,
            'commission_rate' => $commission_rates[$new_tier],
        ),
        array('id' => $partner_id),
        array('%s', '%f'),
        array('%d')
    );
    
    return $new_tier;
}

/**
 * Award badge to partner
 */
function teeptrak_award_badge($partner_id, $badge_type) {
    global $wpdb;
    
    // Check if badge already exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}teeptrak_badges 
         WHERE partner_id = %d AND badge_type = %s",
        $partner_id, $badge_type
    ));
    
    if ($existing) {
        return false;
    }
    
    $result = $wpdb->insert(
        $wpdb->prefix . 'teeptrak_badges',
        array(
            'partner_id' => $partner_id,
            'badge_type' => $badge_type,
            'earned_at' => current_time('mysql'),
        ),
        array('%d', '%s', '%s')
    );
    
    if ($result) {
        // Send notification
        do_action('teeptrak_badge_earned', $partner_id, $badge_type);
        return true;
    }
    
    return false;
}

/**
 * Get partner badges
 */
function teeptrak_get_partner_badges($partner_id) {
    global $wpdb;
    
    $badges = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_badges WHERE partner_id = %d ORDER BY earned_at DESC",
        $partner_id
    ), ARRAY_A);
    
    $badge_info = array(
        'certified_pro' => array(
            'name' => __('Certified Professional', 'teeptrak-partner'),
            'icon' => 'award',
            'color' => '#F59E0B',
        ),
        'first_deal' => array(
            'name' => __('First Deal', 'teeptrak-partner'),
            'icon' => 'target',
            'color' => '#22C55E',
        ),
        'deal_maker' => array(
            'name' => __('Deal Maker (10 deals)', 'teeptrak-partner'),
            'icon' => 'star',
            'color' => '#3B82F6',
        ),
        'top_performer' => array(
            'name' => __('Top Performer', 'teeptrak-partner'),
            'icon' => 'trophy',
            'color' => '#8B5CF6',
        ),
        'early_adopter' => array(
            'name' => __('Early Adopter', 'teeptrak-partner'),
            'icon' => 'rocket',
            'color' => '#EB352B',
        ),
    );
    
    foreach ($badges as &$badge) {
        $info = $badge_info[$badge['badge_type']] ?? array(
            'name' => ucfirst(str_replace('_', ' ', $badge['badge_type'])),
            'icon' => 'badge',
            'color' => '#6B7280',
        );
        $badge = array_merge($badge, $info);
    }
    
    return $badges;
}

/**
 * Log partner activity
 */
function teeptrak_log_activity($partner_id, $action, $object_type = null, $object_id = null, $details = null) {
    global $wpdb;
    
    $wpdb->insert(
        $wpdb->prefix . 'teeptrak_activity_log',
        array(
            'partner_id' => $partner_id,
            'user_id' => get_current_user_id(),
            'action' => $action,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'details' => is_array($details) ? json_encode($details) : $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => current_time('mysql'),
        ),
        array('%d', '%d', '%s', '%s', '%d', '%s', '%s', '%s', '%s')
    );
}

/**
 * Create notification
 */
function teeptrak_create_notification($user_id, $type, $title, $message = '', $link = '') {
    global $wpdb;
    
    $wpdb->insert(
        $wpdb->prefix . 'teeptrak_notifications',
        array(
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
            'created_at' => current_time('mysql'),
        ),
        array('%d', '%s', '%s', '%s', '%s', '%d', '%s')
    );
    
    return $wpdb->insert_id;
}

/**
 * Get unread notifications count
 */
function teeptrak_get_notifications_count($user_id) {
    global $wpdb;
    
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_notifications 
         WHERE user_id = %d AND is_read = 0",
        $user_id
    ));
}

/**
 * Mark notifications as read
 */
function teeptrak_mark_notifications_read($user_id, $notification_ids = array()) {
    global $wpdb;
    
    if (empty($notification_ids)) {
        // Mark all as read
        $wpdb->update(
            $wpdb->prefix . 'teeptrak_notifications',
            array('is_read' => 1),
            array('user_id' => $user_id),
            array('%d'),
            array('%d')
        );
    } else {
        // Mark specific notifications
        $ids = array_map('intval', $notification_ids);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->prefix}teeptrak_notifications 
             SET is_read = 1 
             WHERE user_id = %d AND id IN ($placeholders)",
            array_merge(array($user_id), $ids)
        ));
    }
}

/**
 * Send partner email
 */
function teeptrak_send_partner_email($user_id, $template, $data = array()) {
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return false;
    }
    
    $templates = array(
        'welcome' => array(
            'subject' => __('Welcome to TeepTrak Partner Program!', 'teeptrak-partner'),
            'template' => 'emails/welcome.php',
        ),
        'deal_registered' => array(
            'subject' => __('Deal Registration Confirmed', 'teeptrak-partner'),
            'template' => 'emails/deal-registered.php',
        ),
        'commission_earned' => array(
            'subject' => __('Commission Earned!', 'teeptrak-partner'),
            'template' => 'emails/commission-earned.php',
        ),
        'tier_upgrade' => array(
            'subject' => __('Congratulations! Tier Upgrade', 'teeptrak-partner'),
            'template' => 'emails/tier-upgrade.php',
        ),
        'protection_expiring' => array(
            'subject' => __('Deal Protection Expiring Soon', 'teeptrak-partner'),
            'template' => 'emails/protection-expiring.php',
        ),
    );
    
    if (!isset($templates[$template])) {
        return false;
    }
    
    $template_info = $templates[$template];
    $data['user'] = $user;
    $data['site_name'] = get_bloginfo('name');
    $data['dashboard_url'] = home_url('/dashboard/');
    
    // Get email content
    ob_start();
    include locate_template($template_info['template']);
    $message = ob_get_clean();
    
    // Send email
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: TeepTrak Partner Program <partners@teeptrak.com>',
    );
    
    return wp_mail($user->user_email, $template_info['subject'], $message, $headers);
}

/**
 * Handle partner registration form
 */
function teeptrak_handle_partner_registration() {
    if (!isset($_POST['teeptrak_partner_registration_nonce']) || 
        !wp_verify_nonce($_POST['teeptrak_partner_registration_nonce'], 'teeptrak_partner_registration')) {
        wp_send_json_error(array('message' => __('Security check failed', 'teeptrak-partner')));
    }
    
    // Validate fields
    $required_fields = array('company_name', 'email', 'first_name', 'last_name', 'country');
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error(array('message' => sprintf(__('%s is required', 'teeptrak-partner'), ucfirst(str_replace('_', ' ', $field)))));
        }
    }
    
    $email = sanitize_email($_POST['email']);
    
    // Check if email already exists
    if (email_exists($email)) {
        wp_send_json_error(array('message' => __('This email is already registered', 'teeptrak-partner')));
    }
    
    // Create user
    $username = sanitize_user(strtolower($_POST['first_name'] . '.' . $_POST['last_name']));
    $username = wp_unique_username($username);
    $password = wp_generate_password(12);
    
    $user_id = wp_create_user($username, $password, $email);
    
    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }
    
    // Update user meta
    wp_update_user(array(
        'ID' => $user_id,
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'display_name' => sanitize_text_field($_POST['first_name'] . ' ' . $_POST['last_name']),
    ));
    
    // Create partner
    $partner_id = teeptrak_create_partner($user_id, array(
        'company_name' => sanitize_text_field($_POST['company_name']),
        'company_website' => esc_url_raw($_POST['company_website'] ?? ''),
        'company_country' => sanitize_text_field($_POST['country']),
    ));
    
    if (!$partner_id) {
        wp_delete_user($user_id);
        wp_send_json_error(array('message' => __('Failed to create partner account', 'teeptrak-partner')));
    }
    
    // Send welcome email with credentials
    teeptrak_send_partner_email($user_id, 'welcome', array(
        'username' => $username,
        'password' => $password,
    ));
    
    // Log user in
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    
    wp_send_json_success(array(
        'message' => __('Registration successful! Redirecting...', 'teeptrak-partner'),
        'redirect' => home_url('/dashboard/'),
    ));
}
add_action('wp_ajax_nopriv_teeptrak_partner_registration', 'teeptrak_handle_partner_registration');

/**
 * Generate unique username
 */
function wp_unique_username($username) {
    $original = $username;
    $i = 1;
    
    while (username_exists($username)) {
        $username = $original . $i;
        $i++;
    }
    
    return $username;
}

/**
 * Notification hooks
 */
add_action('teeptrak_deal_created', function($deal_id, $partner_id) {
    global $wpdb;
    
    $partner = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE id = %d",
        $partner_id
    ));
    
    if ($partner) {
        teeptrak_create_notification(
            $partner->user_id,
            'deal',
            __('Deal Registered Successfully', 'teeptrak-partner'),
            __('Your deal has been registered and is now protected for 90 days.', 'teeptrak-partner'),
            home_url('/deals/')
        );
        
        teeptrak_log_activity($partner_id, 'deal_created', 'deal', $deal_id);
    }
}, 10, 2);

add_action('teeptrak_tier_changed', function($partner_id, $old_tier, $new_tier) {
    global $wpdb;
    
    $partner = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_partners WHERE id = %d",
        $partner_id
    ));
    
    if ($partner && $new_tier !== $old_tier) {
        $tier_order = array('bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4);
        
        if ($tier_order[$new_tier] > $tier_order[$old_tier]) {
            // Upgrade
            teeptrak_create_notification(
                $partner->user_id,
                'tier_upgrade',
                sprintf(__('Congratulations! You are now %s tier!', 'teeptrak-partner'), ucfirst($new_tier)),
                __('Your commission rate has been increased.', 'teeptrak-partner'),
                home_url('/dashboard/')
            );
            
            teeptrak_send_partner_email($partner->user_id, 'tier_upgrade', array(
                'old_tier' => $old_tier,
                'new_tier' => $new_tier,
            ));
        }
        
        teeptrak_log_activity($partner_id, 'tier_changed', 'partner', $partner_id, array(
            'old_tier' => $old_tier,
            'new_tier' => $new_tier,
        ));
    }
}, 10, 3);
