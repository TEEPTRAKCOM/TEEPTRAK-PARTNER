<?php
/**
 * Template Name: Partner Dashboard
 * Partner dashboard page - first page partners see after login
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

// Get current user and partner data
$current_user = wp_get_current_user();
$first_name = $current_user->first_name ?: $current_user->display_name;
$user_id = get_current_user_id();

// Get partner data or use demo data
$partner = teeptrak_get_partner_by_user($user_id);

// Demo data for testing (from teeptrak-partner-content.json)
$demo_partner = array(
    'tier' => 'gold',
    'partner_score' => 78,
    'commission_rate' => 25,
    'onboarding_step' => 5,
    'deals_count' => 6,
    'active_deals' => 3,
    'pipeline_value' => 145000,
    'available_balance' => 4500,
    'pending_commissions' => 2800,
    'total_paid' => 18500,
    'training_completed' => 3,
    'training_total' => 8,
);

// Use real data if available, otherwise demo
if (!$partner) {
    $partner = $demo_partner;
    $active_deals = $demo_partner['active_deals'];
    $pipeline_value = $demo_partner['pipeline_value'];
    $available_commissions = $demo_partner['available_balance'];
} else {
    // Get stats from database
    global $wpdb;
    $partner_id = $partner['id'] ?? 0;

    $active_deals = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_deals
         WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
        $partner_id
    )) ?: $demo_partner['active_deals'];

    $pipeline_value = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(deal_value), 0) FROM {$wpdb->prefix}teeptrak_deals
         WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
        $partner_id
    )) ?: $demo_partner['pipeline_value'];

    $available_commissions = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}teeptrak_commissions
         WHERE partner_id = %d AND status = 'approved' AND type = 'commission'",
        $partner_id
    )) ?: $demo_partner['available_balance'];
}

$tier_config = teeptrak_get_tier_config($partner['tier'] ?? 'bronze');

// Onboarding steps from content guide
$onboarding_steps = array(
    1 => array('title' => __('Application', 'teeptrak-partner'), 'key' => 'application'),
    2 => array('title' => __('Agreement', 'teeptrak-partner'), 'key' => 'agreement'),
    3 => array('title' => __('Account', 'teeptrak-partner'), 'key' => 'account'),
    4 => array('title' => __('Training', 'teeptrak-partner'), 'key' => 'training'),
    5 => array('title' => __('First Deal', 'teeptrak-partner'), 'key' => 'first_deal'),
    6 => array('title' => __('Certified', 'teeptrak-partner'), 'key' => 'certified'),
    7 => array('title' => __('First Close', 'teeptrak-partner'), 'key' => 'first_close'),
);

$current_step = intval($partner['onboarding_step'] ?? 5);
$training_completed = $partner['training_completed'] ?? 3;
$training_total = $partner['training_total'] ?? 8;
$training_percent = $training_total > 0 ? round(($training_completed / $training_total) * 100) : 0;
$partner_score = $partner['partner_score'] ?? 78;

// Quick actions from content guide
$quick_actions = array(
    array(
        'title' => __('Register a Deal', 'teeptrak-partner'),
        'description' => __('Get 90-day deal protection', 'teeptrak-partner'),
        'icon' => 'file-plus',
        'bg_color' => '#FEE2E2',
        'icon_color' => '#E63946',
        'url' => home_url('/deals/'),
    ),
    array(
        'title' => __('Continue Training', 'teeptrak-partner'),
        'description' => sprintf(__('%d/%d modules completed', 'teeptrak-partner'), $training_completed, $training_total),
        'icon' => 'graduation-cap',
        'bg_color' => '#DBEAFE',
        'icon_color' => '#3B82F6',
        'url' => home_url('/training/'),
    ),
    array(
        'title' => __('Download Resources', 'teeptrak-partner'),
        'description' => __('Sales materials, case studies', 'teeptrak-partner'),
        'icon' => 'download',
        'bg_color' => '#DCFCE7',
        'icon_color' => '#22C55E',
        'url' => home_url('/resources/'),
    ),
    array(
        'title' => __('View Commissions', 'teeptrak-partner'),
        'description' => __('Track your earnings', 'teeptrak-partner'),
        'icon' => 'dollar-sign',
        'bg_color' => '#FEF3C7',
        'icon_color' => '#F59E0B',
        'url' => home_url('/commissions/'),
    ),
);

// Demo recent activity
$recent_activity = array(
    array(
        'date' => date('M j', strtotime('-1 day')),
        'text' => __('Deal "TechParts GmbH" moved to Demo Scheduled stage', 'teeptrak-partner'),
        'icon' => 'trending-up',
        'color' => 'blue',
    ),
    array(
        'date' => date('M j', strtotime('-3 days')),
        'text' => __('Training module "OEE Fundamentals" completed', 'teeptrak-partner'),
        'icon' => 'check-circle',
        'color' => 'green',
    ),
    array(
        'date' => date('M j', strtotime('-5 days')),
        'text' => sprintf(__('Commission of %s approved for Stellantis Deal', 'teeptrak-partner'), teeptrak_format_currency(3200)),
        'icon' => 'dollar-sign',
        'color' => 'amber',
    ),
    array(
        'date' => date('M j', strtotime('-7 days')),
        'text' => __('New resource available: "2026 Product Roadmap"', 'teeptrak-partner'),
        'icon' => 'file',
        'color' => 'purple',
    ),
);

// Demo recent deals from content.json
$demo_deals = array(
    array(
        'company_name' => 'Acme Manufacturing',
        'deal_value' => 45000,
        'stage' => 'proposal_sent',
        'protection_end' => date('Y-m-d', strtotime('+54 days')),
    ),
    array(
        'company_name' => 'TechParts GmbH',
        'deal_value' => 72000,
        'stage' => 'demo_scheduled',
        'protection_end' => date('Y-m-d', strtotime('+70 days')),
    ),
    array(
        'company_name' => 'Industrie Lyon SA',
        'deal_value' => 38000,
        'stage' => 'qualified',
        'protection_end' => date('Y-m-d', strtotime('+82 days')),
    ),
);

// Get real deals or use demo
if (isset($partner_id) && $partner_id > 0) {
    global $wpdb;
    $recent_deals = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_deals
         WHERE partner_id = %d
         ORDER BY created_at DESC LIMIT 3",
        $partner_id
    ), ARRAY_A);
    if (empty($recent_deals)) {
        $recent_deals = $demo_deals;
    }
} else {
    $recent_deals = $demo_deals;
}

// Stage configuration
$stage_config = array(
    'registered' => array('label' => __('Registered', 'teeptrak-partner'), 'color' => '#9CA3AF'),
    'qualified' => array('label' => __('Qualified', 'teeptrak-partner'), 'color' => '#3B82F6'),
    'demo_scheduled' => array('label' => __('Demo Scheduled', 'teeptrak-partner'), 'color' => '#F59E0B'),
    'proposal_sent' => array('label' => __('Proposal Sent', 'teeptrak-partner'), 'color' => '#8B5CF6'),
    'negotiation' => array('label' => __('Negotiation', 'teeptrak-partner'), 'color' => '#F97316'),
    'closed_won' => array('label' => __('Closed Won', 'teeptrak-partner'), 'color' => '#22C55E'),
    'closed_lost' => array('label' => __('Closed Lost', 'teeptrak-partner'), 'color' => '#DC2626'),
);

// Icon helper
function tt_dash_icon($name, $size = 20) {
    $icons = array(
        'activity' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
        'file-plus' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>',
        'graduation-cap' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'dollar-sign' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
        'download' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
        'trending-up' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
        'check-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'file' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
        'check' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        'arrow-right' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'shield' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}

get_header();
?>

<div class="tt-dashboard">
    <!-- Page Header -->
    <div class="tt-page-header tt-mb-8">
        <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
            <div>
                <h1 class="tt-page-title">
                    <?php printf(__('Welcome back, %s!', 'teeptrak-partner'), esc_html($first_name)); ?>
                </h1>
                <p class="tt-page-subtitle">
                    <?php _e("Here's what's happening with your partner account.", 'teeptrak-partner'); ?>
                </p>
            </div>
            <div class="tt-tier-badge tt-tier-badge-<?php echo esc_attr($partner['tier'] ?? 'bronze'); ?>">
                <?php echo tt_dash_icon('shield', 20); ?>
                <span><?php echo esc_html($tier_config['name']); ?> <?php _e('Partner', 'teeptrak-partner'); ?></span>
            </div>
        </div>
    </div>

    <!-- KPI Cards - 4 columns -->
    <div class="tt-kpi-grid tt-mb-8">
        <!-- Partner Score -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #FEE2E2; color: #E63946;">
                <?php echo tt_dash_icon('activity', 20); ?>
            </div>
            <div class="tt-kpi-content">
                <div class="tt-kpi-label"><?php _e('Partner Score', 'teeptrak-partner'); ?></div>
                <div class="tt-kpi-value"><?php echo esc_html($partner_score); ?><span class="tt-kpi-suffix">/100</span></div>
                <div class="tt-progress tt-mt-2">
                    <div class="tt-progress-bar" style="width: <?php echo esc_attr($partner_score); ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Active Deals -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #DBEAFE; color: #3B82F6;">
                <?php echo tt_dash_icon('file-plus', 20); ?>
            </div>
            <div class="tt-kpi-content">
                <div class="tt-kpi-label"><?php _e('Active Deals', 'teeptrak-partner'); ?></div>
                <div class="tt-kpi-value"><?php echo esc_html($active_deals); ?></div>
                <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-kpi-link">
                    <?php _e('Register deal', 'teeptrak-partner'); ?> <?php echo tt_dash_icon('arrow-right', 14); ?>
                </a>
            </div>
        </div>

        <!-- Training Progress -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #FEF3C7; color: #F59E0B;">
                <?php echo tt_dash_icon('graduation-cap', 20); ?>
            </div>
            <div class="tt-kpi-content">
                <div class="tt-kpi-label"><?php _e('Training Progress', 'teeptrak-partner'); ?></div>
                <div class="tt-kpi-value"><?php echo esc_html($training_percent); ?>%</div>
                <div class="tt-text-sm tt-text-gray-500">
                    <?php printf(__('%d of %d modules completed', 'teeptrak-partner'), $training_completed, $training_total); ?>
                </div>
            </div>
        </div>

        <!-- Commission Rate -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #DCFCE7; color: #22C55E;">
                <?php echo tt_dash_icon('dollar-sign', 20); ?>
            </div>
            <div class="tt-kpi-content">
                <div class="tt-kpi-label"><?php _e('Commission Rate', 'teeptrak-partner'); ?></div>
                <div class="tt-kpi-value"><?php echo esc_html($partner['commission_rate'] ?? $tier_config['commission_rate']); ?>%</div>
                <div class="tt-text-sm tt-text-gray-500">
                    <?php echo esc_html($tier_config['name']); ?> <?php _e('Tier', 'teeptrak-partner'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Onboarding Progress -->
    <div class="tt-card tt-mb-8">
        <div class="tt-card-header">
            <h2 class="tt-card-title"><?php _e('Onboarding Progress', 'teeptrak-partner'); ?></h2>
        </div>
        <div class="tt-card-body">
            <?php
            $total_steps = count($onboarding_steps);
            $progress_percent = min(100, (($current_step - 1) / $total_steps) * 100);
            ?>
            <div class="tt-progress tt-mb-6" style="height: 8px;">
                <div class="tt-progress-bar" style="width: <?php echo esc_attr($progress_percent); ?>%;"></div>
            </div>

            <div class="tt-onboarding-steps">
                <?php foreach ($onboarding_steps as $step_num => $step) :
                    $is_completed = $step_num < $current_step;
                    $is_current = $step_num == $current_step;
                    $status_class = $is_completed ? 'is-completed' : ($is_current ? 'is-current' : 'is-pending');
                ?>
                    <div class="tt-onboarding-step <?php echo esc_attr($status_class); ?>">
                        <div class="tt-onboarding-step-circle <?php echo esc_attr($status_class); ?>">
                            <?php if ($is_completed) : ?>
                                <?php echo tt_dash_icon('check', 14); ?>
                            <?php else : ?>
                                <?php echo esc_html($step_num); ?>
                            <?php endif; ?>
                        </div>
                        <div class="tt-onboarding-step-label"><?php echo esc_html($step['title']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($current_step <= $total_steps) : ?>
                <div class="tt-onboarding-cta tt-mt-6">
                    <div class="tt-onboarding-current">
                        <span class="tt-text-sm tt-text-gray-500"><?php _e('Current Step:', 'teeptrak-partner'); ?></span>
                        <span class="tt-font-semibold"><?php echo esc_html($onboarding_steps[$current_step]['title'] ?? ''); ?></span>
                    </div>
                    <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-btn tt-btn-primary">
                        <?php _e('Continue', 'teeptrak-partner'); ?> <?php echo tt_dash_icon('arrow-right', 16); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="tt-grid md:tt-grid-cols-2 tt-gap-6 tt-mb-8">
        <!-- Quick Actions -->
        <div class="tt-card">
            <div class="tt-card-header">
                <h2 class="tt-card-title"><?php _e('Quick Actions', 'teeptrak-partner'); ?></h2>
            </div>
            <div class="tt-card-body">
                <div class="tt-quick-actions-grid">
                    <?php foreach ($quick_actions as $action) : ?>
                        <a href="<?php echo esc_url($action['url']); ?>" class="tt-quick-action-card">
                            <div class="tt-quick-action-icon" style="background-color: <?php echo esc_attr($action['bg_color']); ?>; color: <?php echo esc_attr($action['icon_color']); ?>;">
                                <?php echo tt_dash_icon($action['icon'], 20); ?>
                            </div>
                            <div class="tt-quick-action-content">
                                <div class="tt-quick-action-title"><?php echo esc_html($action['title']); ?></div>
                                <div class="tt-quick-action-desc"><?php echo esc_html($action['description']); ?></div>
                            </div>
                            <span class="tt-quick-action-arrow"><?php echo tt_dash_icon('arrow-right', 16); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="tt-card">
            <div class="tt-card-header">
                <h2 class="tt-card-title"><?php _e('Recent Activity', 'teeptrak-partner'); ?></h2>
            </div>
            <div class="tt-card-body">
                <div class="tt-activity-feed">
                    <?php foreach ($recent_activity as $activity) : ?>
                        <div class="tt-activity-item">
                            <div class="tt-activity-icon tt-activity-icon-<?php echo esc_attr($activity['color']); ?>">
                                <?php echo tt_dash_icon($activity['icon'], 14); ?>
                            </div>
                            <div class="tt-activity-content">
                                <p class="tt-activity-text"><?php echo esc_html($activity['text']); ?></p>
                                <span class="tt-activity-date"><?php echo esc_html($activity['date']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deals -->
    <div class="tt-card">
        <div class="tt-card-header tt-flex tt-items-center tt-justify-between">
            <h2 class="tt-card-title"><?php _e('Recent Deals', 'teeptrak-partner'); ?></h2>
            <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-ghost tt-btn-sm">
                <?php _e('View All', 'teeptrak-partner'); ?> <?php echo tt_dash_icon('arrow-right', 14); ?>
            </a>
        </div>
        <div class="tt-card-body tt-p-0">
            <?php if (!empty($recent_deals)) : ?>
                <div class="tt-table-wrapper">
                    <table class="tt-table">
                        <thead>
                            <tr>
                                <th><?php _e('Company', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Value', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Stage', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Protection', 'teeptrak-partner'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_deals as $deal) :
                                $stage = $stage_config[$deal['stage']] ?? $stage_config['registered'];

                                // Calculate protection days
                                $protection_days = 0;
                                if (!empty($deal['protection_end'])) {
                                    $end = new DateTime($deal['protection_end']);
                                    $now = new DateTime();
                                    if ($now < $end) {
                                        $protection_days = $now->diff($end)->days;
                                    }
                                }
                                $protection_class = $protection_days > 60 ? 'is-safe' : ($protection_days > 30 ? 'is-warning' : 'is-danger');
                            ?>
                                <tr>
                                    <td>
                                        <div class="tt-font-medium"><?php echo esc_html($deal['company_name']); ?></div>
                                    </td>
                                    <td><?php echo teeptrak_format_currency($deal['deal_value']); ?></td>
                                    <td>
                                        <span class="tt-stage-badge" style="background-color: <?php echo esc_attr($stage['color']); ?>20; color: <?php echo esc_attr($stage['color']); ?>;">
                                            <?php echo esc_html($stage['label']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="tt-deal-protection">
                                            <div class="tt-deal-protection-bar">
                                                <div class="tt-deal-protection-fill <?php echo esc_attr($protection_class); ?>" style="width: <?php echo esc_attr(min(100, ($protection_days / 90) * 100)); ?>%;"></div>
                                            </div>
                                            <span class="tt-text-sm tt-text-gray-500"><?php printf(__('%d days', 'teeptrak-partner'), $protection_days); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="tt-empty-state tt-p-8">
                    <div class="tt-empty-icon">
                        <?php echo tt_dash_icon('file-plus', 48); ?>
                    </div>
                    <h3 class="tt-empty-title"><?php _e('No deals registered yet', 'teeptrak-partner'); ?></h3>
                    <p class="tt-empty-desc"><?php _e('Register your first opportunity to get 90-day protection', 'teeptrak-partner'); ?></p>
                    <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-btn tt-btn-primary">
                        <?php _e('Register New Deal', 'teeptrak-partner'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Dashboard Page Styles */
