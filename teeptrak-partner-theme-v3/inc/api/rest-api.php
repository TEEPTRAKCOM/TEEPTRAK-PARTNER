<?php
/**
 * REST API v2 for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API routes
 */
function teeptrak_register_rest_routes() {
    $namespace = 'teeptrak/v2';

    // =============================================================================
    // PARTNER ENDPOINTS
    // =============================================================================

    // GET /partner - Get current partner data
    register_rest_route($namespace, '/partner', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_partner',
        'permission_callback' => 'is_user_logged_in',
    ));

    // PUT /partner - Update partner profile
    register_rest_route($namespace, '/partner', array(
        'methods'             => 'PUT',
        'callback'            => 'teeptrak_rest_update_partner',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /partner/stats - Get partner statistics
    register_rest_route($namespace, '/partner/stats', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_partner_stats',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /partner/notifications - Get partner notifications
    register_rest_route($namespace, '/partner/notifications', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_notifications',
        'permission_callback' => 'is_user_logged_in',
    ));

    // POST /partner/notifications/read - Mark notifications as read
    register_rest_route($namespace, '/partner/notifications/read', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_rest_mark_notifications_read',
        'permission_callback' => 'is_user_logged_in',
    ));

    // =============================================================================
    // DEAL ENDPOINTS
    // =============================================================================

    // GET /deals - Get all deals
    register_rest_route($namespace, '/deals', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_deals',
        'permission_callback' => 'is_user_logged_in',
    ));

    // POST /deals - Create new deal
    register_rest_route($namespace, '/deals', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_rest_create_deal',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /deals/{id} - Get single deal
    register_rest_route($namespace, '/deals/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_deal',
        'permission_callback' => 'is_user_logged_in',
    ));

    // PUT /deals/{id} - Update deal
    register_rest_route($namespace, '/deals/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'PUT',
        'callback'            => 'teeptrak_rest_update_deal',
        'permission_callback' => 'is_user_logged_in',
    ));

    // DELETE /deals/{id} - Delete deal
    register_rest_route($namespace, '/deals/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'DELETE',
        'callback'            => 'teeptrak_rest_delete_deal',
        'permission_callback' => 'is_user_logged_in',
    ));

    // PUT /deals/{id}/stage - Update deal stage
    register_rest_route($namespace, '/deals/(?P<id>[a-zA-Z0-9_-]+)/stage', array(
        'methods'             => 'PUT',
        'callback'            => 'teeptrak_rest_update_deal_stage',
        'permission_callback' => 'is_user_logged_in',
    ));

    // POST /deals/{id}/notes - Add note to deal
    register_rest_route($namespace, '/deals/(?P<id>[a-zA-Z0-9_-]+)/notes', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_rest_add_deal_note',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /deals/export - Export deals
    register_rest_route($namespace, '/deals/export', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_export_deals',
        'permission_callback' => 'is_user_logged_in',
    ));

    // =============================================================================
    // COMMISSION ENDPOINTS
    // =============================================================================

    // GET /commissions - Get commission data
    register_rest_route($namespace, '/commissions', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_commissions',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /commissions/forecast - Get commission forecast
    register_rest_route($namespace, '/commissions/forecast', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_commission_forecast',
        'permission_callback' => 'is_user_logged_in',
    ));

    // POST /commissions/payout-request - Request payout
    register_rest_route($namespace, '/commissions/payout-request', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_rest_request_payout',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /commissions/statements - Get commission statements
    register_rest_route($namespace, '/commissions/statements', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_statements',
        'permission_callback' => 'is_user_logged_in',
    ));

    // =============================================================================
    // TRAINING ENDPOINTS
    // =============================================================================

    // GET /training/progress - Get training progress
    register_rest_route($namespace, '/training/progress', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_training_progress',
        'permission_callback' => 'is_user_logged_in',
    ));

    // PUT /training/progress/{module_id} - Update module progress
    register_rest_route($namespace, '/training/progress/(?P<module_id>\d+)', array(
        'methods'             => 'PUT',
        'callback'            => 'teeptrak_rest_update_training_progress',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /training/certificates - Get certificates
    register_rest_route($namespace, '/training/certificates', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_certificates',
        'permission_callback' => 'is_user_logged_in',
    ));

    // =============================================================================
    // RESOURCE ENDPOINTS
    // =============================================================================

    // GET /resources - Get resources
    register_rest_route($namespace, '/resources', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_resources',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /resources/{id} - Get single resource
    register_rest_route($namespace, '/resources/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_resource',
        'permission_callback' => 'is_user_logged_in',
    ));

    // POST /resources/{id}/download - Track resource download
    register_rest_route($namespace, '/resources/(?P<id>[a-zA-Z0-9_-]+)/download', array(
        'methods'             => 'POST',
        'callback'            => 'teeptrak_rest_track_download',
        'permission_callback' => 'is_user_logged_in',
    ));

    // =============================================================================
    // CHART DATA ENDPOINTS
    // =============================================================================

    // GET /charts/pipeline - Get pipeline chart data
    register_rest_route($namespace, '/charts/pipeline', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_pipeline_chart',
        'permission_callback' => 'is_user_logged_in',
    ));

    // GET /charts/monthly - Get monthly performance chart data
    register_rest_route($namespace, '/charts/monthly', array(
        'methods'             => 'GET',
        'callback'            => 'teeptrak_rest_get_monthly_chart',
        'permission_callback' => 'is_user_logged_in',
    ));
}
add_action('rest_api_init', 'teeptrak_register_rest_routes');

