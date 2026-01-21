<?php
/**
 * Template part for tier card
 *
 * @package TeepTrak_Partner_Theme_2026
 *
 * @var array $args {
 *     @type string $tier        Tier key (bronze, silver, gold, platinum)
 *     @type array  $config      Tier configuration
 *     @type bool   $is_current  Whether this is the current user's tier
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$tier = isset($args['tier']) ? $args['tier'] : 'bronze';
$config = isset($args['config']) ? $args['config'] : teeptrak_get_tier_config($tier);
$is_current = isset($args['is_current']) ? $args['is_current'] : false;
?>

<div class="tt-tier-card <?php echo $is_current ? 'is-current' : ''; ?>">
    <?php if ($is_current) : ?>
        <div class="tt-tier-current-badge"><?php esc_html_e('Current', 'teeptrak-partner'); ?></div>
    <?php endif; ?>

    <div class="tt-tier-header">
        <?php teeptrak_tier_badge($tier, 'lg'); ?>
    </div>

    <div class="tt-tier-body">
        <div class="tt-tier-rate">
            <span class="tt-tier-rate-value"><?php echo esc_html($config['commission_rate']); ?>%</span>
            <span class="tt-tier-rate-label"><?php esc_html_e('Commission', 'teeptrak-partner'); ?></span>
        </div>

        <p class="tt-tier-requirements"><?php echo esc_html($config['requirements']); ?></p>

        <?php if (!empty($config['benefits'])) : ?>
            <ul class="tt-tier-benefits">
                <?php foreach ($config['benefits'] as $benefit) : ?>
                    <li>
                        <?php echo teeptrak_icon('check', 16); ?>
                        <?php echo esc_html($benefit); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
