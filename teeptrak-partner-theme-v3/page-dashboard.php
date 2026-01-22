<?php
/**
 * Template Name: Partner Dashboard
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

// Require login
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get partner data
$partner_tier = get_user_meta($user_id, 'partner_tier', true) ?: 'registered';
$commission_rate = teeptrak_get_commission_rate($partner_tier);

// Get statistics
$stats = teeptrak_get_partner_stats($user_id);
$deals = teeptrak_get_partner_deals($user_id, array('limit' => 5));
$notifications = teeptrak_get_notifications($user_id, 5);
$training_progress = teeptrak_get_training_progress($user_id);

// Onboarding status
$onboarding_complete = get_user_meta($user_id, 'onboarding_complete', true);
$onboarding_steps = array(
    'profile' => !empty(get_user_meta($user_id, 'partner_company', true)),
    'training' => $training_progress['completed'] > 0,
    'deal' => $stats['total_deals'] > 0,
);

get_header();
?>

<div class="tt-page-header">
    <div class="tt-page-header-content">
        <h1 class="tt-page-title">
            <?php
            printf(
                /* translators: %s: user display name */
                esc_html__('Welcome back, %s', 'teeptrak-partner'),
                esc_html($current_user->display_name)
            );
            ?>
        </h1>
        <p class="tt-page-subtitle">
            <?php esc_html_e('Here\'s your partner performance overview', 'teeptrak-partner'); ?>
        </p>
    </div>
    <div class="tt-page-header-actions">
        <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-primary">
            <?php echo teeptrak_icon('plus', 16); ?>
            <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
        </a>
    </div>
</div>