// =============================================================================
// PARTNER CALLBACKS
// =============================================================================

/**
 * Get current partner
 */
function teeptrak_rest_get_partner($request) {
    $partner = teeptrak_get_current_partner();
    return rest_ensure_response($partner);
}

/**
 * Update partner profile
 */
function teeptrak_rest_update_partner($request) {
    $user_id = get_current_user_id();
    $params = $request->get_json_params();

    $result = teeptrak_update_partner_profile($user_id, $params);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'partner' => teeptrak_get_current_partner(),
    ));
}

/**
 * Get partner stats
 */
function teeptrak_rest_get_partner_stats($request) {
    $partner = teeptrak_get_current_partner();
    $deal_stats = teeptrak_get_deal_stats();
    $training = teeptrak_get_training_progress();
    $forecast = teeptrak_calculate_commission_forecast(get_current_user_id());

    return rest_ensure_response(array(
        'partner_score'       => $partner['partner_score'],
        'tier'                => $partner['tier'],
        'commission_rate'     => $partner['commission_rate'],
        'active_deals'        => $deal_stats['active'],
        'total_deals'         => $deal_stats['total'],
        'won_deals'           => $deal_stats['won'],
        'pipeline_value'      => $deal_stats['pipeline_value'],
        'won_value'           => $deal_stats['won_value'],
        'training_progress'   => $training['percent'],
        'available_balance'   => $partner['available_balance'],
        'pending_commissions' => $partner['pending_commissions'],
        'total_earned'        => $partner['total_earned'],
        'forecast'            => $forecast['total'],
    ));
}

/**
 * Get notifications
 */
function teeptrak_rest_get_notifications($request) {
    $unread_only = $request->get_param('unread_only') === 'true';
    $limit = absint($request->get_param('limit')) ?: 20;
    $offset = absint($request->get_param('offset')) ?: 0;

    $notifications = teeptrak_get_notifications(get_current_user_id(), $unread_only, $limit, $offset);
    $unread_count = teeptrak_get_unread_notification_count();

    return rest_ensure_response(array(
        'notifications' => $notifications,
        'unread_count'  => $unread_count,
    ));
}

/**
 * Mark notifications as read
 */
function teeptrak_rest_mark_notifications_read($request) {
    $params = $request->get_json_params();

    if (isset($params['notification_id'])) {
        teeptrak_mark_notification_read($params['notification_id']);
    } elseif (isset($params['all']) && $params['all']) {
        teeptrak_mark_all_notifications_read();
    }

    return rest_ensure_response(array(
        'success'      => true,
        'unread_count' => teeptrak_get_unread_notification_count(),
    ));
}

// =============================================================================
// DEAL CALLBACKS
// =============================================================================

/**
 * Get deals
 */
