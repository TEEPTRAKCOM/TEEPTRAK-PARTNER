<?php
/**
 * Webhooks Handler for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register webhook endpoints
 */
function teeptrak_register_webhook_routes() {
    // Odoo webhook endpoint
    register_rest_route('teeptrak/v2', '/webhooks/odoo', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_handle_odoo_webhook',
        'permission_callback' => 'teeptrak_verify_odoo_webhook',
    ));

    // Generic webhook endpoint
    register_rest_route('teeptrak/v2', '/webhooks/(?P<source>[a-z]+)', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_handle_generic_webhook',
        'permission_callback' => 'teeptrak_verify_webhook_signature',
    ));
}
add_action('rest_api_init', 'teeptrak_register_webhook_routes');

/**
 * Verify Odoo webhook
 */
function teeptrak_verify_odoo_webhook($request) {
    $secret = defined('TEEPTRAK_ODOO_WEBHOOK_SECRET')
        ? TEEPTRAK_ODOO_WEBHOOK_SECRET
        : get_theme_mod('teeptrak_odoo_webhook_secret');

    if (empty($secret)) {
        // If no secret is configured, allow all (development mode)
        return true;
    }

    $signature = $request->get_header('X-Odoo-Signature');
    if (empty($signature)) {
        return false;
    }

    $payload = $request->get_body();
    $expected = hash_hmac('sha256', $payload, $secret);

    return hash_equals($expected, $signature);
}

/**
 * Verify generic webhook signature
 */
function teeptrak_verify_webhook_signature($request) {
    $source = $request->get_param('source');
    $secret_key = 'teeptrak_' . $source . '_webhook_secret';

    $secret = defined(strtoupper($secret_key)) ? constant(strtoupper($secret_key)) : get_option($secret_key);

    if (empty($secret)) {
        return true;
    }

    $signature = $request->get_header('X-Webhook-Signature');
    if (empty($signature)) {
        return false;
    }

    $payload = $request->get_body();
    $expected = hash_hmac('sha256', $payload, $secret);

    return hash_equals($expected, $signature);
}

/**
 * Handle Odoo webhook
 */
function teeptrak_handle_odoo_webhook($request) {
    $params = $request->get_json_params();

    if (empty($params['event'])) {
        return new WP_Error('invalid_payload', 'Missing event type', array('status' => 400));
    }

    $event = sanitize_text_field($params['event']);
    $data = $params['data'] ?? array();

    // Log webhook
    teeptrak_log_webhook('odoo', $event, $data);

    switch ($event) {
        case 'deal.stage_changed':
            return teeptrak_process_odoo_deal_stage_change($data);

        case 'deal.won':
            return teeptrak_process_odoo_deal_won($data);

        case 'deal.lost':
            return teeptrak_process_odoo_deal_lost($data);

        case 'commission.approved':
            return teeptrak_process_odoo_commission_approved($data);

        case 'payout.processed':
            return teeptrak_process_odoo_payout_processed($data);

        case 'partner.updated':
            return teeptrak_process_odoo_partner_updated($data);

        default:
            do_action('teeptrak_odoo_webhook_' . $event, $data);
            return rest_ensure_response(array('success' => true));
    }
}

/**
 * Handle generic webhook
 */
