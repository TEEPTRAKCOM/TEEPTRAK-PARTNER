<?php
/**
 * Template Name: Partner Commissions
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get commission data
$commissions = teeptrak_get_partner_commissions($user_id);
$stats = teeptrak_get_commission_stats($user_id);
$payout_requests = teeptrak_get_payout_requests($user_id);
$forecast = teeptrak_calculate_commission_forecast($user_id);

$min_payout = get_option('teeptrak_min_payout', 100);
$can_request_payout = $stats['available'] >= $min_payout;

get_header();
?>

<div class="tt-page-header">
    <div class="tt-page-header-content">
        <h1 class="tt-page-title"><?php esc_html_e('Commissions', 'teeptrak-partner'); ?></h1>
        <p class="tt-page-subtitle">
            <?php esc_html_e('Track your earnings and request payouts', 'teeptrak-partner'); ?>
        </p>
    </div>
    <div class="tt-page-header-actions">
        <button class="tt-btn tt-btn-primary" <?php echo !$can_request_payout ? 'disabled' : ''; ?> data-modal-open="payout-modal">
            <?php echo teeptrak_icon('credit-card', 16); ?>
            <?php esc_html_e('Request Payout', 'teeptrak-partner'); ?>
        </button>
    </div>
</div>

<!-- Commission KPIs -->
<div class="tt-kpi-grid tt-kpi-grid-4">
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-success">
            <?php echo teeptrak_icon('dollar-sign', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Total Earned', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['total_earned']); ?></span>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-warning">
            <?php echo teeptrak_icon('clock', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Pending', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['pending']); ?></span>
        </div>
        <div class="tt-kpi-subtitle">
            <?php esc_html_e('Awaiting deal closure', 'teeptrak-partner'); ?>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-primary">
            <?php echo teeptrak_icon('wallet', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Available', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['available']); ?></span>
        </div>
        <div class="tt-kpi-subtitle">
            <?php
            if ($can_request_payout) {
                esc_html_e('Ready for payout', 'teeptrak-partner');
            } else {
                printf(
                    /* translators: %s: minimum payout amount */
                    esc_html__('Min. payout: %s', 'teeptrak-partner'),
                    teeptrak_format_currency($min_payout)
                );
            }
            ?>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-info">
            <?php echo teeptrak_icon('check-circle', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Paid Out', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['paid_out']); ?></span>
        </div>
    </div>
</div>

<div class="tt-dashboard-grid">
    <!-- Commission History -->
    <div class="tt-card tt-card-2col">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Commission History', 'teeptrak-partner'); ?></h3>
            <div class="tt-card-actions">
                <select class="tt-select tt-select-sm" id="commission-filter">
                    <option value=""><?php esc_html_e('All Commissions', 'teeptrak-partner'); ?></option>
                    <option value="earned"><?php esc_html_e('Earned', 'teeptrak-partner'); ?></option>
                    <option value="pending"><?php esc_html_e('Pending', 'teeptrak-partner'); ?></option>
                    <option value="paid"><?php esc_html_e('Paid', 'teeptrak-partner'); ?></option>
                </select>
            </div>
        </div>
        <div class="tt-card-body tt-card-body-flush">
            <?php if (empty($commissions)) : ?>
                <div class="tt-empty-state">
                    <?php echo teeptrak_icon('dollar-sign', 48); ?>
                    <h3><?php esc_html_e('No commissions yet', 'teeptrak-partner'); ?></h3>
                    <p><?php esc_html_e('Close your first deal to start earning commissions', 'teeptrak-partner'); ?></p>
                </div>
            <?php else : ?>
                <table class="tt-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date', 'teeptrak-partner'); ?></th>
                            <th><?php esc_html_e('Deal', 'teeptrak-partner'); ?></th>
                            <th><?php esc_html_e('Deal Value', 'teeptrak-partner'); ?></th>
                            <th><?php esc_html_e('Rate', 'teeptrak-partner'); ?></th>
                            <th><?php esc_html_e('Commission', 'teeptrak-partner'); ?></th>
                            <th><?php esc_html_e('Status', 'teeptrak-partner'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commissions as $commission) : ?>
                            <tr>
                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($commission['date']))); ?></td>
                                <td>
                                    <a href="<?php echo esc_url(home_url('/deals/?deal=' . $commission['deal_id'])); ?>">
                                        <?php echo esc_html($commission['deal_company']); ?>
                                    </a>
                                </td>
                                <td><?php echo teeptrak_format_currency($commission['deal_value']); ?></td>
                                <td><?php echo esc_html($commission['rate']); ?>%</td>
                                <td><strong><?php echo teeptrak_format_currency($commission['amount']); ?></strong></td>
                                <td>
                                    <?php
                                    $status_classes = array(
                                        'earned' => 'tt-badge-success',
                                        'pending' => 'tt-badge-warning',
                                        'paid' => 'tt-badge-info',
                                    );
                                    $status_class = $status_classes[$commission['status']] ?? 'tt-badge-secondary';
                                    ?>
                                    <span class="tt-badge <?php echo esc_attr($status_class); ?>">
                                        <?php echo esc_html(ucfirst($commission['status'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Forecast -->
    <div class="tt-card">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Commission Forecast', 'teeptrak-partner'); ?></h3>
        </div>
        <div class="tt-card-body">
            <div class="tt-forecast">
                <div class="tt-forecast-item">
                    <span class="tt-forecast-period"><?php esc_html_e('This Quarter', 'teeptrak-partner'); ?></span>
                    <span class="tt-forecast-value"><?php echo teeptrak_format_currency($forecast['quarter']); ?></span>
                    <span class="tt-forecast-basis"><?php esc_html_e('Based on pipeline', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-forecast-item">
                    <span class="tt-forecast-period"><?php esc_html_e('This Year', 'teeptrak-partner'); ?></span>
                    <span class="tt-forecast-value"><?php echo teeptrak_format_currency($forecast['year']); ?></span>
                    <span class="tt-forecast-basis"><?php esc_html_e('Projected', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <div class="tt-forecast-breakdown">
                <h4><?php esc_html_e('By Stage', 'teeptrak-partner'); ?></h4>
                <?php foreach ($forecast['by_stage'] as $stage => $amount) : ?>
                    <div class="tt-forecast-row">
                        <span class="tt-forecast-stage"><?php echo teeptrak_stage_badge($stage); ?></span>
                        <span class="tt-forecast-amount"><?php echo teeptrak_format_currency($amount); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Payout Requests -->
<?php if (!empty($payout_requests)) : ?>
<div class="tt-card">
    <div class="tt-card-header">
        <h3 class="tt-card-title"><?php esc_html_e('Payout Requests', 'teeptrak-partner'); ?></h3>
    </div>
    <div class="tt-card-body tt-card-body-flush">
        <table class="tt-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Date', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Amount', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Method', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Status', 'teeptrak-partner'); ?></th>
                    <th><?php esc_html_e('Reference', 'teeptrak-partner'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payout_requests as $request) : ?>
                    <tr>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($request['date']))); ?></td>
                        <td><strong><?php echo teeptrak_format_currency($request['amount']); ?></strong></td>
                        <td><?php echo esc_html(ucfirst($request['method'])); ?></td>
                        <td>
                            <?php
                            $payout_status_classes = array(
                                'pending' => 'tt-badge-warning',
                                'approved' => 'tt-badge-info',
                                'paid' => 'tt-badge-success',
                                'rejected' => 'tt-badge-danger',
                            );
                            ?>
                            <span class="tt-badge <?php echo esc_attr($payout_status_classes[$request['status']] ?? ''); ?>">
                                <?php echo esc_html(ucfirst($request['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($request['reference'] ?: '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Payout Request Modal -->
<div id="payout-modal" class="tt-modal">
    <div class="tt-modal-content">
        <div class="tt-modal-header">
            <h2 class="tt-modal-title"><?php esc_html_e('Request Payout', 'teeptrak-partner'); ?></h2>
            <button class="tt-modal-close" data-modal-close>&times;</button>
        </div>
        <form id="payout-form">
            <div class="tt-modal-body">
                <div class="tt-payout-summary">
                    <div class="tt-payout-available">
                        <span class="tt-label"><?php esc_html_e('Available Balance', 'teeptrak-partner'); ?></span>
                        <span class="tt-value"><?php echo teeptrak_format_currency($stats['available']); ?></span>
                    </div>
                </div>

                <div class="tt-form-group">
                    <label class="tt-label" for="payout-amount"><?php esc_html_e('Amount to Withdraw', 'teeptrak-partner'); ?></label>
                    <div class="tt-input-group">
                        <span class="tt-input-prefix">€</span>
                        <input type="number" id="payout-amount" name="amount" class="tt-input"
                               min="<?php echo esc_attr($min_payout); ?>"
                               max="<?php echo esc_attr($stats['available']); ?>"
                               value="<?php echo esc_attr($stats['available']); ?>" required>
                    </div>
                    <span class="tt-help-text">
                        <?php
                        printf(
                            /* translators: %s: minimum payout amount */
                            esc_html__('Minimum payout: %s', 'teeptrak-partner'),
                            teeptrak_format_currency($min_payout)
                        );
                        ?>
                    </span>
                </div>

                <div class="tt-form-group">
                    <label class="tt-label" for="payout-method"><?php esc_html_e('Payment Method', 'teeptrak-partner'); ?></label>
                    <select id="payout-method" name="method" class="tt-select" required>
                        <option value="bank_transfer"><?php esc_html_e('Bank Transfer', 'teeptrak-partner'); ?></option>
                        <option value="paypal"><?php esc_html_e('PayPal', 'teeptrak-partner'); ?></option>
                    </select>
                </div>

                <div class="tt-form-group" id="bank-details">
                    <label class="tt-label"><?php esc_html_e('Bank Details', 'teeptrak-partner'); ?></label>
                    <?php
                    $bank_iban = get_user_meta($user_id, 'bank_iban', true);
                    $bank_bic = get_user_meta($user_id, 'bank_bic', true);
                    ?>
                    <?php if ($bank_iban) : ?>
                        <div class="tt-bank-info">
                            <p><strong>IBAN:</strong> <?php echo esc_html(substr($bank_iban, 0, 4) . ' **** **** ' . substr($bank_iban, -4)); ?></p>
                            <p><strong>BIC:</strong> <?php echo esc_html($bank_bic); ?></p>
                            <a href="<?php echo esc_url(home_url('/profile/#payment')); ?>" class="tt-link">
                                <?php esc_html_e('Update bank details', 'teeptrak-partner'); ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="tt-alert tt-alert-warning">
                            <?php echo teeptrak_icon('alert-circle', 16); ?>
                            <p><?php esc_html_e('Please add your bank details in your profile before requesting a payout.', 'teeptrak-partner'); ?></p>
                            <a href="<?php echo esc_url(home_url('/profile/#payment')); ?>" class="tt-btn tt-btn-sm tt-btn-secondary">
                                <?php esc_html_e('Add Bank Details', 'teeptrak-partner'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tt-info-box">
                    <?php echo teeptrak_icon('info', 20); ?>
                    <div>
                        <strong><?php esc_html_e('Processing Time', 'teeptrak-partner'); ?></strong>
                        <p><?php esc_html_e('Payout requests are typically processed within 5-7 business days after approval.', 'teeptrak-partner'); ?></p>
                    </div>
                </div>
            </div>
            <div class="tt-modal-footer">
                <button type="button" class="tt-btn tt-btn-secondary" data-modal-close>
                    <?php esc_html_e('Cancel', 'teeptrak-partner'); ?>
                </button>
                <button type="submit" class="tt-btn tt-btn-primary" id="submit-payout" <?php echo !$bank_iban ? 'disabled' : ''; ?>>
                    <?php esc_html_e('Request Payout', 'teeptrak-partner'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payout form
    const payoutForm = document.getElementById('payout-form');
    if (payoutForm) {
        payoutForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-payout');
            TeepTrak.setLoading(submitBtn, true);

            const formData = new FormData(this);
            const data = {};
            formData.forEach(function(v, k) { data[k] = v; });

            TeepTrak.ajax('teeptrak_request_payout', data, {
                onSuccess: function() {
                    TeepTrak.setLoading(submitBtn, false);
                    TeepTrak.closeModal();
                    TeepTrak.showToast('<?php echo esc_js(__('Payout request submitted!', 'teeptrak-partner')); ?>', 'success');
                    window.location.reload();
                },
                onError: function(error) {
                    TeepTrak.setLoading(submitBtn, false);
                    TeepTrak.showToast(error || '<?php echo esc_js(__('Error submitting request', 'teeptrak-partner')); ?>', 'error');
                }
            });
        });
    }

    // Payment method toggle
    document.getElementById('payout-method')?.addEventListener('change', function() {
        document.getElementById('bank-details').style.display =
            this.value === 'bank_transfer' ? 'block' : 'none';
    });
});
</script>

<?php get_footer(); ?>
