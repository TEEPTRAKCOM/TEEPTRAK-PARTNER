<?php
/**
 * Template Tags
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display tier badge
 */
function teeptrak_tier_badge($tier = 'bronze', $size = 'default') {
    $config = teeptrak_get_tier_config($tier);
    $sizes = array(
        'small' => 'tt-tier-badge-sm',
        'default' => '',
        'large' => 'tt-tier-badge-lg',
    );
    
    $size_class = $sizes[$size] ?? '';
    ?>
    <div class="tt-tier-badge tt-tier-badge-<?php echo esc_attr($tier); ?> <?php echo esc_attr($size_class); ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <span><?php echo esc_html($config['name']); ?></span>
    </div>
    <?php
}

/**
 * Display stage badge
 */
function teeptrak_stage_badge($stage) {
    $stages = array(
        'registered' => array('label' => __('Registered', 'teeptrak-partner'), 'class' => 'tt-badge-gray'),
        'qualified' => array('label' => __('Qualified', 'teeptrak-partner'), 'class' => 'tt-badge-success'),
        'proposal' => array('label' => __('Proposal', 'teeptrak-partner'), 'class' => 'tt-badge-info'),
        'negotiation' => array('label' => __('Negotiation', 'teeptrak-partner'), 'class' => 'tt-badge-warning'),
        'closed_won' => array('label' => __('Closed Won', 'teeptrak-partner'), 'class' => 'tt-badge-success'),
        'closed_lost' => array('label' => __('Closed Lost', 'teeptrak-partner'), 'class' => 'tt-badge-primary'),
    );
    
    $info = $stages[$stage] ?? array('label' => ucfirst($stage), 'class' => 'tt-badge-gray');
    ?>
    <span class="tt-badge <?php echo esc_attr($info['class']); ?>">
        <?php echo esc_html($info['label']); ?>
    </span>
    <?php
}

/**
 * Display protection progress bar
 */
