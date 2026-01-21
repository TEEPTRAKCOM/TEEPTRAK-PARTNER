<?php
/**
 * Template Name: Commissions
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$partner = teeptrak_get_current_partner();
$transactions = teeptrak_get_partner_transactions();
$tier_config = teeptrak_get_tier_config($partner['tier']);
?>

<!-- Page Header -->
<div class="tt-page-header">
    <h1 class="tt-page-title"><?php esc_html_e('My Commissions', 'teeptrak-partner'); ?></h1>
    <p class="tt-page-subtitle"><?php esc_html_e('Track your earnings and request withdrawals', 'teeptrak-partner'); ?></p>
</div>

<!-- Commission Summary Cards -->
<div class="tt-commission-cards">
    <!-- Available Balance -->
    <div class="tt-commission-card is-primary">
        <div class="tt-commission-label"><?php esc_html_e('Available Balance', 'teeptrak-partner'); ?></div>
        <div class="tt-commission-value"><?php echo esc_html(teeptrak_format_currency($partner['available_balance'])); ?></div>
        <div class="tt-commission-subtext"><?php esc_html_e('Ready for withdrawal', 'teeptrak-partner'); ?></div>
        <?php if ($partner['available_balance'] >= 500) : ?>
            <button class="tt-btn tt-btn-white tt-btn-sm tt-mt-4">
                <?php esc_html_e('Request Withdrawal', 'teeptrak-partner'); ?>
            </button>
        <?php endif; ?>
    </div>

    <!-- Pending Commissions -->
    <div class="tt-commission-card is-secondary">
        <div class="tt-commission-label"><?php esc_html_e('Pending Commissions', 'teeptrak-partner'); ?></div>
        <div class="tt-commission-value" style="color: #F59E0B;"><?php echo esc_html(teeptrak_format_currency($partner['pending_commissions'])); ?></div>
        <div class="tt-commission-subtext"><?php esc_html_e('Awaiting deal closure or approval', 'teeptrak-partner'); ?></div>
    </div>

    <!-- Total Paid -->
    <div class="tt-commission-card is-secondary">
        <div class="tt-commission-label"><?php esc_html_e('Total Paid (All Time)', 'teeptrak-partner'); ?></div>
        <div class="tt-commission-value"><?php echo esc_html(teeptrak_format_currency($partner['total_paid'])); ?></div>
        <div class="tt-commission-subtext">
            <?php
            printf(
                /* translators: %s: join date */
                esc_html__('Since %s', 'teeptrak-partner'),
                esc_html(date_i18n(get_option('date_format'), strtotime($partner['join_date'])))
            );
            ?>
        </div>
    </div>
</div>

<!-- Commission Rate Info -->
<div class="tt-rate-bar">
    <div class="tt-rate-info">
        <?php teeptrak_tier_badge($partner['tier']); ?>
        <div class="tt-rate-text">
            <?php esc_html_e('Your Commission Rate:', 'teeptrak-partner'); ?>
            <strong><?php echo esc_html($partner['commission_rate']); ?>%</strong>
        </div>
    </div>
    <a href="#commission-structure" class="tt-text-sm tt-text-red">
        <?php esc_html_e('How to increase your rate', 'teeptrak-partner'); ?> &rarr;
    </a>
</div>

