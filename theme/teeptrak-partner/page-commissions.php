<?php
/**
 * Template Name: Commission Tracking
 * Partner commission tracking and withdrawal page
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header();

// Get partner data
$user_id = get_current_user_id();
$partner = teeptrak_get_partner_by_user($user_id);
$tier_config = teeptrak_get_tier_config($partner['tier'] ?? 'gold');

// Demo commission data from content.json
$commission_summary = array(
    'available' => 4500,
    'pending' => 2800,
    'total_paid' => 18500,
    'this_month' => 1200,
    'last_month' => 3300,
    'ytd' => 15800,
);

$commission_rate = $partner['commission_rate'] ?? $tier_config['commission_rate'];

// Demo commission transactions
$demo_transactions = array(
    array(
        'id' => 1,
        'type' => 'commission',
        'amount' => 3200,
        'deal_company' => 'Stellantis Mulhouse',
        'deal_value' => 128000,
        'status' => 'approved',
        'created_at' => date('Y-m-d', strtotime('-5 days')),
        'approved_at' => date('Y-m-d', strtotime('-3 days')),
    ),
    array(
        'id' => 2,
        'type' => 'commission',
        'amount' => 1800,
        'deal_company' => 'BMW Leipzig',
        'deal_value' => 72000,
        'status' => 'pending',
        'created_at' => date('Y-m-d', strtotime('-2 days')),
        'approved_at' => null,
    ),
    array(
        'id' => 3,
        'type' => 'commission',
        'amount' => 1000,
        'deal_company' => 'Airbus Nantes',
        'deal_value' => 40000,
        'status' => 'pending',
        'created_at' => date('Y-m-d', strtotime('-1 day')),
        'approved_at' => null,
    ),
    array(
        'id' => 4,
        'type' => 'withdrawal',
        'amount' => -2500,
        'deal_company' => null,
        'deal_value' => null,
        'status' => 'paid',
        'created_at' => date('Y-m-d', strtotime('-15 days')),
        'approved_at' => date('Y-m-d', strtotime('-12 days')),
        'payment_method' => 'Bank Transfer',
        'reference' => 'PAY-2026-001234',
    ),
    array(
        'id' => 5,
        'type' => 'commission',
        'amount' => 2250,
        'deal_company' => 'Renault Flins',
        'deal_value' => 90000,
        'status' => 'paid',
        'created_at' => date('Y-m-d', strtotime('-30 days')),
        'approved_at' => date('Y-m-d', strtotime('-25 days')),
    ),
    array(
        'id' => 6,
        'type' => 'commission',
        'amount' => 1875,
        'deal_company' => 'Thales Cholet',
        'deal_value' => 75000,
        'status' => 'paid',
        'created_at' => date('Y-m-d', strtotime('-45 days')),
        'approved_at' => date('Y-m-d', strtotime('-40 days')),
    ),
);

// Get real transactions or use demo
if ($partner && isset($partner['id'])) {
    global $wpdb;
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}teeptrak_commissions'");
    if ($table_exists) {
        $transactions = $wpdb->get_results($wpdb->prepare(
            "SELECT c.*, d.company_name as deal_company, d.deal_value
             FROM {$wpdb->prefix}teeptrak_commissions c
             LEFT JOIN {$wpdb->prefix}teeptrak_deals d ON c.deal_id = d.id
             WHERE c.partner_id = %d
             ORDER BY c.created_at DESC
             LIMIT 20",
            $partner['id']
        ), ARRAY_A);
    }
}
if (empty($transactions)) {
    $transactions = $demo_transactions;
}

// Status configuration
$status_config = array(
    'pending' => array('label' => __('Pending', 'teeptrak-partner'), 'color' => '#F59E0B', 'bg' => '#FEF3C7'),
    'approved' => array('label' => __('Approved', 'teeptrak-partner'), 'color' => '#3B82F6', 'bg' => '#DBEAFE'),
    'paid' => array('label' => __('Paid', 'teeptrak-partner'), 'color' => '#22C55E', 'bg' => '#DCFCE7'),
    'cancelled' => array('label' => __('Cancelled', 'teeptrak-partner'), 'color' => '#DC2626', 'bg' => '#FEE2E2'),
);

// Icon helper
function tt_commissions_icon($name, $size = 20) {
    $icons = array(
        'dollar-sign' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
        'trending-up' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
        'clock' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'check-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'credit-card' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>',
        'download' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
        'arrow-up-right' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>',
        'arrow-down-left' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>',
        'calendar' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
        'info' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
        'x' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>

<div class="tt-commissions-page">
    <!-- Page Header -->
    <div class="tt-page-header tt-mb-6">
        <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
            <div>
                <h1 class="tt-page-title"><?php _e('Commission Tracking', 'teeptrak-partner'); ?></h1>
                <p class="tt-page-subtitle"><?php _e('Track your earnings and request withdrawals.', 'teeptrak-partner'); ?></p>
            </div>
            <button type="button" class="tt-btn tt-btn-primary" id="tt-request-payout-btn" <?php echo $commission_summary['available'] < 100 ? 'disabled' : ''; ?>>
                <?php echo tt_commissions_icon('credit-card', 18); ?>
                <?php _e('Request Payout', 'teeptrak-partner'); ?>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="tt-summary-grid tt-mb-8">
        <!-- Available Balance -->
        <div class="tt-summary-card tt-summary-card-primary">
            <div class="tt-summary-header">
                <span class="tt-summary-label"><?php _e('Available Balance', 'teeptrak-partner'); ?></span>
                <div class="tt-summary-icon" style="background: rgba(255,255,255,0.2);">
                    <?php echo tt_commissions_icon('dollar-sign', 20); ?>
                </div>
            </div>
            <div class="tt-summary-value"><?php echo teeptrak_format_currency($commission_summary['available']); ?></div>
            <div class="tt-summary-footer">
                <span><?php _e('Ready for withdrawal', 'teeptrak-partner'); ?></span>
            </div>
        </div>

        <!-- Pending -->
        <div class="tt-summary-card">
            <div class="tt-summary-header">
                <span class="tt-summary-label"><?php _e('Pending', 'teeptrak-partner'); ?></span>
                <div class="tt-summary-icon" style="background: #FEF3C7; color: #F59E0B;">
                    <?php echo tt_commissions_icon('clock', 20); ?>
                </div>
            </div>
            <div class="tt-summary-value tt-text-amber"><?php echo teeptrak_format_currency($commission_summary['pending']); ?></div>
            <div class="tt-summary-footer">
                <span><?php _e('Awaiting approval', 'teeptrak-partner'); ?></span>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="tt-summary-card">
            <div class="tt-summary-header">
                <span class="tt-summary-label"><?php _e('Total Paid', 'teeptrak-partner'); ?></span>
                <div class="tt-summary-icon" style="background: #DCFCE7; color: #22C55E;">
                    <?php echo tt_commissions_icon('check-circle', 20); ?>
                </div>
            </div>
            <div class="tt-summary-value tt-text-success"><?php echo teeptrak_format_currency($commission_summary['total_paid']); ?></div>
            <div class="tt-summary-footer">
                <span><?php _e('All time', 'teeptrak-partner'); ?></span>
            </div>
        </div>

        <!-- Commission Rate -->
        <div class="tt-summary-card">
            <div class="tt-summary-header">
                <span class="tt-summary-label"><?php _e('Your Rate', 'teeptrak-partner'); ?></span>
                <div class="tt-summary-icon" style="background: #F3E8FF; color: #8B5CF6;">
                    <?php echo tt_commissions_icon('trending-up', 20); ?>
                </div>
            </div>
            <div class="tt-summary-value"><?php echo esc_html($commission_rate); ?>%</div>
            <div class="tt-summary-footer">
                <span><?php echo esc_html($tier_config['name']); ?> <?php _e('Tier', 'teeptrak-partner'); ?></span>
            </div>
        </div>
    </div>

    <!-- Period Stats -->
    <div class="tt-period-stats tt-mb-8">
        <div class="tt-period-card">
            <span class="tt-period-label"><?php _e('This Month', 'teeptrak-partner'); ?></span>
            <span class="tt-period-value"><?php echo teeptrak_format_currency($commission_summary['this_month']); ?></span>
        </div>
        <div class="tt-period-card">
            <span class="tt-period-label"><?php _e('Last Month', 'teeptrak-partner'); ?></span>
            <span class="tt-period-value"><?php echo teeptrak_format_currency($commission_summary['last_month']); ?></span>
        </div>
        <div class="tt-period-card">
            <span class="tt-period-label"><?php _e('Year to Date', 'teeptrak-partner'); ?></span>
            <span class="tt-period-value"><?php echo teeptrak_format_currency($commission_summary['ytd']); ?></span>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="tt-card">
        <div class="tt-card-header tt-flex tt-items-center tt-justify-between">
            <h2 class="tt-card-title"><?php _e('Transaction History', 'teeptrak-partner'); ?></h2>
            <button type="button" class="tt-btn tt-btn-outline tt-btn-sm" id="tt-export-btn">
                <?php echo tt_commissions_icon('download', 16); ?>
                <?php _e('Export', 'teeptrak-partner'); ?>
            </button>
        </div>
        <div class="tt-card-body tt-p-0">
            <?php if (!empty($transactions)) : ?>
                <div class="tt-table-wrapper">
                    <table class="tt-table tt-transactions-table">
                        <thead>
                            <tr>
                                <th><?php _e('Date', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Type', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Description', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Amount', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Status', 'teeptrak-partner'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $tx) :
                                $status = $status_config[$tx['status']] ?? $status_config['pending'];
                                $is_withdrawal = $tx['type'] === 'withdrawal';
                                $amount = floatval($tx['amount']);
                            ?>
                                <tr>
                                    <td>
                                        <div class="tt-tx-date">
                                            <?php echo date_i18n(get_option('date_format'), strtotime($tx['created_at'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tt-tx-type <?php echo $is_withdrawal ? 'is-withdrawal' : 'is-commission'; ?>">
                                            <?php if ($is_withdrawal) : ?>
                                                <?php echo tt_commissions_icon('arrow-down-left', 16); ?>
                                                <span><?php _e('Withdrawal', 'teeptrak-partner'); ?></span>
                                            <?php else : ?>
                                                <?php echo tt_commissions_icon('arrow-up-right', 16); ?>
                                                <span><?php _e('Commission', 'teeptrak-partner'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($is_withdrawal) : ?>
                                            <div class="tt-tx-desc">
                                                <span class="tt-font-medium"><?php echo esc_html($tx['payment_method'] ?? __('Bank Transfer', 'teeptrak-partner')); ?></span>
                                                <?php if (!empty($tx['reference'])) : ?>
                                                    <span class="tt-tx-ref"><?php echo esc_html($tx['reference']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="tt-tx-desc">
                                                <span class="tt-font-medium"><?php echo esc_html($tx['deal_company'] ?? '-'); ?></span>
                                                <?php if (!empty($tx['deal_value'])) : ?>
                                                    <span class="tt-tx-deal-value"><?php printf(__('Deal: %s', 'teeptrak-partner'), teeptrak_format_currency($tx['deal_value'])); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="tt-tx-amount <?php echo $amount < 0 ? 'is-negative' : 'is-positive'; ?>">
                                            <?php echo $amount < 0 ? '-' : '+'; ?><?php echo teeptrak_format_currency(abs($amount)); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="tt-status-badge" style="background: <?php echo esc_attr($status['bg']); ?>; color: <?php echo esc_attr($status['color']); ?>;">
                                            <?php echo esc_html($status['label']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="tt-empty-state tt-p-8">
                    <div class="tt-empty-icon">
                        <?php echo tt_commissions_icon('dollar-sign', 48); ?>
                    </div>
                    <h3 class="tt-empty-title"><?php _e('No transactions yet', 'teeptrak-partner'); ?></h3>
                    <p class="tt-empty-desc"><?php _e('Your commission history will appear here once deals are closed.', 'teeptrak-partner'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Commission Info -->
    <div class="tt-info-cards tt-mt-8">
        <div class="tt-info-card">
            <div class="tt-info-card-icon" style="background: #FEE2E2; color: #E63946;">
                <?php echo tt_commissions_icon('info', 24); ?>
            </div>
            <div class="tt-info-card-content">
                <h3><?php _e('How Commissions Work', 'teeptrak-partner'); ?></h3>
                <ul>
                    <li><?php _e('Commissions are calculated at your tier rate when deals close', 'teeptrak-partner'); ?></li>
                    <li><?php _e('Pending commissions are approved within 30 days of deal payment', 'teeptrak-partner'); ?></li>
                    <li><?php _e('Approved commissions can be withdrawn anytime (minimum €100)', 'teeptrak-partner'); ?></li>
                </ul>
            </div>
        </div>

        <div class="tt-info-card">
            <div class="tt-info-card-icon" style="background: #DBEAFE; color: #3B82F6;">
                <?php echo tt_commissions_icon('calendar', 24); ?>
            </div>
            <div class="tt-info-card-content">
                <h3><?php _e('Payment Schedule', 'teeptrak-partner'); ?></h3>
                <ul>
                    <li><?php _e('Payouts are processed twice monthly (1st and 15th)', 'teeptrak-partner'); ?></li>
                    <li><?php _e('Bank transfers typically arrive within 3-5 business days', 'teeptrak-partner'); ?></li>
                    <li><?php _e('PayPal payments are instant once approved', 'teeptrak-partner'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Payout Request Modal -->
<div class="tt-modal" id="tt-payout-modal">
    <div class="tt-modal-backdrop" id="tt-payout-modal-backdrop"></div>
    <div class="tt-modal-container">
        <div class="tt-modal-header">
            <h2 class="tt-modal-title"><?php _e('Request Payout', 'teeptrak-partner'); ?></h2>
            <button type="button" class="tt-modal-close" id="tt-payout-modal-close">
                <?php echo tt_commissions_icon('x', 20); ?>
            </button>
        </div>
        <form id="tt-payout-form" class="tt-modal-form">
            <?php wp_nonce_field('teeptrak_payout_nonce', 'payout_nonce'); ?>

            <div class="tt-modal-body">
                <div class="tt-payout-balance">
                    <span class="tt-payout-balance-label"><?php _e('Available Balance', 'teeptrak-partner'); ?></span>
                    <span class="tt-payout-balance-value"><?php echo teeptrak_format_currency($commission_summary['available']); ?></span>
                </div>

                <div class="tt-form-group">
                    <label for="payout_amount" class="tt-label"><?php _e('Withdrawal Amount', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                    <div class="tt-input-group">
                        <span class="tt-input-addon">&euro;</span>
                        <input type="number" id="payout_amount" name="amount" class="tt-input"
                            min="100" max="<?php echo esc_attr($commission_summary['available']); ?>"
                            step="0.01" required
                            placeholder="<?php esc_attr_e('Enter amount', 'teeptrak-partner'); ?>">
                    </div>
                    <p class="tt-form-hint"><?php _e('Minimum withdrawal: €100', 'teeptrak-partner'); ?></p>
                </div>

                <div class="tt-form-group">
                    <label for="payment_method" class="tt-label"><?php _e('Payment Method', 'teeptrak-partner'); ?></label>
                    <select id="payment_method" name="payment_method" class="tt-select">
                        <option value="bank_transfer"><?php _e('Bank Transfer (SEPA)', 'teeptrak-partner'); ?></option>
                        <option value="paypal"><?php _e('PayPal', 'teeptrak-partner'); ?></option>
                    </select>
                </div>

                <div id="tt-bank-details" class="tt-conditional-fields">
                    <div class="tt-form-group">
                        <label for="bank_iban" class="tt-label"><?php _e('IBAN', 'teeptrak-partner'); ?></label>
                        <input type="text" id="bank_iban" name="iban" class="tt-input" placeholder="<?php esc_attr_e('FR76 1234 5678 9012 3456 7890 123', 'teeptrak-partner'); ?>">
                    </div>
                    <div class="tt-form-group">
                        <label for="bank_bic" class="tt-label"><?php _e('BIC/SWIFT', 'teeptrak-partner'); ?></label>
                        <input type="text" id="bank_bic" name="bic" class="tt-input" placeholder="<?php esc_attr_e('BNPAFRPP', 'teeptrak-partner'); ?>">
                    </div>
                </div>

                <div id="tt-paypal-details" class="tt-conditional-fields" style="display: none;">
                    <div class="tt-form-group">
                        <label for="paypal_email" class="tt-label"><?php _e('PayPal Email', 'teeptrak-partner'); ?></label>
                        <input type="email" id="paypal_email" name="paypal_email" class="tt-input" placeholder="<?php esc_attr_e('your@email.com', 'teeptrak-partner'); ?>">
                    </div>
                </div>

                <div class="tt-form-notice">
                    <div class="tt-notice-icon">
                        <?php echo tt_commissions_icon('info', 18); ?>
                    </div>
                    <div class="tt-notice-content">
                        <p><?php _e('Payouts are processed within 2 business days. You will receive an email confirmation once the transfer is initiated.', 'teeptrak-partner'); ?></p>
                    </div>
                </div>
            </div>

            <div class="tt-modal-footer">
                <button type="button" class="tt-btn tt-btn-outline" id="tt-cancel-payout">
                    <?php _e('Cancel', 'teeptrak-partner'); ?>
                </button>
                <button type="submit" class="tt-btn tt-btn-primary" id="tt-submit-payout">
                    <?php _e('Request Payout', 'teeptrak-partner'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Commissions Page Styles */
