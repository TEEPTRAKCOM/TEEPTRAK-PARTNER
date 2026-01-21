<?php
/**
 * Template Name: Training Modules
 * Partner training and certification page (LearnPress-ready)
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

// Demo training data from content.json
$training_tracks = array(
    'foundation' => array(
        'title' => __('Foundation Track', 'teeptrak-partner'),
        'description' => __('Essential knowledge for all partners', 'teeptrak-partner'),
        'required' => true,
        'modules' => array(
            array(
                'id' => 1,
                'title' => __('Introduction to TeepTrak', 'teeptrak-partner'),
                'description' => __('Company overview, mission, and product portfolio', 'teeptrak-partner'),
                'duration' => '15 min',
                'type' => 'video',
                'status' => 'completed',
                'progress' => 100,
            ),
            array(
                'id' => 2,
                'title' => __('OEE Fundamentals', 'teeptrak-partner'),
                'description' => __('Understanding OEE metrics, calculations, and industry benchmarks', 'teeptrak-partner'),
                'duration' => '30 min',
                'type' => 'video',
                'status' => 'completed',
                'progress' => 100,
            ),
            array(
                'id' => 3,
                'title' => __('Product Deep Dive', 'teeptrak-partner'),
                'description' => __('Detailed walkthrough of TeepTrak hardware and software solutions', 'teeptrak-partner'),
                'duration' => '45 min',
                'type' => 'video',
                'status' => 'completed',
                'progress' => 100,
            ),
            array(
                'id' => 4,
                'title' => __('Foundation Assessment', 'teeptrak-partner'),
                'description' => __('Test your knowledge of TeepTrak fundamentals', 'teeptrak-partner'),
                'duration' => '20 min',
                'type' => 'quiz',
                'status' => 'in_progress',
                'progress' => 60,
            ),
        ),
    ),
    'sales' => array(
        'title' => __('Sales Track', 'teeptrak-partner'),
        'description' => __('Sales methodology and techniques', 'teeptrak-partner'),
        'required' => true,
        'modules' => array(
            array(
                'id' => 5,
                'title' => __('Identifying Opportunities', 'teeptrak-partner'),
                'description' => __('Learn to identify ideal prospects and qualification criteria', 'teeptrak-partner'),
                'duration' => '25 min',
                'type' => 'video',
                'status' => 'locked',
                'progress' => 0,
            ),
            array(
                'id' => 6,
                'title' => __('Value Proposition & Messaging', 'teeptrak-partner'),
                'description' => __('Crafting compelling value propositions for different personas', 'teeptrak-partner'),
                'duration' => '30 min',
                'type' => 'video',
                'status' => 'locked',
                'progress' => 0,
            ),
            array(
                'id' => 7,
                'title' => __('Objection Handling', 'teeptrak-partner'),
                'description' => __('Common objections and proven response strategies', 'teeptrak-partner'),
                'duration' => '20 min',
                'type' => 'video',
                'status' => 'locked',
                'progress' => 0,
            ),
            array(
                'id' => 8,
                'title' => __('Sales Certification Exam', 'teeptrak-partner'),
                'description' => __('Final exam for sales certification', 'teeptrak-partner'),
                'duration' => '30 min',
                'type' => 'quiz',
                'status' => 'locked',
                'progress' => 0,
            ),
        ),
    ),
    'technical' => array(
        'title' => __('Technical Track', 'teeptrak-partner'),
        'description' => __('Installation and technical support', 'teeptrak-partner'),
        'required' => false,
        'modules' => array(
            array(
                'id' => 9,
                'title' => __('Hardware Installation', 'teeptrak-partner'),
                'description' => __('Step-by-step installation procedures for sensors and gateways', 'teeptrak-partner'),
                'duration' => '40 min',
                'type' => 'video',
                'status' => 'not_started',
                'progress' => 0,
            ),
            array(
                'id' => 10,
                'title' => __('Software Configuration', 'teeptrak-partner'),
                'description' => __('Dashboard setup, alerts, and custom reporting', 'teeptrak-partner'),
                'duration' => '35 min',
                'type' => 'video',
                'status' => 'not_started',
                'progress' => 0,
            ),
            array(
                'id' => 11,
                'title' => __('Troubleshooting Guide', 'teeptrak-partner'),
                'description' => __('Common issues and resolution procedures', 'teeptrak-partner'),
                'duration' => '25 min',
                'type' => 'video',
                'status' => 'not_started',
                'progress' => 0,
            ),
            array(
                'id' => 12,
                'title' => __('Technical Certification', 'teeptrak-partner'),
                'description' => __('Hands-on technical certification exam', 'teeptrak-partner'),
                'duration' => '45 min',
                'type' => 'quiz',
                'status' => 'not_started',
                'progress' => 0,
            ),
        ),
    ),
);

// Calculate overall progress
$total_modules = 0;
$completed_modules = 0;
foreach ($training_tracks as $track) {
    foreach ($track['modules'] as $module) {
        $total_modules++;
        if ($module['status'] === 'completed') {
            $completed_modules++;
        }
    }
}
$overall_progress = $total_modules > 0 ? round(($completed_modules / $total_modules) * 100) : 0;

// Certifications
$certifications = array(
    array(
        'title' => __('TeepTrak Certified Partner', 'teeptrak-partner'),
        'description' => __('Foundation + Sales tracks completed', 'teeptrak-partner'),
        'earned' => false,
        'date' => null,
        'icon' => 'award',
    ),
    array(
        'title' => __('Technical Specialist', 'teeptrak-partner'),
        'description' => __('Technical track completed', 'teeptrak-partner'),
        'earned' => false,
        'date' => null,
        'icon' => 'tool',
    ),
);

// Icon helper
function tt_training_icon($name, $size = 20) {
    $icons = array(
        'play-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>',
        'check-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'lock' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
        'clipboard' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>',
        'award' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>',
        'tool' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
        'clock' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'video' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>',
        'help-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        'arrow-right' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>

<div class="tt-training-page">
    <!-- Page Header -->
    <div class="tt-page-header tt-mb-6">
        <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
            <div>
                <h1 class="tt-page-title"><?php _e('Partner Training', 'teeptrak-partner'); ?></h1>
                <p class="tt-page-subtitle"><?php _e('Complete your certification to unlock all partner benefits.', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    </div>

    <!-- Progress Overview -->
    <div class="tt-progress-overview tt-mb-8">
        <div class="tt-progress-card">
            <div class="tt-progress-ring-wrapper">
                <svg class="tt-progress-ring" viewBox="0 0 120 120">
                    <circle class="tt-progress-ring-bg" cx="60" cy="60" r="54" />
                    <circle class="tt-progress-ring-fill" cx="60" cy="60" r="54"
                        stroke-dasharray="339.292"
                        stroke-dashoffset="<?php echo 339.292 * (1 - $overall_progress / 100); ?>" />
                </svg>
                <div class="tt-progress-ring-text">
                    <span class="tt-progress-value"><?php echo esc_html($overall_progress); ?>%</span>
                    <span class="tt-progress-label"><?php _e('Complete', 'teeptrak-partner'); ?></span>
                </div>
            </div>
            <div class="tt-progress-info">
                <h3 class="tt-progress-title"><?php _e('Your Progress', 'teeptrak-partner'); ?></h3>
                <p class="tt-progress-subtitle">
                    <?php printf(__('%d of %d modules completed', 'teeptrak-partner'), $completed_modules, $total_modules); ?>
                </p>
                <div class="tt-progress-stats">
                    <?php foreach ($training_tracks as $key => $track) :
                        $track_completed = count(array_filter($track['modules'], function($m) { return $m['status'] === 'completed'; }));
                        $track_total = count($track['modules']);
                    ?>
                        <div class="tt-progress-stat">
                            <span class="tt-progress-stat-label"><?php echo esc_html($track['title']); ?></span>
                            <span class="tt-progress-stat-value"><?php echo $track_completed; ?>/<?php echo $track_total; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Certifications -->
        <div class="tt-certifications-card">
            <h3 class="tt-certifications-title"><?php _e('Certifications', 'teeptrak-partner'); ?></h3>
            <div class="tt-certifications-list">
                <?php foreach ($certifications as $cert) : ?>
                    <div class="tt-certification-item <?php echo $cert['earned'] ? 'is-earned' : ''; ?>">
                        <div class="tt-certification-icon <?php echo $cert['earned'] ? 'is-earned' : ''; ?>">
                            <?php echo tt_training_icon($cert['icon'], 24); ?>
                        </div>
                        <div class="tt-certification-content">
                            <h4 class="tt-certification-name"><?php echo esc_html($cert['title']); ?></h4>
                            <p class="tt-certification-desc"><?php echo esc_html($cert['description']); ?></p>
                            <?php if ($cert['earned'] && $cert['date']) : ?>
                                <span class="tt-certification-date"><?php printf(__('Earned: %s', 'teeptrak-partner'), date_i18n(get_option('date_format'), strtotime($cert['date']))); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Training Tracks -->
    <?php foreach ($training_tracks as $track_key => $track) :
        $track_completed = count(array_filter($track['modules'], function($m) { return $m['status'] === 'completed'; }));
        $track_total = count($track['modules']);
        $track_progress = $track_total > 0 ? round(($track_completed / $track_total) * 100) : 0;
    ?>
        <div class="tt-track-section tt-mb-6">
            <div class="tt-track-header">
                <div class="tt-track-info">
                    <div class="tt-flex tt-items-center tt-gap-3">
                        <h2 class="tt-track-title"><?php echo esc_html($track['title']); ?></h2>
                        <?php if ($track['required']) : ?>
                            <span class="tt-required-badge"><?php _e('Required', 'teeptrak-partner'); ?></span>
                        <?php else : ?>
                            <span class="tt-optional-badge"><?php _e('Optional', 'teeptrak-partner'); ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="tt-track-desc"><?php echo esc_html($track['description']); ?></p>
                </div>
                <div class="tt-track-progress">
                    <div class="tt-track-progress-bar">
                        <div class="tt-track-progress-fill" style="width: <?php echo esc_attr($track_progress); ?>%;"></div>
                    </div>
                    <span class="tt-track-progress-text"><?php echo $track_completed; ?>/<?php echo $track_total; ?> <?php _e('completed', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <div class="tt-modules-list">
                <?php foreach ($track['modules'] as $index => $module) :
                    $status_classes = array(
                        'completed' => 'is-completed',
                        'in_progress' => 'is-active',
                        'locked' => 'is-locked',
                        'not_started' => '',
                    );
                    $status_class = $status_classes[$module['status']] ?? '';

                    $status_icons = array(
                        'completed' => 'check-circle',
                        'in_progress' => 'play-circle',
                        'locked' => 'lock',
                        'not_started' => ($module['type'] === 'quiz' ? 'clipboard' : 'video'),
                    );
                    $status_icon = $status_icons[$module['status']] ?? 'video';
                ?>
                    <div class="tt-module-card <?php echo esc_attr($status_class); ?>">
                        <div class="tt-module-number">
                            <?php echo $index + 1; ?>
                        </div>
                        <div class="tt-module-icon <?php echo esc_attr($status_class); ?>">
                            <?php echo tt_training_icon($status_icon, 20); ?>
                        </div>
                        <div class="tt-module-content">
                            <div class="tt-module-header">
                                <h3 class="tt-module-title"><?php echo esc_html($module['title']); ?></h3>
                                <div class="tt-module-meta">
                                    <?php if ($module['type'] === 'quiz') : ?>
                                        <span class="tt-module-type tt-module-type-quiz"><?php _e('Quiz', 'teeptrak-partner'); ?></span>
                                    <?php else : ?>
                                        <span class="tt-module-type"><?php _e('Video', 'teeptrak-partner'); ?></span>
                                    <?php endif; ?>
                                    <span class="tt-module-duration">
                                        <?php echo tt_training_icon('clock', 14); ?>
                                        <?php echo esc_html($module['duration']); ?>
                                    </span>
                                </div>
                            </div>
                            <p class="tt-module-desc"><?php echo esc_html($module['description']); ?></p>

                            <?php if ($module['status'] === 'in_progress') : ?>
                                <div class="tt-module-progress">
                                    <div class="tt-module-progress-bar">
                                        <div class="tt-module-progress-fill" style="width: <?php echo esc_attr($module['progress']); ?>%;"></div>
                                    </div>
                                    <span class="tt-module-progress-text"><?php echo esc_html($module['progress']); ?>% <?php _e('complete', 'teeptrak-partner'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tt-module-action">
                            <?php if ($module['status'] === 'completed') : ?>
                                <span class="tt-module-status-text tt-text-success"><?php _e('Completed', 'teeptrak-partner'); ?></span>
                            <?php elseif ($module['status'] === 'in_progress') : ?>
                                <a href="#" class="tt-btn tt-btn-primary tt-btn-sm">
                                    <?php _e('Continue', 'teeptrak-partner'); ?>
                                    <?php echo tt_training_icon('arrow-right', 14); ?>
                                </a>
                            <?php elseif ($module['status'] === 'locked') : ?>
                                <span class="tt-module-status-text tt-text-gray-400"><?php _e('Locked', 'teeptrak-partner'); ?></span>
                            <?php else : ?>
                                <a href="#" class="tt-btn tt-btn-outline tt-btn-sm">
                                    <?php _e('Start', 'teeptrak-partner'); ?>
                                    <?php echo tt_training_icon('arrow-right', 14); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- LearnPress Integration Notice -->
    <?php if (defined('LEARNPRESS_VERSION') || defined('TUTOR_VERSION')) : ?>
        <!-- LMS is active, courses would be loaded dynamically -->
    <?php else : ?>
        <div class="tt-lms-notice tt-mt-8">
            <div class="tt-notice-icon">
                <?php echo tt_training_icon('help-circle', 24); ?>
            </div>
            <div class="tt-notice-content">
                <h3><?php _e('Enhanced Learning Experience', 'teeptrak-partner'); ?></h3>
                <p><?php _e('For video playback, quizzes, and progress tracking, the training content can be integrated with LearnPress, Tutor LMS, or similar learning management systems.', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Training Page Styles */