function teeptrak_rest_get_deals($request) {
    $deals = teeptrak_get_partner_deals();
    $stage = $request->get_param('stage');
    $sort = $request->get_param('sort') ?: 'created_at';
    $order = $request->get_param('order') ?: 'desc';

    // Filter by stage
    if ($stage) {
        $deals = array_filter($deals, function($deal) use ($stage) {
            return $deal['stage'] === $stage;
        });
    }

    // Sort
    usort($deals, function($a, $b) use ($sort, $order) {
        $a_val = $a[$sort] ?? '';
        $b_val = $b[$sort] ?? '';

        if ($order === 'desc') {
            return strcmp($b_val, $a_val);
        }
        return strcmp($a_val, $b_val);
    });

    return rest_ensure_response($deals);
}

/**
 * Get single deal
 */
function teeptrak_rest_get_deal($request) {
    $deal_id = $request->get_param('id');
    $deals = teeptrak_get_partner_deals();

    foreach ($deals as $deal) {
        if ($deal['id'] === $deal_id) {
            return rest_ensure_response($deal);
        }
    }

    return new WP_Error('not_found', __('Deal not found', 'teeptrak-partner'), array('status' => 404));
}

/**
 * Create deal
 */
function teeptrak_rest_create_deal($request) {
    $params = $request->get_json_params();

    if (empty($params['company_name'])) {
        return new WP_Error('missing_field', __('Company name is required', 'teeptrak-partner'), array('status' => 400));
    }

    $deal = teeptrak_register_deal(get_current_user_id(), $params);

    return rest_ensure_response(array(
        'success' => true,
        'deal'    => $deal,
        'message' => __('Deal registered successfully', 'teeptrak-partner'),
    ));
}

/**
 * Update deal
 */
function teeptrak_rest_update_deal($request) {
    $deal_id = $request->get_param('id');
    $params = $request->get_json_params();

    $result = teeptrak_update_deal(get_current_user_id(), $deal_id, $params);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'deal'    => $result,
    ));
}

/**
 * Delete deal
 */
function teeptrak_rest_delete_deal($request) {
    $deal_id = $request->get_param('id');

    $result = teeptrak_delete_deal(get_current_user_id(), $deal_id);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'message' => __('Deal deleted successfully', 'teeptrak-partner'),
    ));
}

/**
 * Update deal stage
 */
function teeptrak_rest_update_deal_stage($request) {
    $deal_id = $request->get_param('id');
    $params = $request->get_json_params();

    if (empty($params['stage'])) {
        return new WP_Error('missing_field', __('Stage is required', 'teeptrak-partner'), array('status' => 400));
    }

    $result = teeptrak_update_deal_stage(get_current_user_id(), $deal_id, $params['stage']);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'deal'    => $result,
    ));
}

/**
 * Add deal note
 */
function teeptrak_rest_add_deal_note($request) {
    $deal_id = $request->get_param('id');
    $params = $request->get_json_params();

    if (empty($params['note'])) {
        return new WP_Error('missing_field', __('Note content is required', 'teeptrak-partner'), array('status' => 400));
    }

    $result = teeptrak_add_deal_note(get_current_user_id(), $deal_id, $params['note']);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'note'    => $result,
    ));
}

/**
 * Export deals
 */
function teeptrak_rest_export_deals($request) {
    $csv_data = teeptrak_export_deals_csv(get_current_user_id());

    $output = fopen('php://temp', 'r+');
    foreach ($csv_data as $row) {
        fputcsv($output, $row);
    }
    rewind($output);
    $csv_content = stream_get_contents($output);
    fclose($output);

    return rest_ensure_response(array(
        'csv'      => base64_encode($csv_content),
        'filename' => 'teeptrak-deals-' . date('Y-m-d') . '.csv',
    ));
}

// =============================================================================
// COMMISSION CALLBACKS
// =============================================================================

/**
 * Get commissions
 */
function teeptrak_rest_get_commissions($request) {
    $partner = teeptrak_get_current_partner();
    $transactions = teeptrak_get_partner_transactions();

    return rest_ensure_response(array(
        'available_balance'   => $partner['available_balance'],
        'pending_commissions' => $partner['pending_commissions'],
        'total_paid'          => $partner['total_paid'],
        'total_earned'        => $partner['total_earned'],
        'commission_rate'     => $partner['commission_rate'],
        'transactions'        => $transactions,
    ));
}

/**
 * Get commission forecast
 */