.tt-commissions-page {
    max-width: 1000px;
}

.tt-page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--tt-gray-900);
    margin: 0 0 4px 0;
}

.tt-page-subtitle {
    font-size: 1rem;
    color: var(--tt-gray-500);
    margin: 0;
}

/* Summary Grid */
.tt-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 1024px) {
    .tt-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .tt-summary-grid {
        grid-template-columns: 1fr;
    }
}

.tt-summary-card {
    background: var(--tt-white);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--tt-gray-100);
}

.tt-summary-card-primary {
    background: linear-gradient(135deg, var(--tt-red) 0%, #C62828 100%);
    color: white;
    border: none;
}

.tt-summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.tt-summary-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.tt-summary-card:not(.tt-summary-card-primary) .tt-summary-label {
    color: var(--tt-gray-500);
}

.tt-summary-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tt-summary-value {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 8px;
}

.tt-summary-card:not(.tt-summary-card-primary) .tt-summary-value {
    color: var(--tt-gray-900);
}

.tt-text-amber { color: #F59E0B; }
.tt-text-success { color: #22C55E; }

.tt-summary-footer {
    font-size: 0.8125rem;
    opacity: 0.7;
}

.tt-summary-card:not(.tt-summary-card-primary) .tt-summary-footer {
    color: var(--tt-gray-500);
}

/* Period Stats */
.tt-period-stats {
    display: flex;
    gap: 16px;
}

@media (max-width: 640px) {
    .tt-period-stats {
        flex-direction: column;
    }
}

.tt-period-card {
    flex: 1;
    background: var(--tt-white);
    border-radius: 10px;
    padding: 16px 20px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tt-period-label {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
}

.tt-period-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
}

/* Transactions Table */
.tt-transactions-table th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--tt-gray-500);
}

.tt-tx-date {
    font-size: 0.875rem;
    color: var(--tt-gray-700);
}

.tt-tx-type {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
}

.tt-tx-type.is-commission {
    color: #22C55E;
}

.tt-tx-type.is-withdrawal {
    color: #F59E0B;
}

.tt-tx-desc {
    display: flex;
    flex-direction: column;
}

.tt-tx-ref,
.tt-tx-deal-value {
    font-size: 0.75rem;
    color: var(--tt-gray-400);
}

.tt-tx-amount {
    font-weight: 600;
    font-size: 0.9375rem;
}

.tt-tx-amount.is-positive {
    color: #22C55E;
}

.tt-tx-amount.is-negative {
    color: var(--tt-gray-700);
}

.tt-status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Info Cards */
.tt-info-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

@media (max-width: 768px) {
    .tt-info-cards {
        grid-template-columns: 1fr;
    }
}

.tt-info-card {
    background: var(--tt-white);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    gap: 16px;
}

.tt-info-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-info-card-content h3 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 12px 0;
}

