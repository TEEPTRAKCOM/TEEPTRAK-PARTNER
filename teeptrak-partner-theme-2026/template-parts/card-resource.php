<?php
/**
 * Template part for resource card
 *
 * @package TeepTrak_Partner_Theme_2026
 *
 * @var array $args {
 *     @type array  $resource   Resource data array
 *     @type string $user_tier  Current user's tier
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$resource = isset($args['resource']) ? $args['resource'] : array();
$user_tier = isset($args['user_tier']) ? $args['user_tier'] : 'bronze';

if (empty($resource)) {
    return;
}

$has_access = teeptrak_tier_has_access($user_tier, $resource['min_tier']);
$colors = teeptrak_get_file_type_colors($resource['type']);
?>

<div class="tt-resource-card <?php echo !$has_access ? 'is-locked' : ''; ?>" data-category="<?php echo esc_attr($resource['category']); ?>">
    <div class="tt-resource-header">
        <div class="tt-resource-icon" style="background-color: <?php echo esc_attr($colors['bg']); ?>; color: <?php echo esc_attr($colors['color']); ?>;">
            <?php echo teeptrak_resource_icon($resource['type']); ?>
        </div>
        <div>
            <h3 class="tt-resource-title"><?php echo esc_html($resource['title']); ?></h3>
            <div class="tt-resource-meta">
                <?php echo esc_html($resource['type']); ?> &middot; <?php echo esc_html($resource['size']); ?>
            </div>
        </div>
    </div>

    <p class="tt-resource-desc"><?php echo esc_html($resource['description']); ?></p>

    <div class="tt-resource-footer">
        <?php if ($has_access) : ?>
            <button class="tt-btn tt-btn-primary tt-btn-sm">
                <?php echo teeptrak_icon('download', 14); ?>
                <?php esc_html_e('Download', 'teeptrak-partner'); ?>
            </button>
        <?php else : ?>
            <span class="tt-badge tt-badge-warning">
                <?php echo teeptrak_icon('lock', 12); ?>
                <?php echo esc_html(ucfirst($resource['min_tier'])); ?>+
            </span>
            <span class="tt-text-xs tt-text-gray-500"><?php esc_html_e('Upgrade to access', 'teeptrak-partner'); ?></span>
        <?php endif; ?>
    </div>
</div>
