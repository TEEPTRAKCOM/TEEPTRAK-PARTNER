<?php
/**
 * Template part for deal card (mobile view)
 *
 * @package TeepTrak_Partner_Theme_2026
 *
 * @var array $args {
 *     @type array $deal Deal data array
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$deal = isset($args['deal']) ? $args['deal'] : array();

if (empty($deal)) {
    return;
}

$days_left = teeptrak_get_protection_days_remaining($deal['protection_expires']);
$protection_percent = ($days_left / 90) * 100;
?>

<div class="tt-deal-card" data-deal-id="<?php echo esc_attr($deal['id']); ?>">
    <div class="tt-deal-card-header">
        <h3 class="tt-deal-card-company"><?php echo esc_html($deal['company_name']); ?></h3>
        <?php teeptrak_stage_badge($deal['stage']); ?>
    </div>

    <div class="tt-deal-card-body">
        <div class="tt-deal-card-row">
            <span class="tt-deal-card-label"><?php esc_html_e('Contact', 'teeptrak-partner'); ?></span>
            <span class="tt-deal-card-value"><?php echo esc_html($deal['contact_name']); ?></span>
        </div>

        <div class="tt-deal-card-row">
            <span class="tt-deal-card-label"><?php esc_html_e('Value', 'teeptrak-partner'); ?></span>
            <span class="tt-deal-card-value tt-font-semibold">
                <?php echo esc_html(teeptrak_format_currency($deal['deal_value'])); ?>
            </span>
        </div>

        <div class="tt-deal-card-row">
            <span class="tt-deal-card-label"><?php esc_html_e('Product', 'teeptrak-partner'); ?></span>
            <span class="tt-deal-card-value"><?php echo esc_html($deal['product_interest']); ?></span>
        </div>

        <div class="tt-deal-card-protection">
            <div class="tt-flex tt-justify-between tt-text-sm tt-mb-1">
                <span><?php esc_html_e('Protection', 'teeptrak-partner'); ?></span>
                <span class="<?php echo $days_left <= 14 ? 'tt-text-red' : 'tt-text-gray-500'; ?>">
                    <?php
                    printf(
                        /* translators: %d: number of days */
                        esc_html__('%d days left', 'teeptrak-partner'),
                        $days_left
                    );
                    ?>
                </span>
            </div>
            <?php teeptrak_protection_bar($days_left); ?>
        </div>
    </div>

    <div class="tt-deal-card-footer">
        <span class="tt-text-xs tt-text-gray-500">
            <?php
            printf(
                /* translators: %s: date */
                esc_html__('Registered %s', 'teeptrak-partner'),
                esc_html(date_i18n(get_option('date_format'), strtotime($deal['created_at'])))
            );
            ?>
        </span>
        <button class="tt-btn tt-btn-sm tt-btn-secondary" data-action="view-deal" data-deal-id="<?php echo esc_attr($deal['id']); ?>">
            <?php esc_html_e('View', 'teeptrak-partner'); ?>
        </button>
    </div>
</div>