.tt-dashboard {
    max-width: 1200px;
}

.tt-page-header {
    margin-bottom: 32px;
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

/* KPI Grid */
.tt-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1024px) {
    .tt-kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .tt-kpi-grid {
        grid-template-columns: 1fr;
    }
}

.tt-kpi-card {
    background: var(--tt-white);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    gap: 16px;
}

.tt-kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-kpi-content {
    flex: 1;
    min-width: 0;
}

.tt-kpi-label {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
    margin-bottom: 4px;
}

.tt-kpi-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--tt-gray-900);
    line-height: 1.2;
}

.tt-kpi-suffix {
    font-size: 1rem;
    font-weight: 500;
    color: var(--tt-gray-400);
}

.tt-kpi-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.875rem;
    color: var(--tt-red);
    font-weight: 500;
    margin-top: 4px;
}

.tt-kpi-link:hover {
    color: var(--tt-red-hover);
}

/* Card */
.tt-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0;
}

/* Onboarding Steps */
.tt-onboarding-steps {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

@media (max-width: 768px) {
    .tt-onboarding-steps {
        grid-template-columns: repeat(4, 1fr);
    }
}

.tt-onboarding-step {
    text-align: center;
}

.tt-onboarding-step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.tt-onboarding-step-circle.is-completed {
    background-color: var(--tt-success);
    color: white;
}

.tt-onboarding-step-circle.is-current {
    background-color: var(--tt-red);
    color: white;
    box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.2);
}

