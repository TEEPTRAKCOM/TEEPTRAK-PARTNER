<?php
/**
 * Template Name: Training
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$partner = teeptrak_get_current_partner();
$training = teeptrak_get_training_progress();
$modules = teeptrak_get_training_modules();
$learnpress_active = teeptrak_learnpress_active();
?>

<!-- Page Header -->
<div class="tt-page-header">
    <h1 class="tt-page-title"><?php esc_html_e('Training Center', 'teeptrak-partner'); ?></h1>
    <p class="tt-page-subtitle">
        <?php
        printf(
            /* translators: 1: completed count, 2: total count */
            esc_html__('%1$d of %2$d modules completed', 'teeptrak-partner'),
            $training['completed'],
            $training['total']
        );
        ?>
    </p>
</div>

<!-- Overall Progress -->
<div class="tt-training-progress tt-mb-8">
    <div class="tt-training-progress-header">
        <h3 class="tt-training-progress-title"><?php esc_html_e('Your Progress', 'teeptrak-partner'); ?></h3>
        <span class="tt-training-progress-percent"><?php echo esc_html($training['percent']); ?>%</span>
    </div>
    <div class="tt-training-progress-bar">
        <div class="tt-training-progress-fill" style="width: <?php echo esc_attr($training['percent']); ?>%"></div>
    </div>
    <p class="tt-training-progress-text">
        <?php esc_html_e('Complete all modules to unlock your certification', 'teeptrak-partner'); ?>
    </p>
</div>

<?php if ($learnpress_active) : ?>
    <!-- LearnPress Integration -->
    <div class="tt-card tt-mb-8">
        <div class="tt-card-header">
            <h2 class="tt-text-lg tt-font-semibold tt-m-0"><?php esc_html_e('Available Courses', 'teeptrak-partner'); ?></h2>
        </div>
        <div class="tt-card-body">
            <?php echo do_shortcode('[teeptrak_courses limit="8"]'); ?>
        </div>
    </div>
<?php else : ?>
    <!-- Demo Training Modules -->
    <h2 class="tt-text-xl tt-font-semibold tt-mb-4"><?php esc_html_e('Training Modules', 'teeptrak-partner'); ?></h2>

    <div class="tt-modules-grid">
        <?php foreach ($modules as $module) :
            $module_progress = isset($training['modules'][$module['id']]) ? $training['modules'][$module['id']] : 0;
            $status = teeptrak_get_module_status($module['id']);
            $is_locked = $status === 'locked';
            $is_completed = $module_progress >= 100;
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

                    <?php if (!$is_locked && !$is_completed && $module_progress > 0) : ?>
                        <div class="tt-module-progress tt-mb-4">
                            <?php teeptrak_progress_bar($module_progress, 'tt-progress-sm'); ?>
                            <span class="tt-text-xs tt-text-gray-500 tt-mt-1"><?php echo esc_html($module_progress); ?>% <?php esc_html_e('complete', 'teeptrak-partner'); ?></span>
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
                    <?php elseif ($module_progress > 0) : ?>
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
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Certification Levels -->
<h2 class="tt-text-xl tt-font-semibold tt-mt-8 tt-mb-4"><?php esc_html_e('Certification Levels', 'teeptrak-partner'); ?></h2>

<div class="tt-grid tt-gap-4" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
    <!-- Level 1 -->
    <div class="tt-card">
        <div class="tt-card-body">
            <div class="tt-flex tt-items-center tt-gap-3 tt-mb-4">
                <div class="tt-tier-badge tt-tier-badge-bronze"><?php esc_html_e('Level 1', 'teeptrak-partner'); ?></div>
            </div>
            <h3 class="tt-text-lg tt-font-semibold tt-mb-2"><?php esc_html_e('Certified Partner', 'teeptrak-partner'); ?></h3>
            <p class="tt-text-sm tt-text-gray-500 tt-mb-4"><?php esc_html_e('3 modules | ~2 hours | 70% passing score', 'teeptrak-partner'); ?></p>
            <?php if ($training['completed'] >= 3) : ?>
                <span class="tt-badge tt-badge-success"><?php esc_html_e('Completed', 'teeptrak-partner'); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Level 2 -->
    <div class="tt-card">
        <div class="tt-card-body">
            <div class="tt-flex tt-items-center tt-gap-3 tt-mb-4">
                <div class="tt-tier-badge tt-tier-badge-silver"><?php esc_html_e('Level 2', 'teeptrak-partner'); ?></div>
            </div>
            <h3 class="tt-text-lg tt-font-semibold tt-mb-2"><?php esc_html_e('Certified Sales Partner', 'teeptrak-partner'); ?></h3>
            <p class="tt-text-sm tt-text-gray-500 tt-mb-4"><?php esc_html_e('3 modules | ~3 hours | 80% passing score', 'teeptrak-partner'); ?></p>
            <?php if ($training['completed'] >= 6) : ?>
                <span class="tt-badge tt-badge-success"><?php esc_html_e('Completed', 'teeptrak-partner'); ?></span>
            <?php elseif ($training['completed'] < 3) : ?>
                <span class="tt-badge tt-badge-gray"><?php esc_html_e('Locked', 'teeptrak-partner'); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Level 3 -->
    <div class="tt-card">
        <div class="tt-card-body">
            <div class="tt-flex tt-items-center tt-gap-3 tt-mb-4">
                <div class="tt-tier-badge tt-tier-badge-gold"><?php esc_html_e('Level 3', 'teeptrak-partner'); ?></div>
            </div>
            <h3 class="tt-text-lg tt-font-semibold tt-mb-2"><?php esc_html_e('Certified Implementation Partner', 'teeptrak-partner'); ?></h3>
            <p class="tt-text-sm tt-text-gray-500 tt-mb-4"><?php esc_html_e('2 modules | ~4 hours | 85% passing score', 'teeptrak-partner'); ?></p>
            <?php if ($training['completed'] >= 8) : ?>
                <span class="tt-badge tt-badge-success"><?php esc_html_e('Completed', 'teeptrak-partner'); ?></span>
            <?php elseif ($training['completed'] < 6) : ?>
                <span class="tt-badge tt-badge-gray"><?php esc_html_e('Locked', 'teeptrak-partner'); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