.tt-training-page {
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

/* Progress Overview */
.tt-progress-overview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .tt-progress-overview {
        grid-template-columns: 1fr;
    }
}

.tt-progress-card {
    background: var(--tt-white);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    gap: 24px;
    align-items: center;
}

.tt-progress-ring-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    flex-shrink: 0;
}

.tt-progress-ring {
    transform: rotate(-90deg);
}

.tt-progress-ring-bg {
    fill: none;
    stroke: var(--tt-gray-100);
    stroke-width: 8;
}

.tt-progress-ring-fill {
    fill: none;
    stroke: var(--tt-red);
    stroke-width: 8;
    stroke-linecap: round;
    transition: stroke-dashoffset 0.5s ease;
}

.tt-progress-ring-text {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.tt-progress-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--tt-gray-900);
}

.tt-progress-label {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
}

.tt-progress-info {
    flex: 1;
}

.tt-progress-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 4px 0;
}

.tt-progress-subtitle {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
    margin: 0 0 16px 0;
}

.tt-progress-stats {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.tt-progress-stat {
    display: flex;
    justify-content: space-between;
    font-size: 0.8125rem;
}

.tt-progress-stat-label {
    color: var(--tt-gray-500);
}

.tt-progress-stat-value {
    font-weight: 600;
    color: var(--tt-gray-700);
}

/* Certifications Card */
.tt-certifications-card {
    background: var(--tt-white);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--tt-gray-100);
}