.tt-onboarding-step-circle.is-pending {
    background-color: var(--tt-gray-100);
    color: var(--tt-gray-400);
}

.tt-onboarding-step-label {
    font-size: 0.75rem;
    color: var(--tt-gray-500);
}

.tt-onboarding-step.is-current .tt-onboarding-step-label {
    color: var(--tt-red);
    font-weight: 500;
}

.tt-onboarding-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid var(--tt-gray-100);
}

.tt-onboarding-current {
    display: flex;
    gap: 8px;
    align-items: center;
}

/* Quick Actions */
.tt-quick-actions-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.tt-quick-action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--tt-gray-50);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.tt-quick-action-card:hover {
    background: var(--tt-gray-100);
}

.tt-quick-action-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-quick-action-content {
    flex: 1;
    min-width: 0;
}

.tt-quick-action-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
}

.tt-quick-action-desc {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
}

.tt-quick-action-arrow {
    color: var(--tt-gray-400);
}

/* Activity Feed */
.tt-activity-feed {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.tt-activity-item {
    display: flex;
    gap: 12px;
}

.tt-activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-activity-icon-blue { background-color: #DBEAFE; color: #3B82F6; }
.tt-activity-icon-green { background-color: #DCFCE7; color: #22C55E; }
.tt-activity-icon-amber { background-color: #FEF3C7; color: #F59E0B; }
.tt-activity-icon-purple { background-color: #F3E8FF; color: #9333EA; }

.tt-activity-content {
    flex: 1;
    min-width: 0;
}

.tt-activity-text {
    font-size: 0.875rem;
    color: var(--tt-gray-700);
    margin: 0 0 2px 0;
    line-height: 1.4;
}

.tt-activity-date {
    font-size: 0.75rem;
    color: var(--tt-gray-400);
}

/* Stage Badge */
.tt-stage-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
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
    margin: 0 0 24px 0;
}

/* Button sizes */
.tt-btn-sm {
    padding: 6px 12px;
    font-size: 0.8125rem;
}

.tt-btn-ghost {
    background: transparent;
    color: var(--tt-gray-600);
}

.tt-btn-ghost:hover {
    background: var(--tt-gray-100);
}
</style>

<?php get_footer(); ?>
