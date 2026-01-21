<?php
/**
 * LearnPress Integration for TeepTrak Partner Theme
 *
 * @package TeepTrak_Partner_Theme_2026
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if LearnPress is active
 */
function teeptrak_learnpress_active() {
    return class_exists('LearnPress') || function_exists('learn_press_get_user');
}

/**
 * Get LearnPress user progress
 */
function teeptrak_get_lp_progress($user_id = null) {
    if (!teeptrak_learnpress_active()) {
        return null;
    }

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return null;
    }

    $user = learn_press_get_user($user_id);

    if (!$user) {
        return null;
    }

    // Get enrolled courses
    $courses = $user->get_enrolled_courses_id();

    $progress = array(
        'total_courses'     => count($courses),
        'completed_courses' => 0,
        'in_progress'       => 0,
        'courses'           => array(),
    );

    foreach ($courses as $course_id) {
        $course_data = $user->get_course_data($course_id);

        if ($course_data) {
            $status = $course_data->get_status();
            $percent = $course_data->get_percent_result();

            $course_info = array(
                'id'      => $course_id,
                'title'   => get_the_title($course_id),
                'status'  => $status,
                'percent' => $percent,
            );

            $progress['courses'][] = $course_info;

            if ($status === 'completed' || $percent >= 100) {
                $progress['completed_courses']++;
            } elseif ($status === 'enrolled') {
                $progress['in_progress']++;
            }
        }
    }

    $progress['overall_percent'] = $progress['total_courses'] > 0
        ? round(($progress['completed_courses'] / $progress['total_courses']) * 100)
        : 0;

    return $progress;
}

/**
 * Sync LearnPress completion with TeepTrak training progress
 */
function teeptrak_sync_lp_progress($user_id) {
    if (!teeptrak_learnpress_active()) {
        return;
    }

    $lp_progress = teeptrak_get_lp_progress($user_id);

    if (!$lp_progress) {
        return;
    }

    // Get TeepTrak training progress
    $tt_progress = get_user_meta($user_id, 'teeptrak_training_progress', true);
    if (!is_array($tt_progress)) {
        $tt_progress = array();
    }

    // Map LearnPress courses to TeepTrak modules
    // This requires custom meta on LearnPress courses to link them
    foreach ($lp_progress['courses'] as $course) {
        $module_id = get_post_meta($course['id'], '_teeptrak_module_id', true);

        if ($module_id) {
            $tt_progress[$module_id] = (int) $course['percent'];
        }
    }

    update_user_meta($user_id, 'teeptrak_training_progress', $tt_progress);

    // Update partner score
    teeptrak_update_partner_score($user_id);
}

/**
 * Hook into LearnPress course completion
 */
function teeptrak_on_lp_course_complete($course_id, $user_id, $course_results) {
    // Sync progress
    teeptrak_sync_lp_progress($user_id);

    // Check if this is a certification course
    $is_certification = get_post_meta($course_id, '_teeptrak_is_certification', true);

    if ($is_certification) {
        // Update onboarding step
        $current_step = (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true);
        if ($current_step < 6) {
            update_user_meta($user_id, 'teeptrak_onboarding_step', 6);
        }
    }

    // Check for tier upgrade
    teeptrak_check_tier_upgrade($user_id);
}

if (teeptrak_learnpress_active()) {
    add_action('learn-press/user-course-finished', 'teeptrak_on_lp_course_complete', 10, 3);
}

/**
 * Shortcode to display LearnPress courses in TeepTrak style
 */
function teeptrak_lp_courses_shortcode($atts) {
    if (!teeptrak_learnpress_active()) {
        return '<p>' . __('LearnPress is not active. Please install and activate LearnPress to use this feature.', 'teeptrak-partner') . '</p>';
    }

    $atts = shortcode_atts(array(
        'limit' => 8,
    ), $atts);

    $args = array(
        'post_type'      => 'lp_course',
        'posts_per_page' => $atts['limit'],
        'post_status'    => 'publish',
    );

    $courses = new WP_Query($args);

    if (!$courses->have_posts()) {
        return '<p>' . __('No courses found.', 'teeptrak-partner') . '</p>';
    }

    ob_start();
    ?>
    <div class="tt-modules-grid">
        <?php while ($courses->have_posts()) : $courses->the_post(); ?>
            <?php
            $course_id = get_the_ID();
            $duration = get_post_meta($course_id, '_lp_duration', true);
            $level = get_post_meta($course_id, '_lp_level', true) ?: 'beginner';
            ?>
            <div class="tt-module-card">
                <div class="tt-module-thumbnail">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('teeptrak-card'); ?>
                    <?php else : ?>
                        <?php echo teeptrak_icon('play', 48); ?>
                    <?php endif; ?>
                </div>
                <div class="tt-module-content">
                    <div class="tt-module-meta">
                        <?php if ($duration) : ?>
                            <span class="tt-module-duration">
                                <?php echo teeptrak_icon('clock', 14); ?>
                                <?php echo esc_html($duration); ?>
                            </span>
                        <?php endif; ?>
                        <span class="tt-module-level"><?php echo esc_html(ucfirst($level)); ?></span>
                    </div>
                    <h3 class="tt-module-title"><?php the_title(); ?></h3>
                    <p class="tt-module-desc"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    <a href="<?php the_permalink(); ?>" class="tt-btn tt-btn-primary tt-btn-sm tt-w-full">
                        <?php esc_html_e('View Course', 'teeptrak-partner'); ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('teeptrak_courses', 'teeptrak_lp_courses_shortcode');

/**
 * Shortcode to display training progress
 */
function teeptrak_training_progress_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '';
    }

    $progress = teeptrak_get_training_progress();

    ob_start();
    ?>
    <div class="tt-training-progress">
        <div class="tt-training-progress-header">
            <h3 class="tt-training-progress-title"><?php esc_html_e('Your Progress', 'teeptrak-partner'); ?></h3>
            <span class="tt-training-progress-percent"><?php echo esc_html($progress['percent']); ?>%</span>
        </div>
        <div class="tt-training-progress-bar">
            <div class="tt-training-progress-fill" style="width: <?php echo esc_attr($progress['percent']); ?>%"></div>
        </div>
        <p class="tt-training-progress-text">
            <?php
            printf(
                /* translators: 1: completed count, 2: total count */
                esc_html__('%1$d of %2$d modules completed', 'teeptrak-partner'),
                $progress['completed'],
                $progress['total']
            );
            ?>
        </p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('teeptrak_training_progress', 'teeptrak_training_progress_shortcode');

/**
 * Add LearnPress styling overrides
 */
function teeptrak_lp_styles() {
    if (!teeptrak_learnpress_active()) {
        return;
    }

    if (is_singular('lp_course') || is_post_type_archive('lp_course')) {
        ?>
        <style>
            .learn-press-content .course-item {
                background-color: var(--tt-white);
                border-radius: var(--tt-radius-xl);
                overflow: hidden;
                border: 1px solid var(--tt-gray-100);
            }

            .learn-press-content .course-item:hover {
                box-shadow: var(--tt-shadow-md);
            }

            .learn-press-content .lp-button,
            .learn-press-content .button {
                background-color: var(--tt-red) !important;
                color: var(--tt-white) !important;
                border-radius: var(--tt-radius-lg) !important;
                padding: var(--tt-space-3) var(--tt-space-5) !important;
                font-weight: 600 !important;
            }

            .learn-press-content .lp-button:hover,
            .learn-press-content .button:hover {
                background-color: var(--tt-red-hover) !important;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'teeptrak_lp_styles');
