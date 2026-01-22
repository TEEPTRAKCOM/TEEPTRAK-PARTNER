<?php
/**
 * Admin Dashboard for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu
 */
function teeptrak_admin_menu() {
    add_menu_page(
        __('Partner Portal', 'teeptrak-partner'),
        __('Partner Portal', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-partners',
        'teeptrak_admin_partners_page',
        'dashicons-groups',
        30
    );

    add_submenu_page(
        'teeptrak-partners',
        __('All Partners', 'teeptrak-partner'),
        __('All Partners', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-partners',
        'teeptrak_admin_partners_page'
    );

    add_submenu_page(
        'teeptrak-partners',
        __('Deal Pipeline', 'teeptrak-partner'),
        __('Deal Pipeline', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-deals',
        'teeptrak_admin_deals_page'
    );

    add_submenu_page(
        'teeptrak-partners',
        __('Commissions', 'teeptrak-partner'),
        __('Commissions', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-commissions',
        'teeptrak_admin_commissions_page'
    );

    add_submenu_page(
        'teeptrak-partners',
        __('Training Reports', 'teeptrak-partner'),
        __('Training Reports', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-training',
        'teeptrak_admin_training_page'
    );

    add_submenu_page(
        'teeptrak-partners',
        __('Settings', 'teeptrak-partner'),
        __('Settings', 'teeptrak-partner'),
        'manage_options',
        'teeptrak-settings',
        'teeptrak_admin_settings_page'
    );
}
add_action('admin_menu', 'teeptrak_admin_menu');

/**
 * Admin Partners Page
 */
function teeptrak_admin_partners_page() {
    $partners = get_users(array(
        'meta_key'   => 'teeptrak_partner_tier',
        'meta_compare' => 'EXISTS',
    ));

    $tiers = teeptrak_get_tier_config();

    // Get stats
    $total_partners = count($partners);
    $total_pipeline = 0;
    $total_won = 0;

    foreach ($partners as $partner) {
        $stats = teeptrak_get_deal_stats($partner->ID);
        $total_pipeline += $stats['pipeline_value'];
        $total_won += $stats['won_value'];
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Partner Overview', 'teeptrak-partner'); ?></h1>

        <!-- Stats Cards -->
        <div class="teeptrak-admin-stats">
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html($total_partners); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Partners', 'teeptrak-partner'); ?></span>
            </div>
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html(teeptrak_format_currency($total_pipeline)); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Pipeline', 'teeptrak-partner'); ?></span>
            </div>
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html(teeptrak_format_currency($total_won)); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Won Revenue', 'teeptrak-partner'); ?></span>
            </div>
        </div>

        <!-- Partners Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Partner', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Email', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Tier', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Score', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Deals', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Pipeline', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Actions', 'teeptrak-partner'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $partner) :
                    $tier = get_user_meta($partner->ID, 'teeptrak_partner_tier', true) ?: 'bronze';
                    $score = get_user_meta($partner->ID, 'teeptrak_partner_score', true) ?: 0;
                    $stats = teeptrak_get_deal_stats($partner->ID);
                ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($partner->display_name); ?></strong>
                        </td>
                        <td><?php echo esc_html($partner->user_email); ?></td>
                        <td>
                            <span class="teeptrak-tier-badge tier-<?php echo esc_attr($tier); ?>">
                                <?php echo esc_html($tiers[$tier]['name']); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($score); ?>/100</td>
                        <td>
                            <?php echo esc_html($stats['active']); ?> <?php esc_html_e('active', 'teeptrak-partner'); ?>,
                            <?php echo esc_html($stats['won']); ?> <?php esc_html_e('won', 'teeptrak-partner'); ?>
                        </td>
                        <td><?php echo esc_html(teeptrak_format_currency($stats['pipeline_value'])); ?></td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $partner->ID)); ?>" class="button button-small">
                                <?php esc_html_e('Edit', 'teeptrak-partner'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <style>
        .teeptrak-admin-stats {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .teeptrak-admin-stat-card {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            min-width: 150px;
        }
        .teeptrak-admin-stat-card .stat-value {
            display: block;
            font-size: 24px;
            font-weight: 600;
            color: #1d2327;
        }
        .teeptrak-admin-stat-card .stat-label {
            display: block;
            font-size: 13px;
            color: #646970;
            margin-top: 5px;
        }
        .teeptrak-tier-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
        }
        .teeptrak-tier-badge.tier-bronze { background: #CD7F32; color: #fff; }
        .teeptrak-tier-badge.tier-silver { background: #A8A8A8; color: #000; }
        .teeptrak-tier-badge.tier-gold { background: #FFD700; color: #000; }
        .teeptrak-tier-badge.tier-platinum { background: #E5E4E2; color: #000; }
    </style>
    <?php
}

/**
 * Admin Deals Page
 */
function teeptrak_admin_deals_page() {
    $partners = get_users(array(
        'meta_key'   => 'teeptrak_partner_tier',
        'meta_compare' => 'EXISTS',
    ));

    $all_deals = array();
    foreach ($partners as $partner) {
        $deals = teeptrak_get_partner_deals($partner->ID);
        foreach ($deals as $deal) {
            $deal['partner_id'] = $partner->ID;
            $deal['partner_name'] = $partner->display_name;
            $all_deals[] = $deal;
        }
    }

    // Sort by created date
    usort($all_deals, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    $stages = teeptrak_get_deal_stages();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Deal Pipeline', 'teeptrak-partner'); ?></h1>

        <!-- Stage Summary -->
        <div class="teeptrak-admin-stats">
            <?php foreach ($stages as $stage_key => $stage) :
                $count = count(array_filter($all_deals, function($d) use ($stage_key) {
                    return $d['stage'] === $stage_key;
                }));
            ?>
                <div class="teeptrak-admin-stat-card">
                    <span class="stat-value"><?php echo esc_html($count); ?></span>
                    <span class="stat-label"><?php echo esc_html($stage['label']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Deals Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Company', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Partner', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Value', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Stage', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Protection', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Created', 'teeptrak-partner'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_deals as $deal) :
                    $days_left = teeptrak_get_protection_days($deal['protection_end'] ?? '');
                    $stage_config = $stages[$deal['stage']] ?? $stages['registered'];
                ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($deal['company_name']); ?></strong>
                            <br>
                            <span class="description"><?php echo esc_html($deal['industry'] ?? ''); ?></span>
                        </td>
                        <td><?php echo esc_html($deal['partner_name']); ?></td>
                        <td><?php echo esc_html(teeptrak_format_currency($deal['deal_value'] ?? 0)); ?></td>
                        <td>
                            <span style="color: <?php echo esc_attr($stage_config['color']); ?>; font-weight: 500;">
                                <?php echo esc_html($stage_config['label']); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($days_left); ?> <?php esc_html_e('days', 'teeptrak-partner'); ?></td>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($deal['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Admin Commissions Page
 */
function teeptrak_admin_commissions_page() {
    $partners = get_users(array(
        'meta_key'   => 'teeptrak_partner_tier',
        'meta_compare' => 'EXISTS',
    ));

    $pending_requests = array();
    $total_pending = 0;
    $total_paid = 0;

    foreach ($partners as $partner) {
        $transactions = teeptrak_get_partner_transactions($partner->ID);
        foreach ($transactions as $txn) {
            if ($txn['type'] === 'withdrawal' && $txn['status'] === 'pending') {
                $txn['partner_id'] = $partner->ID;
                $txn['partner_name'] = $partner->display_name;
                $pending_requests[] = $txn;
            }
        }

        $pending = (float) get_user_meta($partner->ID, 'teeptrak_pending_commissions', true) ?: 0;
        $paid = (float) get_user_meta($partner->ID, 'teeptrak_total_paid', true) ?: 0;

        $total_pending += $pending;
        $total_paid += $paid;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Commission Management', 'teeptrak-partner'); ?></h1>

        <!-- Stats -->
        <div class="teeptrak-admin-stats">
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html(teeptrak_format_currency($total_pending)); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Pending', 'teeptrak-partner'); ?></span>
            </div>
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html(teeptrak_format_currency($total_paid)); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Paid', 'teeptrak-partner'); ?></span>
            </div>
            <div class="teeptrak-admin-stat-card">
                <span class="stat-value"><?php echo esc_html(count($pending_requests)); ?></span>
                <span class="stat-label"><?php esc_html_e('Pending Payout Requests', 'teeptrak-partner'); ?></span>
            </div>
        </div>

        <?php if (!empty($pending_requests)) : ?>
            <h2><?php esc_html_e('Pending Payout Requests', 'teeptrak-partner'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Partner', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Amount', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Method', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Date', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Actions', 'teeptrak-partner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_requests as $request) : ?>
                        <tr>
                            <td><?php echo esc_html($request['partner_name']); ?></td>
                            <td><?php echo esc_html(teeptrak_format_currency(abs($request['amount']))); ?></td>
                            <td><?php echo esc_html(strtoupper($request['method'] ?? 'SEPA')); ?></td>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($request['date']))); ?></td>
                            <td>
                                <button class="button button-primary button-small" data-action="approve" data-txn="<?php echo esc_attr($request['id']); ?>" data-partner="<?php echo esc_attr($request['partner_id']); ?>">
                                    <?php esc_html_e('Approve', 'teeptrak-partner'); ?>
                                </button>
                                <button class="button button-small" data-action="reject" data-txn="<?php echo esc_attr($request['id']); ?>" data-partner="<?php echo esc_attr($request['partner_id']); ?>">
                                    <?php esc_html_e('Reject', 'teeptrak-partner'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Admin Training Page
 */
function teeptrak_admin_training_page() {
    $partners = get_users(array(
        'meta_key'   => 'teeptrak_partner_tier',
        'meta_compare' => 'EXISTS',
    ));

    $modules = teeptrak_get_training_modules();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Training Reports', 'teeptrak-partner'); ?></h1>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Partner', 'teeptrak-partner'); ?></th>
                    <?php foreach ($modules as $module) : ?>
                        <th title="<?php echo esc_attr($module['title']); ?>">M<?php echo esc_html($module['id']); ?></th>
                    <?php endforeach; ?>
                    <th><?php esc_html_e('Overall', 'teeptrak-partner'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $partner) :
                    $progress = teeptrak_get_training_progress($partner->ID);
                ?>
                    <tr>
                        <td><?php echo esc_html($partner->display_name); ?></td>
                        <?php foreach ($modules as $module) :
                            $module_progress = isset($progress['modules'][$module['id']]) ? $progress['modules'][$module['id']] : 0;
                        ?>
                            <td>
                                <?php if ($module_progress >= 100) : ?>
                                    <span style="color: #22C55E;">&#10003;</span>
                                <?php elseif ($module_progress > 0) : ?>
                                    <span style="color: #F59E0B;"><?php echo esc_html($module_progress); ?>%</span>
                                <?php else : ?>
                                    <span style="color: #9CA3AF;">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td><strong><?php echo esc_html($progress['percent']); ?>%</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Add partner meta fields to user profile
 */
function teeptrak_admin_user_profile_fields($user) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $tiers = teeptrak_get_tier_config();
    $current_tier = get_user_meta($user->ID, 'teeptrak_partner_tier', true) ?: 'bronze';
    $commission_rate = get_user_meta($user->ID, 'teeptrak_commission_rate', true) ?: 15;
    $partner_score = get_user_meta($user->ID, 'teeptrak_partner_score', true) ?: 0;
    ?>
    <h2><?php esc_html_e('TeepTrak Partner Information', 'teeptrak-partner'); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="teeptrak_partner_tier"><?php esc_html_e('Partner Tier', 'teeptrak-partner'); ?></label></th>
            <td>
                <select name="teeptrak_partner_tier" id="teeptrak_partner_tier">
                    <?php foreach ($tiers as $key => $tier) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($current_tier, $key); ?>>
                            <?php echo esc_html($tier['name']); ?> (<?php echo esc_html($tier['commission_rate']); ?>%)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="teeptrak_commission_rate"><?php esc_html_e('Commission Rate', 'teeptrak-partner'); ?></label></th>
            <td>
                <input type="number" name="teeptrak_commission_rate" id="teeptrak_commission_rate" value="<?php echo esc_attr($commission_rate); ?>" min="0" max="100"> %
            </td>
        </tr>
        <tr>
            <th><label for="teeptrak_partner_score"><?php esc_html_e('Partner Score', 'teeptrak-partner'); ?></label></th>
            <td>
                <input type="number" name="teeptrak_partner_score" id="teeptrak_partner_score" value="<?php echo esc_attr($partner_score); ?>" min="0" max="100"> / 100
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e('Actions', 'teeptrak-partner'); ?></th>
            <td>
                <button type="button" class="button" id="teeptrak-setup-demo-data" data-user-id="<?php echo esc_attr($user->ID); ?>">
                    <?php esc_html_e('Setup Demo Data', 'teeptrak-partner'); ?>
                </button>
                <p class="description"><?php esc_html_e('Creates demo deals, transactions, and training progress for this user.', 'teeptrak-partner'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'teeptrak_admin_user_profile_fields');
add_action('edit_user_profile', 'teeptrak_admin_user_profile_fields');

/**
 * Save partner meta fields
 */
function teeptrak_admin_save_user_profile_fields($user_id) {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['teeptrak_partner_tier'])) {
        update_user_meta($user_id, 'teeptrak_partner_tier', sanitize_text_field($_POST['teeptrak_partner_tier']));
    }

    if (isset($_POST['teeptrak_commission_rate'])) {
        update_user_meta($user_id, 'teeptrak_commission_rate', absint($_POST['teeptrak_commission_rate']));
    }

    if (isset($_POST['teeptrak_partner_score'])) {
        update_user_meta($user_id, 'teeptrak_partner_score', absint($_POST['teeptrak_partner_score']));
    }
}
add_action('personal_options_update', 'teeptrak_admin_save_user_profile_fields');
add_action('edit_user_profile_update', 'teeptrak_admin_save_user_profile_fields');

/**
 * AJAX: Setup demo data for user
 */
function teeptrak_ajax_admin_setup_demo() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'teeptrak-partner')));
    }

    $user_id = absint($_POST['user_id'] ?? 0);

    if (!$user_id) {
        wp_send_json_error(array('message' => __('Invalid user ID', 'teeptrak-partner')));
    }

    teeptrak_setup_demo_user($user_id);

    wp_send_json_success(array('message' => __('Demo data created successfully', 'teeptrak-partner')));
}
add_action('wp_ajax_teeptrak_admin_setup_demo', 'teeptrak_ajax_admin_setup_demo');

/**
 * Enqueue admin scripts
 */
function teeptrak_admin_enqueue_scripts($hook) {
    if (strpos($hook, 'teeptrak') === false && strpos($hook, 'user') === false) {
        return;
    }

    wp_enqueue_script(
        'teeptrak-admin',
        TEEPTRAK_ASSETS . '/js/admin.js',
        array('jquery'),
        TEEPTRAK_VERSION,
        true
    );

    wp_localize_script('teeptrak-admin', 'teeptrakAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('teeptrak_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'teeptrak_admin_enqueue_scripts');