function teeptrak_handle_generic_webhook($request) {
    $source = $request->get_param('source');
    $params = $request->get_json_params();

    // Log webhook
    teeptrak_log_webhook($source, $params['event'] ?? 'unknown', $params);

    // Fire action for custom handlers
    do_action('teeptrak_webhook_' . $source, $params);

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo deal stage change
 */
function teeptrak_process_odoo_deal_stage_change($data) {
    $odoo_deal_id = $data['deal_id'] ?? null;
    $new_stage = $data['stage'] ?? null;
    $partner_odoo_id = $data['partner_id'] ?? null;

    if (!$odoo_deal_id || !$new_stage || !$partner_odoo_id) {
        return new WP_Error('invalid_data', 'Missing required fields', array('status' => 400));
    }

    // Find WordPress user by Odoo partner ID
    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    // Map Odoo stage to our stage
    $stage_map = array(
        'new'         => 'registered',
        'qualified'   => 'qualified',
        'proposition' => 'proposal_sent',
        'negotiation' => 'negotiation',
        'won'         => 'closed_won',
        'lost'        => 'closed_lost',
    );

    $wp_stage = isset($stage_map[$new_stage]) ? $stage_map[$new_stage] : $new_stage;

    // Find deal by Odoo ID
    $deal_id = teeptrak_get_deal_by_odoo_id($user_id, $odoo_deal_id);
    if ($deal_id) {
        teeptrak_update_deal_stage($user_id, $deal_id, $wp_stage);
    }

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo deal won
 */
function teeptrak_process_odoo_deal_won($data) {
    $odoo_deal_id = $data['deal_id'] ?? null;
    $partner_odoo_id = $data['partner_id'] ?? null;
    $deal_value = floatval($data['value'] ?? 0);

    if (!$odoo_deal_id || !$partner_odoo_id) {
        return new WP_Error('invalid_data', 'Missing required fields', array('status' => 400));
    }

    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    $deal_id = teeptrak_get_deal_by_odoo_id($user_id, $odoo_deal_id);
    if ($deal_id) {
        $deals = teeptrak_get_partner_deals($user_id);
        foreach ($deals as &$deal) {
            if ($deal['id'] === $deal_id) {
                $deal['stage'] = 'closed_won';
                if ($deal_value > 0) {
                    $deal['deal_value'] = $deal_value;
                }
                break;
            }
        }
        update_user_meta($user_id, 'teeptrak_deals', $deals);

        // Process commission
        foreach ($deals as $deal) {
            if ($deal['id'] === $deal_id) {
                teeptrak_process_deal_commission($user_id, $deal);
                break;
            }
        }
    }

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo deal lost
 */
function teeptrak_process_odoo_deal_lost($data) {
    $odoo_deal_id = $data['deal_id'] ?? null;
    $partner_odoo_id = $data['partner_id'] ?? null;

    if (!$odoo_deal_id || !$partner_odoo_id) {
        return new WP_Error('invalid_data', 'Missing required fields', array('status' => 400));
    }

    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    $deal_id = teeptrak_get_deal_by_odoo_id($user_id, $odoo_deal_id);
    if ($deal_id) {
        teeptrak_update_deal_stage($user_id, $deal_id, 'closed_lost');
    }

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo commission approved
 */
function teeptrak_process_odoo_commission_approved($data) {
    $partner_odoo_id = $data['partner_id'] ?? null;
    $amount = floatval($data['amount'] ?? 0);
    $transaction_id = $data['transaction_id'] ?? null;

    if (!$partner_odoo_id || $amount <= 0) {
        return new WP_Error('invalid_data', 'Missing required fields', array('status' => 400));
    }

    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    // Move from pending to available
    $pending = (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true) ?: 0;
    $available = (float) get_user_meta($user_id, 'teeptrak_available_balance', true) ?: 0;

    $new_pending = max(0, $pending - $amount);
    $new_available = $available + $amount;

    update_user_meta($user_id, 'teeptrak_pending_commissions', $new_pending);
    update_user_meta($user_id, 'teeptrak_available_balance', $new_available);

    // Update transaction status
    if ($transaction_id) {
        $transactions = teeptrak_get_partner_transactions($user_id);
        foreach ($transactions as &$txn) {
            if (isset($txn['odoo_id']) && $txn['odoo_id'] === $transaction_id) {
                $txn['status'] = 'approved';
                break;
            }
        }
        update_user_meta($user_id, 'teeptrak_transactions', $transactions);
    }

    // Send notification
    teeptrak_send_notification(
        $user_id,
        'commission',
        __('Commission Approved', 'teeptrak-partner'),
        sprintf(__('Your commission of %s has been approved and is now available.', 'teeptrak-partner'), teeptrak_format_currency($amount)),
        home_url('/commissions/')
    );

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo payout processed
 */
function teeptrak_process_odoo_payout_processed($data) {
    $partner_odoo_id = $data['partner_id'] ?? null;
    $amount = floatval($data['amount'] ?? 0);
    $status = $data['status'] ?? 'processed';

    if (!$partner_odoo_id || $amount <= 0) {
        return new WP_Error('invalid_data', 'Missing required fields', array('status' => 400));
    }

    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    // Update total paid
    $total_paid = (float) get_user_meta($user_id, 'teeptrak_total_paid', true) ?: 0;
    update_user_meta($user_id, 'teeptrak_total_paid', $total_paid + $amount);

    // Notify partner
    teeptrak_notify_payout_processed($user_id, $amount, $status);

    return rest_ensure_response(array('success' => true));
}

/**
 * Process Odoo partner updated
 */
function teeptrak_process_odoo_partner_updated($data) {
    $partner_odoo_id = $data['partner_id'] ?? null;

    if (!$partner_odoo_id) {
        return new WP_Error('invalid_data', 'Missing partner_id', array('status' => 400));
    }

    $user_id = teeptrak_get_user_by_odoo_id($partner_odoo_id);
    if (!$user_id) {
        return new WP_Error('user_not_found', 'Partner not found', array('status' => 404));
    }

    // Update allowed fields
    $field_map = array(
        'tier'            => 'teeptrak_partner_tier',
        'commission_rate' => 'teeptrak_commission_rate',
        'company'         => 'teeptrak_company',
        'phone'           => 'teeptrak_phone',
        'country'         => 'teeptrak_country',
    );

    foreach ($field_map as $odoo_field => $wp_field) {
        if (isset($data[$odoo_field])) {
            update_user_meta($user_id, $wp_field, sanitize_text_field($data[$odoo_field]));
        }
    }

    // Check for tier upgrade
    if (isset($data['tier'])) {
        $old_tier = get_user_meta($user_id, 'teeptrak_partner_tier', true);
        if ($old_tier !== $data['tier']) {
            teeptrak_notify_tier_upgrade($user_id, $data['tier']);
        }
    }

    return rest_ensure_response(array('success' => true));
}

/**
 * Get user ID by Odoo partner ID
 */
function teeptrak_get_user_by_odoo_id($odoo_id) {
    global $wpdb;

    $user_id = $wpdb->get_var($wpdb->prepare(
        "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'teeptrak_odoo_partner_id' AND meta_value = %s",
        $odoo_id
    ));

    return $user_id ? (int) $user_id : null;
}

/**
 * Get deal ID by Odoo deal ID
 */
function teeptrak_get_deal_by_odoo_id($user_id, $odoo_deal_id) {
    $deals = teeptrak_get_partner_deals($user_id);

    foreach ($deals as $deal) {
        if (isset($deal['odoo_id']) && $deal['odoo_id'] === $odoo_deal_id) {
            return $deal['id'];
        }
    }

    return null;
}

/**
 * Log webhook
 */
function teeptrak_log_webhook($source, $event, $data) {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }

    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'source'    => $source,
        'event'     => $event,
        'data'      => $data,
    );

    $log = get_option('teeptrak_webhook_log', array());
    array_unshift($log, $log_entry);

    // Keep only last 100 entries
    $log = array_slice($log, 0, 100);

    update_option('teeptrak_webhook_log', $log);
}