<!-- Transaction History -->
<div class="tt-card tt-mt-6">
    <div class="tt-card-header tt-flex tt-justify-between tt-items-center">
        <h2 class="tt-text-lg tt-font-semibold tt-m-0"><?php esc_html_e('Transaction History', 'teeptrak-partner'); ?></h2>
    </div>

    <?php if (empty($transactions)) : ?>
        <div class="tt-empty-state">
            <div class="tt-empty-icon">
                <?php echo teeptrak_icon('dollar-sign', 80); ?>
            </div>
            <h3 class="tt-empty-title"><?php esc_html_e('No commissions yet', 'teeptrak-partner'); ?></h3>
            <p class="tt-empty-text"><?php esc_html_e('Close your first deal to start earning commissions', 'teeptrak-partner'); ?></p>
            <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-primary">
                <?php esc_html_e('Register a Deal', 'teeptrak-partner'); ?>
            </a>
        </div>
    <?php else : ?>
        <div class="tt-table-wrapper">
            <table class="tt-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Date', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Type', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Description', 'teeptrak-partner'); ?></th>
                        <th class="tt-text-right"><?php esc_html_e('Amount', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Status', 'teeptrak-partner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $txn) :
                        $is_commission = $txn['type'] === 'commission';
                        $amount = (float) $txn['amount'];
                    ?>
                        <tr>
                            <td class="tt-text-gray-500">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($txn['date']))); ?>
                            </td>
                            <td>
                                <?php if ($is_commission) : ?>
                                    <span class="tt-text-success tt-font-semibold">&darr; <?php esc_html_e('Commission', 'teeptrak-partner'); ?></span>
                                <?php else : ?>
                                    <span class="tt-text-red tt-font-semibold">&uarr; <?php esc_html_e('Withdrawal', 'teeptrak-partner'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($txn['description']); ?></td>
                            <td class="tt-text-right tt-font-semibold <?php echo $is_commission ? 'tt-text-success' : 'tt-text-red'; ?>">
                                <?php echo $is_commission ? '+' : '-'; ?><?php echo esc_html(teeptrak_format_currency(abs($amount))); ?>
                            </td>
                            <td>
                                <?php
                                $status_classes = array(
                                    'paid'       => 'tt-badge-success',
                                    'pending'    => 'tt-badge-warning',
                                    'processing' => 'tt-badge-info',
                                    'approved'   => 'tt-badge-success',
                                );
                                $status_class = isset($status_classes[$txn['status']]) ? $status_classes[$txn['status']] : 'tt-badge-gray';
                                ?>
                                <span class="tt-badge <?php echo esc_attr($status_class); ?>">
                                    <?php echo esc_html(ucfirst($txn['status'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Commission Structure -->
<div class="tt-card tt-mt-6" id="commission-structure">
    <div class="tt-card-header">
        <h2 class="tt-text-lg tt-font-semibold tt-m-0"><?php esc_html_e('Commission Structure', 'teeptrak-partner'); ?></h2>
    </div>
    <div class="tt-card-body">
        <div class="tt-table-wrapper tt-mb-6">
            <table class="tt-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Tier', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Rate', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Requirements', 'teeptrak-partner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (teeptrak_get_tier_config() as $tier_key => $tier) : ?>
                        <tr class="<?php echo $tier_key === $partner['tier'] ? 'tt-bg-gray-50' : ''; ?>">
                            <td>
                                <?php teeptrak_tier_badge($tier_key, 'sm'); ?>
                                <?php if ($tier_key === $partner['tier']) : ?>
                                    <span class="tt-badge tt-badge-info tt-ml-2"><?php esc_html_e('Current', 'teeptrak-partner'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="tt-font-bold"><?php echo esc_html($tier['commission_rate']); ?>%</td>
                            <td class="tt-text-gray-600"><?php echo esc_html($tier['requirements']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tt-bg-gray-50 tt-rounded-lg tt-p-4">
            <h4 class="tt-font-semibold tt-mb-2"><?php esc_html_e('Important Notes', 'teeptrak-partner'); ?></h4>
            <ul class="tt-text-sm tt-text-gray-600" style="list-style: disc; padding-left: 1.5rem; margin: 0;">
                <li><?php esc_html_e('Commissions calculated on net contract value', 'teeptrak-partner'); ?></li>
                <li><?php esc_html_e('Paid within 30 days of deal closure confirmation', 'teeptrak-partner'); ?></li>
                <li><?php esc_html_e('Recurring commissions for multi-year contracts (at 50% rate in years 2+)', 'teeptrak-partner'); ?></li>
                <li><?php esc_html_e('Minimum withdrawal amount: €500', 'teeptrak-partner'); ?></li>
            </ul>
        </div>
    </div>
</div>

<?php get_footer(); ?>