.tt-certifications-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 16px 0;
}

.tt-certifications-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.tt-certification-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: var(--tt-gray-50);
    border-radius: 10px;
}

.tt-certification-item.is-earned {
    background: #FEF3C7;
}

.tt-certification-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: var(--tt-gray-200);
    color: var(--tt-gray-400);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-certification-icon.is-earned {
    background: #F59E0B;
    color: white;
}

.tt-certification-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 2px 0;
}

.tt-certification-desc {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
    margin: 0;
}

.tt-certification-date {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
    margin-top: 4px;
}

/* Track Section */
.tt-track-section {
    background: var(--tt-white);
    border-radius: 16px;
    border: 1px solid var(--tt-gray-100);
    overflow: hidden;
}

.tt-track-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--tt-gray-100);
    background: var(--tt-gray-50);
}

@media (max-width: 640px) {
    .tt-track-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}

.tt-track-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0;
}

.tt-required-badge {
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 3px 8px;
    background: #FEE2E2;
    color: #DC2626;
    border-radius: 4px;
}

.tt-optional-badge {
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 3px 8px;
    background: var(--tt-gray-100);
    color: var(--tt-gray-500);
    border-radius: 4px;
}

.tt-track-desc {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
    margin: 4px 0 0 0;
}

.tt-track-progress {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
}

