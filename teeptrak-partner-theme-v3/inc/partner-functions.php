<?php
/**
 * Partner Functions for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Update partner profile
 */
function teeptrak_update_partner_profile($user_id, $data) {
    $allowed_fields = array(
        'first_name',
        'last_name',
        'teeptrak_company',
        'teeptrak_phone',
        'teeptrak_country',
        'teeptrak_locale',
        'teeptrak_email_notifications',
    );

    foreach ($data as $key => $value) {
        if (in_array($key, array('first_name', 'last_name'))) {
            wp_update_user(array(
                'ID' => $user_id,
                $key => sanitize_text_field($value),
            ));
        } elseif (in_array($key, $allowed_fields)) {
            update_user_meta($user_id, $key, sanitize_text_field($value));
        }
    }

    do_action('teeptrak_partner_profile_updated', $user_id, $data);

    return true;
}

/**
 * Calculate and update partner tier
 */
function teeptrak_calculate_partner_tier($user_id) {
    $deals = teeptrak_get_partner_deals($user_id);
    $stats = teeptrak_get_deal_stats($user_id);
    $training = teeptrak_get_training_progress($user_id);

    $won_deals = $stats['won'];
    $pipeline_value = $stats['pipeline_value'] + $stats['won_value'];

    $tiers = teeptrak_get_tier_config();
    $new_tier = 'bronze';

    // Check tier requirements (highest first)
    if ($won_deals >= 10 && $pipeline_value >= 250000) {
        $new_tier = 'platinum';
    } elseif ($won_deals >= 5 && $pipeline_value >= 100000) {
        $new_tier = 'gold';
    } elseif ($won_deals >= 2 && $pipeline_value >= 50000) {
        $new_tier = 'silver';
    }

    $current_tier = get_user_meta($user_id, 'teeptrak_partner_tier', true) ?: 'bronze';

    if ($new_tier !== $current_tier) {
        update_user_meta($user_id, 'teeptrak_partner_tier', $new_tier);
        update_user_meta($user_id, 'teeptrak_commission_rate', $tiers[$new_tier]['commission_rate']);

        // Check if upgrade
        $tier_levels = array('bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4);
        if ($tier_levels[$new_tier] > $tier_levels[$current_tier]) {
            teeptrak_notify_tier_upgrade($user_id, $new_tier);
        }

        do_action('teeptrak_partner_tier_changed', $user_id, $current_tier, $new_tier);
    }

    return $new_tier;
}

/**
 * Calculate partner score
 */
function teeptrak_calculate_partner_score($user_id) {
    $stats = teeptrak_get_deal_stats($user_id);
    $training = teeptrak_get_training_progress($user_id);

    $score = 0;

    // Deal activity (up to 40 points)
    $score += min(40, $stats['active'] * 8);

    // Won deals (up to 30 points)
    $score += min(30, $stats['won'] * 6);

    // Training progress (up to 20 points)
    $score += round($training['percent'] * 0.2);

    // Profile completeness (up to 10 points)
    $partner = teeptrak_get_current_partner();
    $profile_fields = array('company', 'phone', 'country');
    $completed_fields = 0;
    foreach ($profile_fields as $field) {
        if (!empty($partner[$field])) {
            $completed_fields++;
        }
    }
    $score += round(($completed_fields / count($profile_fields)) * 10);

    $score = min(100, max(0, $score));

    update_user_meta($user_id, 'teeptrak_partner_score', $score);

    return $score;
}

/**
 * Register a new deal
 */
function teeptrak_register_deal($user_id, $deal_data) {
    $deals = teeptrak_get_partner_deals($user_id);

    // Generate deal ID
    $deal_id = 'deal_' . uniqid();
    $protection_end = date('Y-m-d', strtotime('+90 days'));

    $new_deal = array(
        'id'              => $deal_id,
        'company_name'    => sanitize_text_field($deal_data['company_name'] ?? ''),
        'industry'        => sanitize_text_field($deal_data['industry'] ?? ''),
        'company_size'    => sanitize_text_field($deal_data['company_size'] ?? ''),
        'num_plants'      => absint($deal_data['num_plants'] ?? 1),
        'contact_name'    => sanitize_text_field($deal_data['contact_name'] ?? ''),
        'contact_title'   => sanitize_text_field($deal_data['contact_title'] ?? ''),
        'contact_email'   => sanitize_email($deal_data['contact_email'] ?? ''),
        'contact_phone'   => sanitize_text_field($deal_data['contact_phone'] ?? ''),
        'deal_value'      => floatval($deal_data['deal_value'] ?? 0),
        'expected_close'  => sanitize_text_field($deal_data['expected_close'] ?? ''),
        'notes'           => array(),
        'stage'           => 'registered',
        'protection_end'  => $protection_end,
        'created_at'      => current_time('Y-m-d H:i:s'),
        'updated_at'      => current_time('Y-m-d H:i:s'),
    );

    // Add initial note if provided
    if (!empty($deal_data['notes'])) {
        $new_deal['notes'][] = array(
            'id'         => 'note_' . uniqid(),
            'content'    => sanitize_textarea_field($deal_data['notes']),
            'created_at' => current_time('Y-m-d H:i:s'),
            'user_id'    => $user_id,
        );
    }

    $deals[] = $new_deal;
    update_user_meta($user_id, 'teeptrak_deals', $deals);

    // Update onboarding step if needed
    $current_step = (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true);
    if ($current_step < 5) {
        update_user_meta($user_id, 'teeptrak_onboarding_step', 5);
    }

    // Recalculate score and tier
    teeptrak_calculate_partner_score($user_id);
    teeptrak_calculate_partner_tier($user_id);

    do_action('teeptrak_deal_registered', $deal_id, $new_deal, $user_id);

    return $new_deal;
}

/**
 * Update deal
 */
function teeptrak_update_deal($user_id, $deal_id, $deal_data) {
    $deals = teeptrak_get_partner_deals($user_id);
    $deal_index = null;
    $old_deal = null;

    foreach ($deals as $index => $deal) {
        if ($deal['id'] === $deal_id) {
            $deal_index = $index;
            $old_deal = $deal;
            break;
        }
    }

    if ($deal_index === null) {
        return new WP_Error('not_found', __('Deal not found', 'teeptrak-partner'));
    }

    // Update allowed fields
    $allowed_fields = array(
        'company_name', 'industry', 'company_size', 'num_plants',
        'contact_name', 'contact_title', 'contact_email', 'contact_phone',
        'deal_value', 'expected_close', 'stage',
    );

    foreach ($allowed_fields as $field) {
        if (isset($deal_data[$field])) {
            if ($field === 'deal_value') {
                $deals[$deal_index][$field] = floatval($deal_data[$field]);
            } elseif ($field === 'num_plants') {
                $deals[$deal_index][$field] = absint($deal_data[$field]);
            } elseif ($field === 'contact_email') {
                $deals[$deal_index][$field] = sanitize_email($deal_data[$field]);
            } else {
                $deals[$deal_index][$field] = sanitize_text_field($deal_data[$field]);
            }
        }
    }

    $deals[$deal_index]['updated_at'] = current_time('Y-m-d H:i:s');

    update_user_meta($user_id, 'teeptrak_deals', $deals);

    // Check for stage change
    if (isset($deal_data['stage']) && $old_deal['stage'] !== $deal_data['stage']) {
        teeptrak_notify_deal_stage_change($deal_id, $old_deal['stage'], $deal_data['stage'], $user_id);

        // If closed won, process commission
        if ($deal_data['stage'] === 'closed_won' && $old_deal['stage'] !== 'closed_won') {
            teeptrak_process_deal_commission($user_id, $deals[$deal_index]);
        }
    }

    // Recalculate score and tier
    teeptrak_calculate_partner_score($user_id);
    teeptrak_calculate_partner_tier($user_id);

    do_action('teeptrak_deal_updated', $deal_id, $deals[$deal_index], $old_deal, $user_id);

    return $deals[$deal_index];
}

/**
 * Update deal stage
 */
function teeptrak_update_deal_stage($user_id, $deal_id, $new_stage) {
    return teeptrak_update_deal($user_id, $deal_id, array('stage' => $new_stage));
}

/**
 * Add note to deal
 */
function teeptrak_add_deal_note($user_id, $deal_id, $note_content) {
    $deals = teeptrak_get_partner_deals($user_id);
    $deal_index = null;

    foreach ($deals as $index => $deal) {
        if ($deal['id'] === $deal_id) {
            $deal_index = $index;
            break;
        }
    }

    if ($deal_index === null) {
        return new WP_Error('not_found', __('Deal not found', 'teeptrak-partner'));
    }

    $note = array(
        'id'         => 'note_' . uniqid(),
        'content'    => sanitize_textarea_field($note_content),
        'created_at' => current_time('Y-m-d H:i:s'),
        'user_id'    => $user_id,
    );

    if (!isset($deals[$deal_index]['notes']) || !is_array($deals[$deal_index]['notes'])) {
        $deals[$deal_index]['notes'] = array();
    }

    $deals[$deal_index]['notes'][] = $note;
    $deals[$deal_index]['updated_at'] = current_time('Y-m-d H:i:s');

    update_user_meta($user_id, 'teeptrak_deals', $deals);

    return $note;
}

/**
 * Delete deal (soft delete)
 */
function teeptrak_delete_deal($user_id, $deal_id) {
    $deals = teeptrak_get_partner_deals($user_id);
    $new_deals = array();
    $deleted_deal = null;

    foreach ($deals as $deal) {
        if ($deal['id'] === $deal_id) {
            $deleted_deal = $deal;
        } else {
            $new_deals[] = $deal;
        }
    }

    if (!$deleted_deal) {
        return new WP_Error('not_found', __('Deal not found', 'teeptrak-partner'));
    }

    update_user_meta($user_id, 'teeptrak_deals', $new_deals);

    // Recalculate score and tier
    teeptrak_calculate_partner_score($user_id);
    teeptrak_calculate_partner_tier($user_id);

    do_action('teeptrak_deal_deleted', $deal_id, $deleted_deal, $user_id);

    return true;
}

/**
 * Process commission when deal is won
 */
function teeptrak_process_deal_commission($user_id, $deal) {
    $commission_rate = (int) get_user_meta($user_id, 'teeptrak_commission_rate', true) ?: 15;
    $deal_value = floatval($deal['deal_value']);
    $commission_amount = $deal_value * ($commission_rate / 100);

    // Add transaction
    $transactions = teeptrak_get_partner_transactions($user_id);
    $transactions[] = array(
        'id'          => 'txn_' . uniqid(),
        'date'        => current_time('Y-m-d'),
        'type'        => 'commission',
        'description' => sprintf('%s - %s', $deal['company_name'], $deal['id']),
        'amount'      => $commission_amount,
        'status'      => 'pending',
        'deal_id'     => $deal['id'],
    );
    update_user_meta($user_id, 'teeptrak_transactions', $transactions);

    // Update pending commissions
    $pending = (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true) ?: 0;
    update_user_meta($user_id, 'teeptrak_pending_commissions', $pending + $commission_amount);

    // Update total earned
    $total_earned = (float) get_user_meta($user_id, 'teeptrak_total_earned', true) ?: 0;
    update_user_meta($user_id, 'teeptrak_total_earned', $total_earned + $commission_amount);

    // Update onboarding step if first close
    $current_step = (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true);
    if ($current_step < 7) {
        update_user_meta($user_id, 'teeptrak_onboarding_step', 7);
    }

    // Send notification
    teeptrak_notify_commission_earned($user_id, $commission_amount, $deal['company_name']);

    do_action('teeptrak_commission_earned', $user_id, $commission_amount, $deal);

    return $commission_amount;
}

/**
 * Request payout
 */
function teeptrak_request_payout($user_id, $amount, $method = 'sepa') {
    $available_balance = (float) get_user_meta($user_id, 'teeptrak_available_balance', true) ?: 0;

    if ($amount > $available_balance) {
        return new WP_Error('insufficient_balance', __('Insufficient balance', 'teeptrak-partner'));
    }

    // Add withdrawal transaction
    $transactions = teeptrak_get_partner_transactions($user_id);
    $transactions[] = array(
        'id'          => 'txn_' . uniqid(),
        'date'        => current_time('Y-m-d'),
        'type'        => 'withdrawal',
        'description' => sprintf(__('Payout request (%s)', 'teeptrak-partner'), strtoupper($method)),
        'amount'      => -$amount,
        'status'      => 'pending',
        'method'      => $method,
    );
    update_user_meta($user_id, 'teeptrak_transactions', $transactions);

    // Update available balance
    update_user_meta($user_id, 'teeptrak_available_balance', $available_balance - $amount);

    do_action('teeptrak_payout_requested', $user_id, $amount, $method);

    return true;
}

/**
 * Calculate commission forecast
 */
function teeptrak_calculate_commission_forecast($user_id) {
    $deals = teeptrak_get_partner_deals($user_id);
    $commission_rate = (int) get_user_meta($user_id, 'teeptrak_commission_rate', true) ?: 15;

    $forecast = array(
        'total'   => 0,
        'by_stage' => array(),
    );

    // Probability by stage
    $stage_probability = array(
        'registered'     => 0.10,
        'qualified'      => 0.25,
        'demo_scheduled' => 0.40,
        'proposal_sent'  => 0.60,
        'negotiation'    => 0.80,
    );

    foreach ($deals as $deal) {
        $stage = $deal['stage'] ?? 'registered';

        if (in_array($stage, array('closed_won', 'closed_lost'))) {
            continue;
        }

        $probability = isset($stage_probability[$stage]) ? $stage_probability[$stage] : 0.10;
        $deal_value = floatval($deal['deal_value'] ?? 0);
        $expected_commission = $deal_value * ($commission_rate / 100) * $probability;

        $forecast['total'] += $expected_commission;

        if (!isset($forecast['by_stage'][$stage])) {
            $forecast['by_stage'][$stage] = 0;
        }
        $forecast['by_stage'][$stage] += $expected_commission;
    }

    return $forecast;
}

/**
 * Export deals to CSV
 */
function teeptrak_export_deals_csv($user_id) {
    $deals = teeptrak_get_partner_deals($user_id);
    $stages = teeptrak_get_deal_stages();

    $csv_lines = array();
    $csv_lines[] = array(
        'ID',
        'Company Name',
        'Industry',
        'Contact Name',
        'Contact Email',
        'Contact Phone',
        'Deal Value',
        'Stage',
        'Protection End',
        'Created At',
    );

    foreach ($deals as $deal) {
        $stage_label = isset($stages[$deal['stage']]) ? $stages[$deal['stage']]['label'] : $deal['stage'];

        $csv_lines[] = array(
            $deal['id'],
            $deal['company_name'],
            $deal['industry'] ?? '',
            $deal['contact_name'] ?? '',
            $deal['contact_email'] ?? '',
            $deal['contact_phone'] ?? '',
            $deal['deal_value'] ?? 0,
            $stage_label,
            $deal['protection_end'] ?? '',
            $deal['created_at'] ?? '',
        );
    }

    return $csv_lines;
}

/**
 * Get monthly deal statistics for charts
 */
function teeptrak_get_monthly_deal_stats($user_id, $months = 6) {
    $deals = teeptrak_get_partner_deals($user_id);
    $stats = array();

    // Initialize months
    for ($i = $months - 1; $i >= 0; $i--) {
        $month_key = date('Y-m', strtotime("-$i months"));
        $stats[$month_key] = array(
            'label'    => date('M Y', strtotime("-$i months")),
            'deals'    => 0,
            'value'    => 0,
            'won'      => 0,
            'won_value' => 0,
        );
    }

    foreach ($deals as $deal) {
        $created_month = date('Y-m', strtotime($deal['created_at'] ?? ''));

        if (isset($stats[$created_month])) {
            $stats[$created_month]['deals']++;
            $stats[$created_month]['value'] += floatval($deal['deal_value'] ?? 0);

            if ($deal['stage'] === 'closed_won') {
                $stats[$created_month]['won']++;
                $stats[$created_month]['won_value'] += floatval($deal['deal_value'] ?? 0);
            }
        }
    }

    return array_values($stats);
}

// =============================================================================
// AJAX HANDLERS
// =============================================================================

/**
 * AJAX: Register deal
 */
function teeptrak_ajax_register_deal() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $user_id = get_current_user_id();

    if (empty($_POST['company_name'])) {
        wp_send_json_error(array('message' => __('Company name is required', 'teeptrak-partner')));
    }

    $deal = teeptrak_register_deal($user_id, $_POST);

    wp_send_json_success(array(
        'deal'    => $deal,
        'message' => __('Deal registered successfully! Your 90-day protection starts now.', 'teeptrak-partner'),
    ));
}
add_action('wp_ajax_teeptrak_register_deal', 'teeptrak_ajax_register_deal');

