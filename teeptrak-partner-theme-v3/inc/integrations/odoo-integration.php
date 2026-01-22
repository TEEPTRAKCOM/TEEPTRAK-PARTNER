<?php
/**
 * Odoo CRM Integration for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Odoo configuration
 */
function teeptrak_get_odoo_config() {
    return array(
        'url'      => defined('TEEPTRAK_ODOO_URL') ? TEEPTRAK_ODOO_URL : get_theme_mod('teeptrak_odoo_url'),
        'db'       => defined('TEEPTRAK_ODOO_DB') ? TEEPTRAK_ODOO_DB : get_theme_mod('teeptrak_odoo_db'),
        'api_key'  => defined('TEEPTRAK_ODOO_API_KEY') ? TEEPTRAK_ODOO_API_KEY : get_theme_mod('teeptrak_odoo_api_key'),
        'username' => defined('TEEPTRAK_ODOO_USERNAME') ? TEEPTRAK_ODOO_USERNAME : get_theme_mod('teeptrak_odoo_username'),
    );
}

/**
 * Check if Odoo is configured
 */
function teeptrak_odoo_is_configured() {
    $config = teeptrak_get_odoo_config();
    return !empty($config['url']) && !empty($config['api_key']);
}

/**
 * Make Odoo API request
 */
function teeptrak_odoo_request($endpoint, $method = 'GET', $data = array()) {
    $config = teeptrak_get_odoo_config();

    if (empty($config['url']) || empty($config['api_key'])) {
        return new WP_Error('not_configured', __('Odoo integration is not configured', 'teeptrak-partner'));
    }

    $url = trailingslashit($config['url']) . 'api/v2/' . ltrim($endpoint, '/');

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $config['api_key'],
            'X-Odoo-DB'     => $config['db'],
        ),
        'timeout' => 30,
    );

    if (!empty($data)) {
        $args['body'] = wp_json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        return $response;
    }

    $body = wp_remote_retrieve_body($response);
    $code = wp_remote_retrieve_response_code($response);

    if ($code >= 400) {
        return new WP_Error('api_error', sprintf('Odoo API error: %s', $body), array('status' => $code));
    }

    return json_decode($body, true);
}

/**
 * Sync deal to Odoo
 */
function teeptrak_odoo_sync_deal($user_id, $deal) {
    if (!teeptrak_odoo_is_configured()) {
        return false;
    }

    $partner = teeptrak_get_current_partner();
    $odoo_partner_id = get_user_meta($user_id, 'teeptrak_odoo_partner_id', true);

    if (!$odoo_partner_id) {
        // Create partner in Odoo first
        $odoo_partner_id = teeptrak_odoo_create_partner($user_id);
        if (!$odoo_partner_id) {
            return false;
        }
    }

    // Map deal data to Odoo opportunity
    $odoo_data = array(
        'name'         => $deal['company_name'],
        'partner_id'   => $odoo_partner_id,
        'expected_revenue' => $deal['deal_value'],
        'probability'  => teeptrak_get_stage_probability($deal['stage']),
        'stage_id'     => teeptrak_map_stage_to_odoo($deal['stage']),
        'description'  => sprintf(
            "Partner: %s\nContact: %s\nEmail: %s\nPhone: %s",
            $partner['name'],
            $deal['contact_name'] ?? '',
            $deal['contact_email'] ?? '',
            $deal['contact_phone'] ?? ''
        ),
        'x_teeptrak_deal_id' => $deal['id'],
        'x_teeptrak_protection_end' => $deal['protection_end'],
    );

    if (!empty($deal['expected_close'])) {
        $odoo_data['date_deadline'] = $deal['expected_close'];
    }

    // Check if deal already exists in Odoo
    if (!empty($deal['odoo_id'])) {
        // Update existing opportunity
        $response = teeptrak_odoo_request('crm.lead/' . $deal['odoo_id'], 'PUT', $odoo_data);
    } else {
        // Create new opportunity
        $response = teeptrak_odoo_request('crm.lead', 'POST', $odoo_data);

        if (!is_wp_error($response) && isset($response['id'])) {
            // Save Odoo ID to deal
            $deals = teeptrak_get_partner_deals($user_id);
            foreach ($deals as &$d) {
                if ($d['id'] === $deal['id']) {
                    $d['odoo_id'] = $response['id'];
                    break;
                }
            }
            update_user_meta($user_id, 'teeptrak_deals', $deals);
        }
    }

    if (is_wp_error($response)) {
        error_log('Odoo sync error: ' . $response->get_error_message());
        return false;
    }

    // Update last sync time
    update_user_meta($user_id, 'teeptrak_odoo_last_sync', current_time('mysql'));

    return true;
}

