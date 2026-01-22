<?php
/**
 * Admin Settings for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Settings Page
 */
function teeptrak_admin_settings_page() {
    // Handle form submission
    if (isset($_POST['teeptrak_settings_submit']) && check_admin_referer('teeptrak_settings_nonce')) {
        // Save settings
        $settings = array(
            'teeptrak_odoo_api_key' => sanitize_text_field($_POST['teeptrak_odoo_api_key'] ?? ''),
            'teeptrak_odoo_webhook_secret' => sanitize_text_field($_POST['teeptrak_odoo_webhook_secret'] ?? ''),
            'teeptrak_email_from_name' => sanitize_text_field($_POST['teeptrak_email_from_name'] ?? ''),
            'teeptrak_email_from_address' => sanitize_email($_POST['teeptrak_email_from_address'] ?? ''),
            'teeptrak_min_payout_amount' => absint($_POST['teeptrak_min_payout_amount'] ?? 100),
            'teeptrak_protection_days' => absint($_POST['teeptrak_protection_days'] ?? 90),
        );

        foreach ($settings as $key => $value) {
            update_option($key, $value);
        }

        echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved successfully.', 'teeptrak-partner') . '</p></div>';
    }

    // Get current settings
    $odoo_config = teeptrak_get_odoo_config();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('TeepTrak Partner Portal Settings', 'teeptrak-partner'); ?></h1>

        <form method="post" action="">
            <?php wp_nonce_field('teeptrak_settings_nonce'); ?>

            <h2 class="title"><?php esc_html_e('Odoo CRM Integration', 'teeptrak-partner'); ?></h2>
            <p class="description">
                <?php esc_html_e('Configure Odoo CRM integration for deal and commission synchronization.', 'teeptrak-partner'); ?>
            </p>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="teeptrak_odoo_url"><?php esc_html_e('Odoo URL', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="url" name="teeptrak_odoo_url" id="teeptrak_odoo_url"
                               value="<?php echo esc_attr($odoo_config['url']); ?>"
                               class="regular-text"
                               placeholder="https://your-company.odoo.com">
                        <p class="description"><?php esc_html_e('Your Odoo instance URL', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="teeptrak_odoo_db"><?php esc_html_e('Database Name', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="teeptrak_odoo_db" id="teeptrak_odoo_db"
                               value="<?php echo esc_attr($odoo_config['db']); ?>"
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="teeptrak_odoo_api_key"><?php esc_html_e('API Key', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="password" name="teeptrak_odoo_api_key" id="teeptrak_odoo_api_key"
                               value="<?php echo esc_attr(get_option('teeptrak_odoo_api_key', '')); ?>"
                               class="regular-text">
                        <p class="description"><?php esc_html_e('API key for authentication', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="teeptrak_odoo_webhook_secret"><?php esc_html_e('Webhook Secret', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="password" name="teeptrak_odoo_webhook_secret" id="teeptrak_odoo_webhook_secret"
                               value="<?php echo esc_attr(get_option('teeptrak_odoo_webhook_secret', '')); ?>"
                               class="regular-text">
                        <p class="description"><?php esc_html_e('Secret key for webhook signature verification', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Connection Status', 'teeptrak-partner'); ?></th>
                    <td>
                        <button type="button" class="button" id="test-odoo-connection">
                            <?php esc_html_e('Test Connection', 'teeptrak-partner'); ?>
                        </button>
                        <span id="odoo-connection-status"></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Webhook URL', 'teeptrak-partner'); ?></th>
                    <td>
                        <code><?php echo esc_html(rest_url('teeptrak/v2/webhooks/odoo')); ?></code>
                        <p class="description"><?php esc_html_e('Configure this URL in your Odoo automation/webhook settings', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Manual Sync', 'teeptrak-partner'); ?></th>
                    <td>
                        <button type="button" class="button" id="manual-odoo-sync">
                            <?php esc_html_e('Sync Now', 'teeptrak-partner'); ?>
                        </button>
                        <span id="odoo-sync-status"></span>
                        <p class="description">
                            <?php
                            $last_sync = get_option('teeptrak_odoo_last_bulk_sync');
                            if ($last_sync) {
                                printf(
                                    esc_html__('Last sync: %s', 'teeptrak-partner'),
                                    date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($last_sync))
                                );
                            }
                            ?>
                        </p>
                    </td>
                </tr>
            </table>

            <h2 class="title"><?php esc_html_e('Email Settings', 'teeptrak-partner'); ?></h2>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="teeptrak_email_from_name"><?php esc_html_e('From Name', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="teeptrak_email_from_name" id="teeptrak_email_from_name"
                               value="<?php echo esc_attr(get_option('teeptrak_email_from_name', get_bloginfo('name'))); ?>"
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="teeptrak_email_from_address"><?php esc_html_e('From Email', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="email" name="teeptrak_email_from_address" id="teeptrak_email_from_address"
                               value="<?php echo esc_attr(get_option('teeptrak_email_from_address', get_option('admin_email'))); ?>"
                               class="regular-text">
                    </td>
                </tr>
            </table>

            <h2 class="title"><?php esc_html_e('Commission Settings', 'teeptrak-partner'); ?></h2>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="teeptrak_min_payout_amount"><?php esc_html_e('Minimum Payout Amount', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="number" name="teeptrak_min_payout_amount" id="teeptrak_min_payout_amount"
                               value="<?php echo esc_attr(get_option('teeptrak_min_payout_amount', 100)); ?>"
                               min="0" step="10">
                        <span><?php echo esc_html(teeptrak_get_currency()); ?></span>
                        <p class="description"><?php esc_html_e('Minimum amount required to request a payout', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="teeptrak_protection_days"><?php esc_html_e('Deal Protection Period', 'teeptrak-partner'); ?></label>
                    </th>
                    <td>
                        <input type="number" name="teeptrak_protection_days" id="teeptrak_protection_days"
                               value="<?php echo esc_attr(get_option('teeptrak_protection_days', 90)); ?>"
                               min="30" max="365">
                        <span><?php esc_html_e('days', 'teeptrak-partner'); ?></span>
                        <p class="description"><?php esc_html_e('Number of days a deal is protected after registration', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
            </table>

            <h2 class="title"><?php esc_html_e('Notifications', 'teeptrak-partner'); ?></h2>

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Notification Channels', 'teeptrak-partner'); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="teeptrak_notify_email" value="1"
                                       <?php checked(get_option('teeptrak_notify_email', '1'), '1'); ?>>
                                <?php esc_html_e('Email notifications', 'teeptrak-partner'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="teeptrak_notify_inapp" value="1"
                                       <?php checked(get_option('teeptrak_notify_inapp', '1'), '1'); ?>>
                                <?php esc_html_e('In-app notifications', 'teeptrak-partner'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>

            <h2 class="title"><?php esc_html_e('Tools', 'teeptrak-partner'); ?></h2>

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Export Data', 'teeptrak-partner'); ?></th>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=teeptrak_export_all_data&nonce=' . wp_create_nonce('teeptrak_export'))); ?>" class="button">
                            <?php esc_html_e('Export All Partner Data', 'teeptrak-partner'); ?>
                        </a>
                        <p class="description"><?php esc_html_e('Download a CSV file with all partner data, deals, and commissions', 'teeptrak-partner'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Clear Cache', 'teeptrak-partner'); ?></th>
                    <td>
                        <button type="button" class="button" id="clear-cache-btn">
                            <?php esc_html_e('Clear Transient Cache', 'teeptrak-partner'); ?>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Webhook Log', 'teeptrak-partner'); ?></th>
                    <td>
                        <?php
                        $webhook_log = get_option('teeptrak_webhook_log', array());
                        if (!empty($webhook_log)) {
                            echo '<details>';
                            echo '<summary>' . sprintf(esc_html__('Show last %d webhook events', 'teeptrak-partner'), count($webhook_log)) . '</summary>';
                            echo '<pre style="max-height: 200px; overflow: auto; background: #f0f0f1; padding: 10px;">';
                            foreach (array_slice($webhook_log, 0, 10) as $entry) {
                                echo esc_html($entry['timestamp'] . ' - ' . $entry['source'] . ' - ' . $entry['event']) . "\n";
                            }
                            echo '</pre>';
                            echo '</details>';
                        } else {
                            echo '<p class="description">' . esc_html__('No webhook events logged', 'teeptrak-partner') . '</p>';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="teeptrak_settings_submit" class="button-primary"
                       value="<?php esc_attr_e('Save Settings', 'teeptrak-partner'); ?>">
            </p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Test Odoo connection
        $('#test-odoo-connection').on('click', function() {
            var $btn = $(this);
            var $status = $('#odoo-connection-status');

            $btn.prop('disabled', true);
            $status.html('<span class="spinner is-active" style="float: none;"></span>');

            $.post(ajaxurl, {
                action: 'teeptrak_test_odoo',
                nonce: teeptrakAdmin.nonce
            }, function(response) {
                $btn.prop('disabled', false);
                if (response.success) {
                    $status.html('<span style="color: green;"><?php esc_html_e('Connected successfully!', 'teeptrak-partner'); ?></span>');
                } else {
                    $status.html('<span style="color: red;">' + response.data.message + '</span>');
                }
            });
        });

        // Manual Odoo sync
        $('#manual-odoo-sync').on('click', function() {
            var $btn = $(this);
            var $status = $('#odoo-sync-status');

            $btn.prop('disabled', true);
            $status.html('<span class="spinner is-active" style="float: none;"></span>');

            $.post(ajaxurl, {
                action: 'teeptrak_odoo_sync',
                nonce: teeptrakAdmin.nonce
            }, function(response) {
                $btn.prop('disabled', false);
                if (response.success) {
                    $status.html('<span style="color: green;">' + response.data.message + '</span>');
                } else {
                    $status.html('<span style="color: red;">' + response.data.message + '</span>');
                }
            });
        });

        // Clear cache
        $('#clear-cache-btn').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true);

            $.post(ajaxurl, {
                action: 'teeptrak_clear_cache',
                nonce: teeptrakAdmin.nonce
            }, function(response) {
                $btn.prop('disabled', false);
                alert(response.success ? '<?php esc_html_e('Cache cleared!', 'teeptrak-partner'); ?>' : '<?php esc_html_e('Error clearing cache', 'teeptrak-partner'); ?>');
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX: Clear cache
 */
function teeptrak_ajax_clear_cache() {
    check_ajax_referer('teeptrak_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }

    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_teeptrak_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_teeptrak_%'");

    wp_send_json_success();
}
add_action('wp_ajax_teeptrak_clear_cache', 'teeptrak_ajax_clear_cache');

/**
 * AJAX: Export all data
 */
function teeptrak_ajax_export_all_data() {
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'teeptrak_export')) {
        wp_die('Unauthorized');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    $partners = get_users(array(
        'meta_key'   => 'teeptrak_partner_tier',
        'meta_compare' => 'EXISTS',
    ));

    $csv_lines = array();
    $csv_lines[] = array(
        'Partner ID',
        'Partner Name',
        'Email',
        'Tier',
        'Commission Rate',
        'Score',
        'Total Deals',
        'Active Deals',
        'Won Deals',
        'Pipeline Value',
        'Won Value',
        'Available Balance',
        'Total Earned',
        'Total Paid',
        'Training Progress',
    );

    foreach ($partners as $partner) {
        $stats = teeptrak_get_deal_stats($partner->ID);
        $training = teeptrak_get_training_progress($partner->ID);

        $csv_lines[] = array(
            $partner->ID,
            $partner->display_name,
            $partner->user_email,
            get_user_meta($partner->ID, 'teeptrak_partner_tier', true),
            get_user_meta($partner->ID, 'teeptrak_commission_rate', true),
            get_user_meta($partner->ID, 'teeptrak_partner_score', true),
            $stats['total'],
            $stats['active'],
            $stats['won'],
            $stats['pipeline_value'],
            $stats['won_value'],
            get_user_meta($partner->ID, 'teeptrak_available_balance', true),
            get_user_meta($partner->ID, 'teeptrak_total_earned', true),
            get_user_meta($partner->ID, 'teeptrak_total_paid', true),
            $training['percent'] . '%',
        );
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="teeptrak-partners-' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    foreach ($csv_lines as $line) {
        fputcsv($output, $line);
    }
    fclose($output);

    exit;
}
add_action('wp_ajax_teeptrak_export_all_data', 'teeptrak_ajax_export_all_data');