<?php if (!$onboarding_complete) : ?>
<!-- Onboarding Steps -->
<div class="tt-card tt-onboarding-card">
    <div class="tt-card-header">
        <h3 class="tt-card-title"><?php esc_html_e('Getting Started', 'teeptrak-partner'); ?></h3>
        <span class="tt-badge tt-badge-info">
            <?php echo array_sum($onboarding_steps); ?>/3 <?php esc_html_e('Complete', 'teeptrak-partner'); ?>
        </span>
    </div>
    <div class="tt-card-body">
        <div class="tt-onboarding-steps">
            <div class="tt-onboarding-step <?php echo $onboarding_steps['profile'] ? 'tt-complete' : ''; ?>">
                <div class="tt-step-icon">
                    <?php echo $onboarding_steps['profile'] ? teeptrak_icon('check-circle', 24) : teeptrak_icon('user', 24); ?>
                </div>
                <div class="tt-step-content">
                    <h4><?php esc_html_e('Complete Your Profile', 'teeptrak-partner'); ?></h4>
                    <p><?php esc_html_e('Add your company details and contact information', 'teeptrak-partner'); ?></p>
                </div>
                <?php if (!$onboarding_steps['profile']) : ?>
                <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="tt-btn tt-btn-sm tt-btn-secondary">
                    <?php esc_html_e('Complete', 'teeptrak-partner'); ?>
                </a>
                <?php endif; ?>
            </div>

            <div class="tt-onboarding-step <?php echo $onboarding_steps['training'] ? 'tt-complete' : ''; ?>">
                <div class="tt-step-icon">
                    <?php echo $onboarding_steps['training'] ? teeptrak_icon('check-circle', 24) : teeptrak_icon('book-open', 24); ?>
                </div>
                <div class="tt-step-content">
                    <h4><?php esc_html_e('Start Training', 'teeptrak-partner'); ?></h4>
                    <p><?php esc_html_e('Complete at least one training module', 'teeptrak-partner'); ?></p>
                </div>
                <?php if (!$onboarding_steps['training']) : ?>
                <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-btn tt-btn-sm tt-btn-secondary">
                    <?php esc_html_e('Start', 'teeptrak-partner'); ?>
                </a>
                <?php endif; ?>
            </div>

            <div class="tt-onboarding-step <?php echo $onboarding_steps['deal'] ? 'tt-complete' : ''; ?>">
                <div class="tt-step-icon">
                    <?php echo $onboarding_steps['deal'] ? teeptrak_icon('check-circle', 24) : teeptrak_icon('briefcase', 24); ?>
                </div>
                <div class="tt-step-content">
                    <h4><?php esc_html_e('Register Your First Deal', 'teeptrak-partner'); ?></h4>
                    <p><?php esc_html_e('Submit a deal for registration', 'teeptrak-partner'); ?></p>
                </div>
                <?php if (!$onboarding_steps['deal']) : ?>
                <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-sm tt-btn-secondary">
                    <?php esc_html_e('Register', 'teeptrak-partner'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- KPI Cards -->
<div class="tt-kpi-grid">
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-primary">
            <?php echo teeptrak_icon('briefcase', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Total Deals', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo esc_html($stats['total_deals']); ?></span>
        </div>
        <div class="tt-kpi-trend tt-trend-up">
            <?php echo teeptrak_icon('trending-up', 16); ?>
            <span>+<?php echo esc_html($stats['deals_this_month']); ?> <?php esc_html_e('this month', 'teeptrak-partner'); ?></span>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-success">
            <?php echo teeptrak_icon('dollar-sign', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Pipeline Value', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['pipeline_value']); ?></span>
        </div>
        <div class="tt-kpi-sparkline">
            <canvas class="tt-sparkline" data-values="<?php echo esc_attr(json_encode($stats['pipeline_trend'])); ?>"></canvas>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-warning">
            <?php echo teeptrak_icon('award', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Commissions Earned', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo teeptrak_format_currency($stats['total_commissions']); ?></span>
        </div>
        <div class="tt-kpi-trend tt-trend-up">
            <?php echo teeptrak_icon('trending-up', 16); ?>
            <span><?php echo teeptrak_format_currency($stats['pending_commissions']); ?> <?php esc_html_e('pending', 'teeptrak-partner'); ?></span>
        </div>
    </div>

    <div class="tt-kpi-card">
        <div class="tt-kpi-icon tt-kpi-icon-info">
            <?php echo teeptrak_icon('percent', 24); ?>
        </div>
        <div class="tt-kpi-content">
            <span class="tt-kpi-label"><?php esc_html_e('Win Rate', 'teeptrak-partner'); ?></span>
            <span class="tt-kpi-value"><?php echo esc_html(number_format($stats['win_rate'], 1)); ?>%</span>
        </div>
        <div class="tt-kpi-subtitle">
            <span><?php echo esc_html($stats['deals_won']); ?> <?php esc_html_e('won', 'teeptrak-partner'); ?> / <?php echo esc_html($stats['deals_closed']); ?> <?php esc_html_e('closed', 'teeptrak-partner'); ?></span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="tt-dashboard-grid">
    <!-- Pipeline Chart -->
    <div class="tt-card tt-card-chart">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Pipeline by Stage', 'teeptrak-partner'); ?></h3>
        </div>
        <div class="tt-card-body">
            <div class="tt-chart-container">
                <canvas id="pipeline-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Commission Trend Chart -->
    <div class="tt-card tt-card-chart">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Commission Trend', 'teeptrak-partner'); ?></h3>
            <div class="tt-card-actions">
                <select class="tt-select tt-select-sm" id="commission-period">
                    <option value="6"><?php esc_html_e('Last 6 months', 'teeptrak-partner'); ?></option>
                    <option value="12"><?php esc_html_e('Last 12 months', 'teeptrak-partner'); ?></option>
                </select>
            </div>
        </div>
        <div class="tt-card-body">
            <div class="tt-chart-container">
                <canvas id="commission-chart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="tt-dashboard-grid tt-dashboard-grid-3">
    <!-- Recent Deals -->
    <div class="tt-card">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Recent Deals', 'teeptrak-partner'); ?></h3>
            <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-link">
                <?php esc_html_e('View All', 'teeptrak-partner'); ?>
                <?php echo teeptrak_icon('arrow-right', 14); ?>
            </a>
        </div>
        <div class="tt-card-body tt-card-body-flush">
            <?php if (empty($deals)) : ?>
                <div class="tt-empty-state tt-empty-state-sm">
                    <?php echo teeptrak_icon('briefcase', 32); ?>
                    <p><?php esc_html_e('No deals yet', 'teeptrak-partner'); ?></p>
                    <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-sm tt-btn-primary">
                        <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
                    </a>
                </div>
            <?php else : ?>
                <ul class="tt-deal-list">
                    <?php foreach ($deals as $deal) : ?>
                        <li class="tt-deal-item">
                            <div class="tt-deal-info">
                                <span class="tt-deal-company"><?php echo esc_html($deal['company']); ?></span>
                                <span class="tt-deal-value"><?php echo teeptrak_format_currency($deal['value']); ?></span>
                            </div>
                            <div class="tt-deal-meta">
                                <?php echo teeptrak_stage_badge($deal['stage']); ?>
                                <?php echo teeptrak_protection_bar($deal['protection_end']); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Training Progress -->
    <div class="tt-card">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Training Progress', 'teeptrak-partner'); ?></h3>
            <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-link">
                <?php esc_html_e('View All', 'teeptrak-partner'); ?>
                <?php echo teeptrak_icon('arrow-right', 14); ?>
            </a>
        </div>
        <div class="tt-card-body">
            <div class="tt-training-overview">
                <div class="tt-training-stat">
                    <span class="tt-training-value"><?php echo esc_html($training_progress['completed']); ?></span>
                    <span class="tt-training-label"><?php esc_html_e('Completed', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-training-stat">
                    <span class="tt-training-value"><?php echo esc_html($training_progress['in_progress']); ?></span>
                    <span class="tt-training-label"><?php esc_html_e('In Progress', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-training-stat">
                    <span class="tt-training-value"><?php echo esc_html($training_progress['certificates']); ?></span>
                    <span class="tt-training-label"><?php esc_html_e('Certificates', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <?php if (!empty($training_progress['current_course'])) : ?>
                <div class="tt-current-course">
                    <h4><?php esc_html_e('Continue Learning', 'teeptrak-partner'); ?></h4>
                    <div class="tt-course-card-mini">
                        <span class="tt-course-title"><?php echo esc_html($training_progress['current_course']['title']); ?></span>
                        <div class="tt-progress-bar">
                            <div class="tt-progress-fill" style="width: <?php echo esc_attr($training_progress['current_course']['progress']); ?>%"></div>
                        </div>
                        <span class="tt-progress-text"><?php echo esc_html($training_progress['current_course']['progress']); ?>%</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Partner Status -->
    <div class="tt-card">
        <div class="tt-card-header">
            <h3 class="tt-card-title"><?php esc_html_e('Partner Status', 'teeptrak-partner'); ?></h3>
        </div>
        <div class="tt-card-body">
            <div class="tt-partner-status">
                <div class="tt-tier-display">
                    <?php echo teeptrak_tier_badge($partner_tier, 'lg'); ?>
                    <div class="tt-tier-info">
                        <span class="tt-tier-rate"><?php echo esc_html($commission_rate); ?>% <?php esc_html_e('Commission Rate', 'teeptrak-partner'); ?></span>
                    </div>
                </div>

                <?php
                $next_tier = teeptrak_get_next_tier($partner_tier);
                if ($next_tier) :
                    $progress = teeptrak_get_tier_progress($user_id, $next_tier);
                ?>
                <div class="tt-tier-progress">
                    <h4><?php
                        printf(
                            /* translators: %s: next tier name */
                            esc_html__('Progress to %s', 'teeptrak-partner'),
                            esc_html(ucfirst($next_tier['name']))
                        );
                    ?></h4>
                    <div class="tt-progress-bar">
                        <div class="tt-progress-fill" style="width: <?php echo esc_attr($progress['percent']); ?>%"></div>
                    </div>
                    <span class="tt-progress-text">
                        <?php echo teeptrak_format_currency($progress['current']); ?> / <?php echo teeptrak_format_currency($progress['required']); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="tt-card">
    <div class="tt-card-header">
        <h3 class="tt-card-title"><?php esc_html_e('Quick Actions', 'teeptrak-partner'); ?></h3>
    </div>
    <div class="tt-card-body">
        <div class="tt-quick-actions">
            <a href="<?php echo esc_url(home_url('/deals/?action=new')); ?>" class="tt-quick-action">
                <?php echo teeptrak_icon('plus-circle', 24); ?>
                <span><?php esc_html_e('Register New Deal', 'teeptrak-partner'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/resources/')); ?>" class="tt-quick-action">
                <?php echo teeptrak_icon('download', 24); ?>
                <span><?php esc_html_e('Download Resources', 'teeptrak-partner'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/commissions/?action=request')); ?>" class="tt-quick-action">
                <?php echo teeptrak_icon('credit-card', 24); ?>
                <span><?php esc_html_e('Request Payout', 'teeptrak-partner'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/support/')); ?>" class="tt-quick-action">
                <?php echo teeptrak_icon('message-circle', 24); ?>
                <span><?php esc_html_e('Contact Support', 'teeptrak-partner'); ?></span>
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