.tt-info-card-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tt-info-card-content li {
    font-size: 0.8125rem;
    color: var(--tt-gray-600);
    padding-left: 16px;
    position: relative;
    margin-bottom: 6px;
    line-height: 1.5;
}

.tt-info-card-content li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: var(--tt-red);
}

/* Modal */
.tt-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.tt-modal.is-open {
    display: flex;
}

.tt-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
}

.tt-modal-container {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.tt-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--tt-gray-100);
}

.tt-modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0;
}

.tt-modal-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--tt-gray-400);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tt-modal-close:hover {
    background: var(--tt-gray-100);
    color: var(--tt-gray-700);
}

.tt-modal-body {
    padding: 24px;
    overflow-y: auto;
}

.tt-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--tt-gray-100);
}

/* Payout Balance */
.tt-payout-balance {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: var(--tt-gray-50);
    border-radius: 10px;
    margin-bottom: 24px;
}

.tt-payout-balance-label {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
}

.tt-payout-balance-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--tt-gray-900);
}

/* Form Styles */
.tt-form-group {
    margin-bottom: 16px;
}

.tt-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tt-gray-700);
    margin-bottom: 6px;
}

.tt-required {
    color: var(--tt-red);
}

.tt-input,
.tt-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--tt-gray-200);
    border-radius: 8px;
    font-size: 0.9375rem;
    font-family: inherit;
}