function teeptrak_rest_get_commission_forecast($request) {
    $forecast = teeptrak_calculate_commission_forecast(get_current_user_id());

    return rest_ensure_response($forecast);
}

/**
 * Request payout
 */
function teeptrak_rest_request_payout($request) {
    $params = $request->get_json_params();

    $amount = floatval($params['amount'] ?? 0);
    $method = sanitize_text_field($params['method'] ?? 'sepa');

    if ($amount <= 0) {
        return new WP_Error('invalid_amount', __('Invalid amount', 'teeptrak-partner'), array('status' => 400));
    }

    $result = teeptrak_request_payout(get_current_user_id(), $amount, $method);

    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response(array(
        'success' => true,
        'message' => __('Payout request submitted successfully', 'teeptrak-partner'),
    ));
}

/**
 * Get statements
 */
function teeptrak_rest_get_statements($request) {
    $transactions = teeptrak_get_partner_transactions();
    $year = $request->get_param('year') ?: date('Y');

    // Group by month
    $statements = array();
    foreach ($transactions as $txn) {
        $txn_year = date('Y', strtotime($txn['date']));
        if ($txn_year !== $year) {
            continue;
        }

        $month = date('Y-m', strtotime($txn['date']));
        if (!isset($statements[$month])) {
            $statements[$month] = array(
                'month'       => $month,
                'commissions' => 0,
                'withdrawals' => 0,
                'count'       => 0,
            );
        }

        if ($txn['type'] === 'commission') {
            $statements[$month]['commissions'] += $txn['amount'];
        } else {
            $statements[$month]['withdrawals'] += abs($txn['amount']);
        }
        $statements[$month]['count']++;
    }

    return rest_ensure_response(array_values($statements));
}

// =============================================================================
// TRAINING CALLBACKS
// =============================================================================

/**
 * Get training progress
 */
function teeptrak_rest_get_training_progress($request) {
    $progress = teeptrak_get_training_progress();
    $modules = teeptrak_get_training_modules();

    // Enrich modules with progress
    foreach ($modules as &$module) {
        $module['progress'] = isset($progress['modules'][$module['id']]) ? $progress['modules'][$module['id']] : 0;
        $module['completed'] = $module['progress'] >= 100;
    }

    return rest_ensure_response(array(
        'modules'   => $modules,
        'completed' => $progress['completed'],
        'total'     => $progress['total'],
        'percent'   => $progress['percent'],
    ));
}

/**
 * Update training progress
 */
function teeptrak_rest_update_training_progress($request) {
    $module_id = absint($request->get_param('module_id'));
    $params = $request->get_json_params();
    $progress_value = isset($params['progress']) ? min(100, max(0, absint($params['progress']))) : 100;

    $user_id = get_current_user_id();
    $progress = get_user_meta($user_id, 'teeptrak_training_progress', true) ?: array();
    $old_progress = isset($progress[$module_id]) ? $progress[$module_id] : 0;

    $progress[$module_id] = $progress_value;
    update_user_meta($user_id, 'teeptrak_training_progress', $progress);

    // If module completed, send notification
    if ($progress_value >= 100 && $old_progress < 100) {
        teeptrak_notify_training_complete($user_id, $module_id);
    }

    // Recalculate partner score
    teeptrak_calculate_partner_score($user_id);

    return rest_ensure_response(array(
        'success'  => true,
        'progress' => teeptrak_get_training_progress(),
    ));
}

/**
 * Get certificates
 */