/**
 * Get deal status from Odoo
 */
function teeptrak_odoo_get_deal_status($odoo_id) {
    if (!teeptrak_odoo_is_configured()) {
        return null;
    }

    $response = teeptrak_odoo_request('crm.lead/' . $odoo_id);

    if (is_wp_error($response)) {
        return null;
    }

    return $response;
}

/**
 * Sync partner to Odoo
 */
function teeptrak_odoo_sync_partner($user_id) {
    if (!teeptrak_odoo_is_configured()) {
        return false;
    }

    $partner = array();
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return false;
    }

    $partner_data = array(
        'name'          => $user->display_name,
        'email'         => $user->user_email,
        'is_company'    => false,
        'customer_rank' => 0,
        'supplier_rank' => 1,
        'x_teeptrak_partner_tier' => get_user_meta($user_id, 'teeptrak_partner_tier', true),
        'x_teeptrak_commission_rate' => get_user_meta($user_id, 'teeptrak_commission_rate', true),
        'x_teeptrak_user_id' => $user_id,
    );

    $company = get_user_meta($user_id, 'teeptrak_company', true);
    if ($company) {
        $partner_data['company_name'] = $company;
    }

    $phone = get_user_meta($user_id, 'teeptrak_phone', true);
    if ($phone) {
        $partner_data['phone'] = $phone;
    }

    $odoo_partner_id = get_user_meta($user_id, 'teeptrak_odoo_partner_id', true);

    if ($odoo_partner_id) {
        // Update existing partner
        $response = teeptrak_odoo_request('res.partner/' . $odoo_partner_id, 'PUT', $partner_data);
    } else {
        // Create new partner
        $response = teeptrak_odoo_request('res.partner', 'POST', $partner_data);

        if (!is_wp_error($response) && isset($response['id'])) {
            update_user_meta($user_id, 'teeptrak_odoo_partner_id', $response['id']);
        }
    }

    if (is_wp_error($response)) {
        error_log('Odoo partner sync error: ' . $response->get_error_message());
        return false;
    }

    update_user_meta($user_id, 'teeptrak_odoo_last_sync', current_time('mysql'));

    return true;
}

/**
 * Create partner in Odoo
 */
function teeptrak_odoo_create_partner($user_id) {
    $result = teeptrak_odoo_sync_partner($user_id);

    if ($result) {
        return get_user_meta($user_id, 'teeptrak_odoo_partner_id', true);
    }

    return false;
}

/**
 * Get commissions from Odoo
 */
function teeptrak_odoo_get_commissions($user_id) {
    if (!teeptrak_odoo_is_configured()) {
        return array();
    }

    $odoo_partner_id = get_user_meta($user_id, 'teeptrak_odoo_partner_id', true);

    if (!$odoo_partner_id) {
        return array();
    }

    $response = teeptrak_odoo_request('account.move', 'GET', array(
        'partner_id' => $odoo_partner_id,
        'move_type'  => 'out_invoice',
        'x_teeptrak_commission' => true,
    ));

    if (is_wp_error($response)) {
        return array();
    }

    return $response;
}

/**
 * Map stage to Odoo stage ID
 */
function teeptrak_map_stage_to_odoo($stage) {
    $stage_map = array(
        'registered'     => 1,
        'qualified'      => 2,
        'demo_scheduled' => 3,
        'proposal_sent'  => 4,
        'negotiation'    => 5,
        'closed_won'     => 6,
        'closed_lost'    => 7,
    );

    return isset($stage_map[$stage]) ? $stage_map[$stage] : 1;
}

