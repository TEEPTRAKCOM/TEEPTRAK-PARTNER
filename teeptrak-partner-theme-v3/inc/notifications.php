<?php
/**
 * Notification System for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create notifications table on theme activation
 */
function teeptrak_create_notifications_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'teeptrak_notifications';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        user_id bigint(20) unsigned NOT NULL,
        type varchar(50) NOT NULL DEFAULT 'system',
        title varchar(255) NOT NULL,
        message text,
        link varchar(255) DEFAULT NULL,
        is_read tinyint(1) NOT NULL DEFAULT 0,
        metadata longtext DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY type (type),
        KEY is_read (is_read),
        KEY created_at (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'teeptrak_create_notifications_table');

/**
 * Send notification to a user
 *
 * @param int    $user_id  User ID
 * @param string $type     Notification type
 * @param string $title    Notification title
 * @param string $message  Notification message
 * @param string $link     Optional link
 * @param array  $metadata Optional metadata
 * @return int|false Notification ID or false on failure
 */
function teeptrak_send_notification($user_id, $type, $title, $message = '', $link = '', $metadata = array()) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id'    => $user_id,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'link'       => $link,
            'metadata'   => !empty($metadata) ? wp_json_encode($metadata) : null,
            'created_at' => current_time('mysql'),
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
    );

    if ($result) {
        $notification_id = $wpdb->insert_id;

        // Trigger action for other integrations (email, push, etc.)
        do_action('teeptrak_notification_sent', $notification_id, $user_id, $type, $title, $message);

        // Send email notification if enabled
        $send_email = get_user_meta($user_id, 'teeptrak_email_notifications', true);
        if ($send_email !== 'disabled') {
            teeptrak_send_notification_email($user_id, $type, $title, $message, $link);
        }

        return $notification_id;
    }

    return false;
}

/**
 * Get notifications for a user
 *
 * @param int  $user_id     User ID
 * @param bool $unread_only Only get unread notifications
 * @param int  $limit       Number of notifications to get
 * @param int  $offset      Offset for pagination
 * @return array Notifications
 */
function teeptrak_get_notifications($user_id = null, $unread_only = false, $limit = 20, $offset = 0) {
    global $wpdb;

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $where = $wpdb->prepare("user_id = %d", $user_id);

    if ($unread_only) {
        $where .= " AND is_read = 0";
    }

    $sql = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE $where ORDER BY created_at DESC LIMIT %d OFFSET %d",
        $limit,
        $offset
    );

    $notifications = $wpdb->get_results($sql, ARRAY_A);

    // Parse metadata JSON
    foreach ($notifications as &$notification) {
        if (!empty($notification['metadata'])) {
            $notification['metadata'] = json_decode($notification['metadata'], true);
        }
    }

    return $notifications;
}

/**
 * Mark notification as read
 *
 * @param int $notification_id Notification ID
 * @return bool Success
 */
function teeptrak_mark_notification_read($notification_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $result = $wpdb->update(
        $table_name,
        array('is_read' => 1),
        array('id' => $notification_id),
        array('%d'),
        array('%d')
    );

    return $result !== false;
}

/**
 * Mark all notifications as read for a user
 *
 * @param int $user_id User ID
 * @return bool Success
 */
function teeptrak_mark_all_notifications_read($user_id = null) {
    global $wpdb;

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $result = $wpdb->update(
        $table_name,
        array('is_read' => 1),
        array('user_id' => $user_id, 'is_read' => 0),
        array('%d'),
        array('%d', '%d')
    );

    return $result !== false;
}

/**
 * Get unread notification count
 *
 * @param int $user_id User ID
 * @return int Count
 */
function teeptrak_get_unread_notification_count($user_id = null) {
    global $wpdb;

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    return (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND is_read = 0",
            $user_id
        )
    );
}

/**
 * Delete notification
 *
 * @param int $notification_id Notification ID
 * @return bool Success
 */