/**
 * AJAX: Update deal stage
 */
function teeptrak_ajax_update_deal_stage() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $deal_id = sanitize_text_field($_POST['deal_id'] ?? '');
    $new_stage = sanitize_text_field($_POST['stage'] ?? '');

    if (empty($deal_id) || empty($new_stage)) {
        wp_send_json_error(array('message' => __('Invalid request', 'teeptrak-partner')));
    }

    $result = teeptrak_update_deal_stage(get_current_user_id(), $deal_id, $new_stage);

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array(
        'deal'    => $result,
        'message' => __('Deal stage updated', 'teeptrak-partner'),
    ));
}
add_action('wp_ajax_teeptrak_update_deal_stage', 'teeptrak_ajax_update_deal_stage');

/**
 * AJAX: Add deal note
 */
function teeptrak_ajax_add_deal_note() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $deal_id = sanitize_text_field($_POST['deal_id'] ?? '');
    $note_content = sanitize_textarea_field($_POST['note'] ?? '');

    if (empty($deal_id) || empty($note_content)) {
        wp_send_json_error(array('message' => __('Invalid request', 'teeptrak-partner')));
    }

    $result = teeptrak_add_deal_note(get_current_user_id(), $deal_id, $note_content);

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array(
        'note'    => $result,
        'message' => __('Note added successfully', 'teeptrak-partner'),
    ));
}
add_action('wp_ajax_teeptrak_add_deal_note', 'teeptrak_ajax_add_deal_note');