.tt-track-progress-bar {
    width: 120px;
    height: 6px;
    background: var(--tt-gray-200);
    border-radius: 3px;
    overflow: hidden;
}

.tt-track-progress-fill {
    height: 100%;
    background: var(--tt-red);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.tt-track-progress-text {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
}

/* Module Card */
.tt-modules-list {
    padding: 0;
}

.tt-module-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--tt-gray-100);
    transition: background-color 0.15s ease;
}

.tt-module-card:last-child {
    border-bottom: none;
}

.tt-module-card:hover:not(.is-locked) {
    background: var(--tt-gray-50);
}

.tt-module-card.is-locked {
    opacity: 0.6;
}

.tt-module-number {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--tt-gray-100);
    color: var(--tt-gray-500);
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-module-card.is-completed .tt-module-number {
    background: #DCFCE7;
    color: #22C55E;
}

.tt-module-card.is-active .tt-module-number {
    background: var(--tt-red);
    color: white;
}

.tt-module-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--tt-gray-100);
    color: var(--tt-gray-500);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-module-icon.is-completed {
    background: #DCFCE7;
    color: #22C55E;
}

.tt-module-icon.is-active {
    background: #FEE2E2;
    color: var(--tt-red);
}

.tt-module-content {
    flex: 1;
    min-width: 0;
}

