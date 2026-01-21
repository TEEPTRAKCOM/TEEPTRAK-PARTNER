<?php
/**
 * Template part for training module card
 *
 * @package TeepTrak_Partner_Theme_2026
 *
 * @var array $args {
 *     @type array $module   Module data array
 *     @type int   $progress User's progress percentage
 *     @type string $status  Module status (locked, not_started, in_progress, completed)
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$module = isset($args['module']) ? $args['module'] : array();
$progress = isset($args['progress']) ? (int) $args['progress'] : 0;
$status = isset($args['status']) ? $args['status'] : 'not_started';

if (empty($module)) {
    return;
}

$is_locked = $status === 'locked';
$is_completed = $progress >= 100;
?>

<div class="tt-module-card <?php echo $is_locked ? 'is-locked' : ''; ?>">
    <div class="tt-module-thumbnail" style="<?php echo $is_locked ? 'opacity: 0.5;' : ''; ?>">
        <?php if ($is_locked) : ?>
            <?php echo teeptrak_icon('lock', 48); ?>
        <?php else : ?>
            <?php echo teeptrak_icon('play', 48); ?>
        <?php endif; ?>

        <?php if ($is_completed) : ?>
            <span class="tt-module-badge tt-badge tt-badge-success">
                <?php echo teeptrak_icon('check', 12); ?>
                <?php esc_html_e('Completed', 'teeptrak-partner'); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="tt-module-content">
        <div class="tt-module-meta">
            <span class="tt-module-duration">
                <?php echo teeptrak_icon('clock', 14); ?>
                <?php echo esc_html($module['duration']); ?> <?php esc_html_e('min', 'teeptrak-partner'); ?>
            </span>
            <span class="tt-module-level">
                <?php echo esc_html($module['level']); ?>
            </span>
        </div>

        <h3 class="tt-module-title"><?php echo esc_html($module['title']); ?></h3>
        <p class="tt-module-desc"><?php echo esc_html($module['description']); ?></p>

        <?php if (!$is_locked && !$is_completed && $progress > 0) : ?>
            <div class="tt-module-progress tt-mb-4">
                <?php teeptrak_progress_bar($progress, 'tt-progress-sm'); ?>
                <span class="tt-text-xs tt-text-gray-500 tt-mt-1"><?php echo esc_html($progress); ?>% <?php esc_html_e('complete', 'teeptrak-partner'); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($is_locked) : ?>
            <button class="tt-btn tt-btn-secondary tt-btn-sm tt-w-full" disabled>
                <?php echo teeptrak_icon('lock', 14); ?>
                <?php esc_html_e('Complete previous level', 'teeptrak-partner'); ?>
            </button>
        <?php elseif ($is_completed) : ?>
            <button class="tt-btn tt-btn-secondary tt-btn-sm tt-w-full">
                <?php esc_html_e('Review', 'teeptrak-partner'); ?>
            </button>
        <?php elseif ($progress > 0) : ?>
            <button class="tt-btn tt-btn-primary tt-btn-sm tt-w-full">
                <?php esc_html_e('Continue', 'teeptrak-partner'); ?>
            </button>
        <?php else : ?>
            <button class="tt-btn tt-btn-primary tt-btn-sm tt-w-full">
                <?php esc_html_e('Start', 'teeptrak-partner'); ?>
            </button>
        <?php endif; ?>
    </div>
</div>