.tt-input:focus,
.tt-select:focus {
    outline: none;
    border-color: var(--tt-red);
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
}

.tt-input-group {
    display: flex;
}

.tt-input-addon {
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: var(--tt-gray-50);
    border: 1px solid var(--tt-gray-200);
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 0.9375rem;
    color: var(--tt-gray-500);
}

.tt-input-group .tt-input {
    border-radius: 0 8px 8px 0;
}

.tt-form-hint {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
    margin-top: 4px;
}

.tt-form-notice {
    display: flex;
    gap: 10px;
    padding: 12px;
    background: #F0F9FF;
    border-radius: 8px;
    margin-top: 16px;
}

.tt-form-notice .tt-notice-icon {
    color: #0284C7;
    flex-shrink: 0;
}

.tt-form-notice p {
    font-size: 0.8125rem;
    color: var(--tt-gray-600);
    margin: 0;
}

/* Empty State */
.tt-empty-state {
    text-align: center;
    padding: 48px 24px;
}

.tt-empty-icon {
    color: var(--tt-gray-300);
    margin-bottom: 16px;
}

.tt-empty-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 8px 0;
}

.tt-empty-desc {
    font-size: 0.9375rem;
    color: var(--tt-gray-500);
    margin: 0;
}

/* Button Sizes */
.tt-btn-sm {
    padding: 6px 12px;
    font-size: 0.8125rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tt-payout-modal');
    const openBtn = document.getElementById('tt-request-payout-btn');
    const closeBtns = document.querySelectorAll('#tt-payout-modal-close, #tt-cancel-payout, #tt-payout-modal-backdrop');
    const form = document.getElementById('tt-payout-form');
    const paymentMethodSelect = document.getElementById('payment_method');
    const bankDetails = document.getElementById('tt-bank-details');
    const paypalDetails = document.getElementById('tt-paypal-details');

    // Open modal
    if (openBtn && !openBtn.disabled) {
        openBtn.addEventListener('click', () => {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close modal
    closeBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            });
        }
    });

    // Toggle payment method fields
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'paypal') {
                bankDetails.style.display = 'none';
                paypalDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'block';
                paypalDetails.style.display = 'none';
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('tt-submit-payout');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<?php _e('Processing...', 'teeptrak-partner'); ?>';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                alert('<?php _e('Payout request submitted successfully! You will receive a confirmation email shortly.', 'teeptrak-partner'); ?>');
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                form.reset();
            }, 1500);
        });
    }

    // Export button
    const exportBtn = document.getElementById('tt-export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            alert('<?php _e('Export functionality will generate a CSV file with your transaction history.', 'teeptrak-partner'); ?>');
        });
    }
});
</script>

<?php get_footer(); ?>