function teeptrak_rest_get_certificates($request) {
    $progress = teeptrak_get_training_progress();
    $certificates = array();

    // Basic certification (modules 1-3)
    if (isset($progress['modules'][1]) && $progress['modules'][1] >= 100 &&
        isset($progress['modules'][2]) && $progress['modules'][2] >= 100 &&
        isset($progress['modules'][3]) && $progress['modules'][3] >= 100) {
        $certificates[] = array(
            'id'    => 'cert_basic',
            'name'  => __('TeepTrak Basic Certification', 'teeptrak-partner'),
            'level' => 1,
            'date'  => date('Y-m-d'),
        );
    }

    // Advanced certification (modules 4-6)
    if (isset($progress['modules'][4]) && $progress['modules'][4] >= 100 &&
        isset($progress['modules'][5]) && $progress['modules'][5] >= 100 &&
        isset($progress['modules'][6]) && $progress['modules'][6] >= 100) {
        $certificates[] = array(
            'id'    => 'cert_advanced',
            'name'  => __('TeepTrak Advanced Sales Certification', 'teeptrak-partner'),
            'level' => 2,
            'date'  => date('Y-m-d'),
        );
    }

    // Technical certification (modules 7-8)
    if (isset($progress['modules'][7]) && $progress['modules'][7] >= 100 &&
        isset($progress['modules'][8]) && $progress['modules'][8] >= 100) {
        $certificates[] = array(
            'id'    => 'cert_technical',
            'name'  => __('TeepTrak Technical Certification', 'teeptrak-partner'),
            'level' => 3,
            'date'  => date('Y-m-d'),
        );
    }

    return rest_ensure_response($certificates);
}

// =============================================================================
// RESOURCE CALLBACKS
// =============================================================================

/**
 * Get resources
 */
function teeptrak_rest_get_resources($request) {
    $resources = teeptrak_get_resources();
    $category = $request->get_param('category');
    $partner = teeptrak_get_current_partner();

    // Filter by category
    if ($category && $category !== 'all') {
        $resources = array_filter($resources, function($resource) use ($category) {
            return $resource['category'] === $category;
        });
    }

    // Add access info
    foreach ($resources as &$resource) {
        $resource['has_access'] = teeptrak_tier_has_access($partner['tier'], $resource['min_tier']);
    }

    return rest_ensure_response(array_values($resources));
}

/**
 * Get single resource
 */
function teeptrak_rest_get_resource($request) {
    $resource_id = $request->get_param('id');
    $resources = teeptrak_get_resources();
    $partner = teeptrak_get_current_partner();

    foreach ($resources as $resource) {
        if ($resource['id'] === $resource_id) {
            $resource['has_access'] = teeptrak_tier_has_access($partner['tier'], $resource['min_tier']);
            return rest_ensure_response($resource);
        }
    }

    return new WP_Error('not_found', __('Resource not found', 'teeptrak-partner'), array('status' => 404));
}

/**
 * Track resource download
 */
function teeptrak_rest_track_download($request) {
    $resource_id = $request->get_param('id');
    $user_id = get_current_user_id();

    // Track download in user meta
    $downloads = get_user_meta($user_id, 'teeptrak_resource_downloads', true) ?: array();
    $downloads[] = array(
        'resource_id' => $resource_id,
        'downloaded_at' => current_time('mysql'),
    );
    update_user_meta($user_id, 'teeptrak_resource_downloads', $downloads);

    do_action('teeptrak_resource_downloaded', $resource_id, $user_id);

    return rest_ensure_response(array(
        'success' => true,
    ));
}

// =============================================================================
// CHART CALLBACKS
// =============================================================================

/**
 * Get pipeline chart data
 */
function teeptrak_rest_get_pipeline_chart($request) {
    $stats = teeptrak_get_deal_stats();
    $stages = teeptrak_get_deal_stages();

    $data = array(
        'labels' => array(),
        'values' => array(),
        'colors' => array(),
    );

    foreach ($stages as $key => $stage) {
        if (!in_array($key, array('closed_won', 'closed_lost'))) {
            $data['labels'][] = $stage['label'];
            $data['values'][] = $stats['by_stage'][$key] ?? 0;
            $data['colors'][] = $stage['color'];
        }
    }

    return rest_ensure_response($data);
}

/**
 * Get monthly chart data
 */
function teeptrak_rest_get_monthly_chart($request) {
    $months = absint($request->get_param('months')) ?: 6;
    $monthly_stats = teeptrak_get_monthly_deal_stats(get_current_user_id(), $months);

    $data = array(
        'labels'    => array(),
        'deals'     => array(),
        'value'     => array(),
        'won'       => array(),
        'won_value' => array(),
    );

    foreach ($monthly_stats as $month) {
        $data['labels'][] = $month['label'];
        $data['deals'][] = $month['deals'];
        $data['value'][] = $month['value'];
        $data['won'][] = $month['won'];
        $data['won_value'][] = $month['won_value'];
    }

    return rest_ensure_response($data);
}