function teeptrak_delete_notification($notification_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $result = $wpdb->delete(
        $table_name,
        array('id' => $notification_id),
        array('%d')
    );

    return $result !== false;
}

/**
 * Delete old notifications (cleanup)
 *
 * @param int $days Days to keep notifications
 */
function teeptrak_cleanup_old_notifications($days = 90) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'teeptrak_notifications';

    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $table_name WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY) AND is_read = 1",
            $days
        )
    );
}

/**
 * Schedule notification cleanup
 */
function teeptrak_schedule_notification_cleanup() {
    if (!wp_next_scheduled('teeptrak_notification_cleanup')) {
        wp_schedule_event(time(), 'daily', 'teeptrak_notification_cleanup');
    }
}
add_action('wp', 'teeptrak_schedule_notification_cleanup');

/**
 * Run notification cleanup
 */
function teeptrak_run_notification_cleanup() {
    teeptrak_cleanup_old_notifications(90);
}
add_action('teeptrak_notification_cleanup', 'teeptrak_run_notification_cleanup');

/**
 * Send notification email
 */
function teeptrak_send_notification_email($user_id, $type, $title, $message, $link = '') {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return false;
    }

    $subject = sprintf('[%s] %s', get_bloginfo('name'), $title);

    $email_message = sprintf(
        __("Hello %s,\n\n%s\n\n%s\n\n", 'teeptrak-partner'),
        $user->display_name,
        $title,
        $message
    );

    if ($link) {
        $email_message .= sprintf(__("View details: %s\n\n", 'teeptrak-partner'), $link);
    }

    $email_message .= sprintf(
        __("Best regards,\nThe %s Team", 'teeptrak-partner'),
        get_theme_mod('teeptrak_brand_name', 'TeepTrak')
    );

    return wp_mail($user->user_email, $subject, $email_message);
}

// =============================================================================
// NOTIFICATION TRIGGERS
// =============================================================================

/**
 * Send notification when deal stage changes
 */
function teeptrak_notify_deal_stage_change($deal_id, $old_stage, $new_stage, $user_id) {
    $stages = teeptrak_get_deal_stages();
    $new_stage_label = isset($stages[$new_stage]) ? $stages[$new_stage]['label'] : $new_stage;

    $deals = teeptrak_get_partner_deals($user_id);
    $deal = null;
    foreach ($deals as $d) {
        if ($d['id'] === $deal_id) {
            $deal = $d;
            break;
        }
    }

    $company_name = $deal ? $deal['company_name'] : __('Deal', 'teeptrak-partner');

    teeptrak_send_notification(
        $user_id,
        'deal_update',
        sprintf(__('Deal Updated: %s', 'teeptrak-partner'), $company_name),
        sprintf(__('Your deal has moved to %s stage.', 'teeptrak-partner'), $new_stage_label),
        home_url('/deals/')
    );
}

/**
 * Send notification when commission is earned
 */
function teeptrak_notify_commission_earned($user_id, $amount, $deal_name) {
    teeptrak_send_notification(
        $user_id,
        'commission',
        __('Commission Earned!', 'teeptrak-partner'),
        sprintf(
            __('You earned %s commission from %s.', 'teeptrak-partner'),
            teeptrak_format_currency($amount),
            $deal_name
        ),
        home_url('/commissions/')
    );
}

/**
 * Send notification when payout is processed
 */
function teeptrak_notify_payout_processed($user_id, $amount, $status) {
    $title = $status === 'approved'
        ? __('Payout Approved', 'teeptrak-partner')
        : __('Payout Processed', 'teeptrak-partner');

    teeptrak_send_notification(
        $user_id,
        'payout',
        $title,
        sprintf(
            __('Your payout request of %s has been %s.', 'teeptrak-partner'),
            teeptrak_format_currency($amount),
            $status
        ),
        home_url('/commissions/')
    );
}