/**
 * AJAX: Export deals
 */
function teeptrak_ajax_export_deals() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $csv_data = teeptrak_export_deals_csv(get_current_user_id());

    // Generate CSV content
    $output = fopen('php://temp', 'r+');
    foreach ($csv_data as $row) {
        fputcsv($output, $row);
    }
    rewind($output);
    $csv_content = stream_get_contents($output);
    fclose($output);

    wp_send_json_success(array(
        'csv'      => $csv_content,
        'filename' => 'teeptrak-deals-' . date('Y-m-d') . '.csv',
    ));
}
add_action('wp_ajax_teeptrak_export_deals', 'teeptrak_ajax_export_deals');

/**
 * AJAX: Request payout
 */
function teeptrak_ajax_request_payout() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in', 'teeptrak-partner')));
    }

    $amount = floatval($_POST['amount'] ?? 0);
    $method = sanitize_text_field($_POST['method'] ?? 'sepa');

    if ($amount <= 0) {
        wp_send_json_error(array('message' => __('Invalid amount', 'teeptrak-partner')));
    }

    $result = teeptrak_request_payout(get_current_user_id(), $amount, $method);

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array(
        'message' => __('Payout request submitted successfully', 'teeptrak-partner'),
    ));
}
add_action('wp_ajax_teeptrak_request_payout', 'teeptrak_ajax_request_payout');