function teeptrak_protection_bar($protection_end, $show_days = true) {
    $days_left = 0;
    $progress = 0;
    
    if (!empty($protection_end)) {
        $end = new DateTime($protection_end);
        $now = new DateTime();
        
        if ($now < $end) {
            $days_left = $now->diff($end)->days;
            $progress = ($days_left / 90) * 100;
        }
    }
    
    $status_class = 'is-safe';
    if ($days_left <= 30) {
        $status_class = 'is-danger';
    } elseif ($days_left <= 60) {
        $status_class = 'is-warning';
    }
    ?>
    <div class="tt-deal-protection">
        <div class="tt-deal-protection-bar">
            <div class="tt-deal-protection-fill <?php echo esc_attr($status_class); ?>" 
                 style="width: <?php echo esc_attr($progress); ?>%;"></div>
        </div>
        <?php if ($show_days) : ?>
            <span class="tt-text-xs tt-text-gray-500">
                <?php printf(__('%d days left', 'teeptrak-partner'), $days_left); ?>
            </span>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display KPI card
 */
function teeptrak_kpi_card($args = array()) {
    $defaults = array(
        'icon' => '',
        'icon_color' => '#EB352B',
        'icon_bg' => '#FEE2E2',
        'value' => '0',
        'label' => '',
        'trend' => null,
        'trend_direction' => 'up',
    );
    
    $args = wp_parse_args($args, $defaults);
    ?>
    <div class="tt-kpi-card">
        <div class="tt-flex tt-items-center tt-justify-between tt-mb-3">
            <div class="tt-kpi-icon" style="background-color: <?php echo esc_attr($args['icon_bg']); ?>; color: <?php echo esc_attr($args['icon_color']); ?>;">
                <?php echo $args['icon']; ?>
            </div>
            <?php if ($args['trend'] !== null) : ?>
                <span class="tt-kpi-trend tt-kpi-trend-<?php echo esc_attr($args['trend_direction']); ?>">
                    <?php if ($args['trend_direction'] === 'up') : ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                    <?php else : ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                        </svg>
                    <?php endif; ?>
                    <?php echo esc_html($args['trend']); ?>
                </span>
            <?php endif; ?>
        </div>
        <p class="tt-kpi-value"><?php echo esc_html($args['value']); ?></p>
        <p class="tt-kpi-label"><?php echo esc_html($args['label']); ?></p>
    </div>
    <?php
}

/**
 * Display module card
 */
function teeptrak_module_card($module) {
    $progress = intval($module['progress'] ?? 0);
    $is_completed = $progress >= 100;
    ?>
    <div class="tt-module-card">
        <div class="tt-module-thumbnail">
            <svg class="tt-module-play-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="5 3 19 12 5 21 5 3"></polygon>
            </svg>
            <?php if ($is_completed) : ?>
                <div class="tt-module-completed-badge"><?php _e('Completed', 'teeptrak-partner'); ?></div>
            <?php endif; ?>
        </div>
        <div class="tt-module-content">
            <h3 class="tt-module-title"><?php echo esc_html($module['title']); ?></h3>
            <p class="tt-module-duration">
                <?php printf(__('%d minutes', 'teeptrak-partner'), $module['duration'] ?? 0); ?>
            </p>
            <?php if ($progress > 0 && !$is_completed) : ?>
                <div class="tt-progress tt-mb-4">
                    <div class="tt-progress-bar" style="width: <?php echo esc_attr($progress); ?>%;"></div>
                </div>
            <?php endif; ?>
            <?php if ($is_completed) : ?>
                <span class="tt-btn tt-btn-secondary tt-btn-block" style="background-color: #DCFCE7; color: #22C55E;">
                    <?php _e('Completed', 'teeptrak-partner'); ?>
                </span>
            <?php elseif ($progress > 0) : ?>
                <a href="<?php echo esc_url($module['url'] ?? '#'); ?>" class="tt-btn tt-btn-primary tt-btn-block">
                    <?php _e('Continue', 'teeptrak-partner'); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($module['url'] ?? '#'); ?>" class="tt-btn tt-btn-secondary tt-btn-block">
                    <?php _e('Start', 'teeptrak-partner'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Display resource card
 */
function teeptrak_resource_card($resource) {
    $file_type = strtoupper($resource['file_type'] ?? 'FILE');
    $icon_class = ($file_type === 'PDF') ? 'is-pdf' : 'is-xlsx';
    ?>
    <div class="tt-resource-card">
        <div class="tt-flex tt-items-start tt-justify-between tt-mb-3">
            <div class="tt-resource-icon <?php echo esc_attr($icon_class); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
            <span class="tt-text-xs tt-text-gray-500"><?php echo esc_html($resource['file_size'] ?? ''); ?></span>
        </div>
        <h3 class="tt-font-medium tt-text-gray-900 tt-mb-1"><?php echo esc_html($resource['title']); ?></h3>
        <p class="tt-text-sm tt-text-gray-500 tt-mb-4"><?php echo esc_html($file_type); ?></p>
        <a href="<?php echo esc_url($resource['file_url'] ?? '#'); ?>" 
           class="tt-btn tt-btn-secondary tt-btn-block"
           download>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <?php _e('Download', 'teeptrak-partner'); ?>
        </a>
    </div>
    <?php
}

/**
 * Display breadcrumbs
 */
function teeptrak_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    ?>
    <nav class="tt-breadcrumbs tt-mb-6">
        <a href="<?php echo home_url('/'); ?>" class="tt-breadcrumb-item">
            <?php _e('Home', 'teeptrak-partner'); ?>
        </a>
        <?php if (teeptrak_is_portal_page()) : ?>
            <span class="tt-breadcrumb-separator">/</span>
            <a href="<?php echo home_url('/dashboard/'); ?>" class="tt-breadcrumb-item">
                <?php _e('Dashboard', 'teeptrak-partner'); ?>
            </a>
        <?php endif; ?>
        <span class="tt-breadcrumb-separator">/</span>
        <span class="tt-breadcrumb-current"><?php the_title(); ?></span>
    </nav>
    <?php
}

/**
 * Display empty state
 */
function teeptrak_empty_state($args = array()) {
    $defaults = array(
        'icon' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
        'title' => __('No data found', 'teeptrak-partner'),
        'description' => '',
        'button_text' => '',
        'button_url' => '#',
    );
    
    $args = wp_parse_args($args, $defaults);
    ?>
    <div class="tt-empty-state">
        <div class="tt-empty-state-icon">
            <?php echo $args['icon']; ?>
        </div>
        <h3 class="tt-empty-state-title"><?php echo esc_html($args['title']); ?></h3>
        <?php if ($args['description']) : ?>
            <p class="tt-empty-state-description"><?php echo esc_html($args['description']); ?></p>
        <?php endif; ?>
        <?php if ($args['button_text']) : ?>
            <a href="<?php echo esc_url($args['button_url']); ?>" class="tt-btn tt-btn-primary">
                <?php echo esc_html($args['button_text']); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php
}