/**
 * Send notification when tier upgrades
 */
function teeptrak_notify_tier_upgrade($user_id, $new_tier) {
    $tier_config = teeptrak_get_tier_config($new_tier);

    teeptrak_send_notification(
        $user_id,
        'tier_upgrade',
        __('Congratulations! Tier Upgrade', 'teeptrak-partner'),
        sprintf(
            __('You have been upgraded to %s Partner with %d%% commission rate!', 'teeptrak-partner'),
            $tier_config['name'],
            $tier_config['commission_rate']
        ),
        home_url('/dashboard/')
    );
}

/**
 * Send notification when training module is completed
 */
function teeptrak_notify_training_complete($user_id, $module_id) {
    $modules = teeptrak_get_training_modules();
    $module = null;
    foreach ($modules as $m) {
        if ($m['id'] == $module_id) {
            $module = $m;
            break;
        }
    }

    $module_title = $module ? $module['title'] : __('Training Module', 'teeptrak-partner');

    teeptrak_send_notification(
        $user_id,
        'training',
        __('Training Module Completed', 'teeptrak-partner'),
        sprintf(__('Congratulations! You completed "%s".', 'teeptrak-partner'), $module_title),
        home_url('/training/')
    );
}

/**
 * Send welcome notification to new partners
 */
function teeptrak_notify_welcome($user_id) {
    $user = get_user_by('id', $user_id);
    $brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');

    teeptrak_send_notification(
        $user_id,
        'system',
        sprintf(__('Welcome to %s Partner Portal!', 'teeptrak-partner'), $brand_name),
        __('Start your journey by completing the onboarding steps and training modules.', 'teeptrak-partner'),
        home_url('/dashboard/')
    );
}

// =============================================================================
// AJAX HANDLERS
// =============================================================================

/**
 * AJAX: Get notifications
 */
function teeptrak_ajax_get_notifications() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Not logged in', 'teeptrak-partner')));
    }

    $unread_only = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';
    $limit = isset($_GET['limit']) ? absint($_GET['limit']) : 20;
    $offset = isset($_GET['offset']) ? absint($_GET['offset']) : 0;

    $notifications = teeptrak_get_notifications(get_current_user_id(), $unread_only, $limit, $offset);
    $unread_count = teeptrak_get_unread_notification_count();

    wp_send_json_success(array(
        'notifications' => $notifications,
        'unread_count'  => $unread_count,
    ));
}
add_action('wp_ajax_teeptrak_get_notifications', 'teeptrak_ajax_get_notifications');

/**
 * AJAX: Mark notification as read
 */
function teeptrak_ajax_mark_notification_read() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Not logged in', 'teeptrak-partner')));
    }

    $notification_id = isset($_POST['notification_id']) ? absint($_POST['notification_id']) : 0;

    if (!$notification_id) {
        wp_send_json_error(array('message' => __('Invalid notification ID', 'teeptrak-partner')));
    }

    if (teeptrak_mark_notification_read($notification_id)) {
        wp_send_json_success(array(
            'unread_count' => teeptrak_get_unread_notification_count(),
        ));
    } else {
        wp_send_json_error(array('message' => __('Failed to mark notification as read', 'teeptrak-partner')));
    }
}
add_action('wp_ajax_teeptrak_mark_notification_read', 'teeptrak_ajax_mark_notification_read');

/**
 * AJAX: Mark all notifications as read
 */
function teeptrak_ajax_mark_all_notifications_read() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Not logged in', 'teeptrak-partner')));
    }

    if (teeptrak_mark_all_notifications_read()) {
        wp_send_json_success(array('unread_count' => 0));
    } else {
        wp_send_json_error(array('message' => __('Failed to mark notifications as read', 'teeptrak-partner')));
    }
}
add_action('wp_ajax_teeptrak_mark_all_read', 'teeptrak_ajax_mark_all_notifications_read');