/**
 * Get stage probability
 */
function teeptrak_get_stage_probability($stage) {
    $probabilities = array(
        'registered'     => 10,
        'qualified'      => 25,
        'demo_scheduled' => 40,
        'proposal_sent'  => 60,
        'negotiation'    => 80,
        'closed_won'     => 100,
        'closed_lost'    => 0,
    );

    return isset($probabilities[$stage]) ? $probabilities[$stage] : 10;
}

/**
 * Bulk sync all deals to Odoo
 */
function teeptrak_odoo_bulk_sync($user_id = null) {
    if (!teeptrak_odoo_is_configured()) {
        return false;
    }

    if ($user_id) {
        $users = array(get_user_by('id', $user_id));
    } else {
        $users = get_users(array(
            'meta_key'   => 'teeptrak_partner_tier',
            'meta_compare' => 'EXISTS',
        ));
    }

    $synced = 0;
    $errors = 0;

    foreach ($users as $user) {
        // Sync partner
        $partner_synced = teeptrak_odoo_sync_partner($user->ID);

        // Sync deals
        $deals = teeptrak_get_partner_deals($user->ID);
        foreach ($deals as $deal) {
            if (teeptrak_odoo_sync_deal($user->ID, $deal)) {
                $synced++;
            } else {
                $errors++;
            }
        }
    }

    return array(
        'synced' => $synced,
        'errors' => $errors,
    );
}

/**
 * Schedule Odoo sync
 */
function teeptrak_schedule_odoo_sync() {
    $frequency = get_theme_mod('teeptrak_odoo_sync_frequency', 'hourly');

    if ($frequency === 'manual') {
        return;
    }

    if (!wp_next_scheduled('teeptrak_odoo_sync_cron')) {
        wp_schedule_event(time(), $frequency, 'teeptrak_odoo_sync_cron');
    }
}
add_action('wp', 'teeptrak_schedule_odoo_sync');

/**
 * Run scheduled Odoo sync
 */
function teeptrak_run_odoo_sync() {
    teeptrak_odoo_bulk_sync();
}
add_action('teeptrak_odoo_sync_cron', 'teeptrak_run_odoo_sync');

/**
 * Sync deal on registration
 */
function teeptrak_sync_deal_on_register($deal_id, $deal, $user_id) {
    $frequency = get_theme_mod('teeptrak_odoo_sync_frequency', 'hourly');

    if ($frequency === 'realtime') {
        teeptrak_odoo_sync_deal($user_id, $deal);
    }
}
add_action('teeptrak_deal_registered', 'teeptrak_sync_deal_on_register', 10, 3);

/**
 * Sync deal on update
 */
function teeptrak_sync_deal_on_update($deal_id, $deal, $old_deal, $user_id) {
    $frequency = get_theme_mod('teeptrak_odoo_sync_frequency', 'hourly');

    if ($frequency === 'realtime') {
        teeptrak_odoo_sync_deal($user_id, $deal);
    }
}
add_action('teeptrak_deal_updated', 'teeptrak_sync_deal_on_update', 10, 4);

/**
 * AJAX: Manual Odoo sync
 */
function teeptrak_ajax_odoo_sync() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'teeptrak-partner')));
    }

    $result = teeptrak_odoo_bulk_sync();

    wp_send_json_success(array(
        'message' => sprintf(
            __('Sync completed: %d synced, %d errors', 'teeptrak-partner'),
            $result['synced'],
            $result['errors']
        ),
        'result' => $result,
    ));
}
add_action('wp_ajax_teeptrak_odoo_sync', 'teeptrak_ajax_odoo_sync');

/**
 * AJAX: Test Odoo connection
 */
function teeptrak_ajax_test_odoo_connection() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'teeptrak-partner')));
    }

    $response = teeptrak_odoo_request('res.users/me');

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => $response->get_error_message()));
    }

    wp_send_json_success(array(
        'message' => __('Connection successful', 'teeptrak-partner'),
        'user'    => $response,
    ));
}
add_action('wp_ajax_teeptrak_test_odoo', 'teeptrak_ajax_test_odoo_connection');