.tt-module-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 4px;
}

.tt-module-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0;
}

.tt-module-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.tt-module-type {
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 6px;
    background: var(--tt-gray-100);
    color: var(--tt-gray-500);
    border-radius: 4px;
}

.tt-module-type-quiz {
    background: #F3E8FF;
    color: #7C3AED;
}

.tt-module-duration {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--tt-gray-400);
}

.tt-module-desc {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
    margin: 0;
    line-height: 1.5;
}

.tt-module-progress {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
}

.tt-module-progress-bar {
    flex: 1;
    height: 4px;
    background: var(--tt-gray-100);
    border-radius: 2px;
    overflow: hidden;
}

.tt-module-progress-fill {
    height: 100%;
    background: var(--tt-red);
    border-radius: 2px;
}

.tt-module-progress-text {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
    flex-shrink: 0;
}

.tt-module-action {
    flex-shrink: 0;
    align-self: center;
}

.tt-module-status-text {
    font-size: 0.8125rem;
    font-weight: 500;
}

.tt-text-success {
    color: #22C55E;
}

/* LMS Notice */
.tt-lms-notice {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: #F0F9FF;
    border: 1px solid #BAE6FD;
    border-radius: 12px;
}

.tt-lms-notice .tt-notice-icon {
    color: #0284C7;
    flex-shrink: 0;
}

.tt-lms-notice h3 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 4px 0;
}

.tt-lms-notice p {
    font-size: 0.875rem;
    color: var(--tt-gray-600);
    margin: 0;
}

/* Button sizes */
.tt-btn-sm {
    padding: 6px 12px;
    font-size: 0.8125rem;
}
</style>

<?php get_footer(); ?>
