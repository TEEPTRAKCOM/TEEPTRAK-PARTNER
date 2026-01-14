<?php
/**
 * Template Name: Partner Dashboard
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

// Get partner data
$user_id = get_current_user_id();
$partner = teeptrak_get_partner_by_user($user_id);
$tier_config = teeptrak_get_tier_config($partner['tier'] ?? 'bronze');
$onboarding_steps = teeptrak_get_onboarding_steps();
$current_step = intval($partner['onboarding_step'] ?? 1);

// Get stats
global $wpdb;
$partner_id = $partner['id'] ?? 0;

$active_deals = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}teeptrak_deals 
     WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
    $partner_id
)) ?: 0;

$pipeline_value = $wpdb->get_var($wpdb->prepare(
    "SELECT COALESCE(SUM(deal_value), 0) FROM {$wpdb->prefix}teeptrak_deals 
     WHERE partner_id = %d AND stage NOT IN ('closed_won', 'closed_lost')",
    $partner_id
)) ?: 0;

$available_commissions = $wpdb->get_var($wpdb->prepare(
    "SELECT COALESCE(SUM(amount), 0) FROM {$wpdb->prefix}teeptrak_commissions 
     WHERE partner_id = %d AND status = 'approved' AND type = 'commission'",
    $partner_id
)) ?: 0;

$recent_deals = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}teeptrak_deals 
     WHERE partner_id = %d 
     ORDER BY created_at DESC LIMIT 3",
    $partner_id
), ARRAY_A) ?: array();

get_header();
?>

<div class="tt-dashboard">
    <!-- Welcome Header -->
    <div class="tt-dashboard-header tt-flex tt-flex-col md:tt-flex-row md:tt-items-center md:tt-justify-between tt-gap-4 tt-mb-6">
        <div>
            <h1 class="tt-text-2xl tt-font-bold tt-text-gray-900">
                <?php printf(__('Welcome back, %s!', 'teeptrak-partner'), esc_html($partner['company_name'] ?? wp_get_current_user()->display_name)); ?> 👋
            </h1>
            <p class="tt-text-gray-500"><?php _e("Let's grow together!", 'teeptrak-partner'); ?></p>
        </div>
        
        <!-- Tier Badge -->
        <div class="tt-tier-badge tt-tier-badge-<?php echo esc_attr($partner['tier'] ?? 'bronze'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <div>
                <p class="tt-text-white tt-text-sm tt-font-medium"><?php echo esc_html($tier_config['name']); ?> <?php _e('Tier', 'teeptrak-partner'); ?></p>
                <p class="tt-text-white/80 tt-text-xs"><?php _e('Commission Rate:', 'teeptrak-partner'); ?> <?php echo esc_html($tier_config['commission_rate']); ?>%</p>
            </div>
        </div>
    </div>
    
    <!-- KPI Cards -->
    <div class="tt-grid tt-grid-cols-2 lg:tt-grid-cols-4 tt-gap-4 tt-mb-6">
        <!-- Partner Score -->
        <div class="tt-kpi-card">
            <div class="tt-flex tt-items-center tt-justify-between tt-mb-3">
                <div class="tt-kpi-icon" style="background-color: #FEE2E2; color: #EB352B;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <span class="tt-kpi-trend tt-kpi-trend-up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                    +12%
                </span>
            </div>
            <p class="tt-kpi-value"><?php echo esc_html($partner['partner_score'] ?? 0); ?></p>
            <p class="tt-kpi-label"><?php _e('Partner Score', 'teeptrak-partner'); ?></p>
        </div>
        
        <!-- Active Deals -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #DBEAFE; color: #3B82F6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <p class="tt-kpi-value"><?php echo esc_html($active_deals); ?></p>
            <p class="tt-kpi-label"><?php _e('Active Deals', 'teeptrak-partner'); ?></p>
        </div>
        
        <!-- Pipeline Value -->
        <div class="tt-kpi-card">
            <div class="tt-flex tt-items-center tt-justify-between tt-mb-3">
                <div class="tt-kpi-icon" style="background-color: #DCFCE7; color: #22C55E;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="20" x2="12" y2="10"></line>
                        <line x1="18" y1="20" x2="18" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="16"></line>
                    </svg>
                </div>
                <span class="tt-kpi-trend tt-kpi-trend-up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                    +28%
                </span>
            </div>
            <p class="tt-kpi-value"><?php echo teeptrak_format_currency($pipeline_value); ?></p>
            <p class="tt-kpi-label"><?php _e('Pipeline Value', 'teeptrak-partner'); ?></p>
        </div>
        
        <!-- Available Commissions -->
        <div class="tt-kpi-card">
            <div class="tt-kpi-icon" style="background-color: #FEF3C7; color: #F59E0B;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <p class="tt-kpi-value"><?php echo teeptrak_format_currency($available_commissions); ?></p>
            <p class="tt-kpi-label"><?php _e('Available', 'teeptrak-partner'); ?></p>
        </div>
    </div>
    
    <!-- Onboarding Progress -->
    <div class="tt-onboarding tt-mb-6">
        <div class="tt-flex tt-items-center tt-justify-between tt-mb-4">
            <div>
                <h2 class="tt-text-lg tt-font-semibold tt-text-gray-900"><?php _e('Onboarding Progress', 'teeptrak-partner'); ?></h2>
                <p class="tt-text-sm tt-text-gray-500"><?php _e('Complete your onboarding to unlock all features', 'teeptrak-partner'); ?></p>
            </div>
            <div class="tt-text-right">
                <p class="tt-text-2xl tt-font-bold" style="color: #EB352B;">
                    <?php echo round((($current_step - 1) / count($onboarding_steps)) * 100); ?>%
                </p>
                <p class="tt-text-sm tt-text-gray-500">
                    <?php printf(__('Step %d of %d', 'teeptrak-partner'), $current_step - 1, count($onboarding_steps)); ?>
                </p>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="tt-progress tt-mb-6">
            <div class="tt-progress-bar" style="width: <?php echo (($current_step - 1) / count($onboarding_steps)) * 100; ?>%;"></div>
        </div>
        
        <!-- Steps -->
        <div class="tt-onboarding-steps">
            <?php foreach ($onboarding_steps as $step_num => $step) : 
                $is_completed = $step_num < $current_step;
                $is_current = $step_num === $current_step;
            ?>
                <div class="tt-onboarding-step <?php echo $is_current ? 'is-current' : ''; ?>">
                    <div class="tt-onboarding-step-circle <?php echo $is_completed ? 'is-completed' : ($is_current ? 'is-current' : 'is-pending'); ?>">
                        <?php if ($is_completed) : ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        <?php else : ?>
                            <?php echo $step_num; ?>
                        <?php endif; ?>
                    </div>
                    <p class="tt-onboarding-step-label"><?php echo esc_html($step['title']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Next Step Button -->
        <?php if ($current_step <= count($onboarding_steps)) : 
            $next_step = $onboarding_steps[$current_step] ?? null;
            $next_url = $next_step ? home_url('/' . $next_step['slug'] . '/') : '#';
        ?>
            <a href="<?php echo esc_url($next_url); ?>" class="tt-btn tt-btn-primary tt-btn-block tt-mt-4">
                <?php printf(__('Next Step: %s', 'teeptrak-partner'), $next_step['title'] ?? ''); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Two Column Layout -->
    <div class="tt-grid lg:tt-grid-cols-2 tt-gap-6">
        <!-- Quick Actions -->
        <div class="tt-card">
            <div class="tt-card-body">
                <h2 class="tt-text-lg tt-font-semibold tt-text-gray-900 tt-mb-4"><?php _e('Quick Actions', 'teeptrak-partner'); ?></h2>
                <div class="tt-quick-actions">
                    <a href="<?php echo home_url('/deals/'); ?>" class="tt-quick-action" style="background-color: #FEE2E2;">
                        <svg class="tt-quick-action-icon" style="color: #EB352B;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span class="tt-quick-action-label"><?php _e('Register Deal', 'teeptrak-partner'); ?></span>
                    </a>
                    
                    <a href="<?php echo home_url('/academy/'); ?>" class="tt-quick-action" style="background-color: #DBEAFE;">
                        <svg class="tt-quick-action-icon" style="color: #3B82F6;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span class="tt-quick-action-label"><?php _e('Access Academy', 'teeptrak-partner'); ?></span>
                    </a>
                    
                    <a href="<?php echo home_url('/resources/'); ?>" class="tt-quick-action" style="background-color: #DCFCE7;">
                        <svg class="tt-quick-action-icon" style="color: #22C55E;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="tt-quick-action-label"><?php _e('View Resources', 'teeptrak-partner'); ?></span>
                    </a>
                    
                    <a href="<?php echo home_url('/commissions/'); ?>" class="tt-quick-action" style="background-color: #FEF3C7;">
                        <svg class="tt-quick-action-icon" style="color: #F59E0B;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span class="tt-quick-action-label"><?php _e('Request Payout', 'teeptrak-partner'); ?></span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Deals -->
        <div class="tt-card">
            <div class="tt-card-body">
                <div class="tt-flex tt-items-center tt-justify-between tt-mb-4">
                    <h2 class="tt-text-lg tt-font-semibold tt-text-gray-900"><?php _e('Recent Deals', 'teeptrak-partner'); ?></h2>
                    <a href="<?php echo home_url('/deals/'); ?>" class="tt-text-sm tt-font-medium" style="color: #EB352B;">
                        <?php _e('View All', 'teeptrak-partner'); ?> →
                    </a>
                </div>
                
                <?php if (!empty($recent_deals)) : ?>
                    <div class="tt-flex tt-flex-col tt-gap-3">
                        <?php foreach ($recent_deals as $deal) : 
                            $protection_days = 0;
                            if (!empty($deal['protection_end'])) {
                                $end = new DateTime($deal['protection_end']);
                                $now = new DateTime();
                                $protection_days = max(0, $now->diff($end)->days);
                                if ($now > $end) $protection_days = 0;
                            }
                            $protection_status = teeptrak_get_protection_status($protection_days);
                        ?>
                            <div class="tt-deal-item">
                                <div>
                                    <p class="tt-deal-company"><?php echo esc_html($deal['company_name']); ?></p>
                                    <p class="tt-deal-value"><?php echo teeptrak_format_currency($deal['deal_value'], $deal['currency']); ?></p>
                                </div>
                                <div class="tt-text-right">
                                    <span class="tt-badge tt-badge-info"><?php echo esc_html(ucfirst($deal['stage'])); ?></span>
                                    <div class="tt-deal-protection tt-mt-1">
                                        <div class="tt-deal-protection-bar">
                                            <div class="tt-deal-protection-fill <?php echo $protection_status; ?>" 
                                                 style="width: <?php echo ($protection_days / 90) * 100; ?>%;"></div>
                                        </div>
                                        <span class="tt-text-xs tt-text-gray-500">
                                            <?php printf(__('%d days left', 'teeptrak-partner'), $protection_days); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="tt-text-center tt-p-8">
                        <svg class="tt-mx-auto tt-mb-4" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <p class="tt-text-gray-500 tt-mb-4"><?php _e('No deals registered yet', 'teeptrak-partner'); ?></p>
                        <a href="<?php echo home_url('/deals/'); ?>" class="tt-btn tt-btn-primary">
                            <?php _e('Register Your First Deal', 'teeptrak-partner'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
