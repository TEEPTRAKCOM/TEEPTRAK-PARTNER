<?php
/**
 * Template Name: Deal Registration
 * Deal registration and management page
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
$tier_config = teeptrak_get_tier_config($partner['tier'] ?? 'gold');

// Demo deals data from content.json
$demo_deals = array(
    array(
        'id' => 1,
        'company_name' => 'Acme Manufacturing',
        'contact_name' => 'Jean Dupont',
        'contact_email' => 'jean.dupont@acme-mfg.com',
        'deal_value' => 45000,
        'currency' => 'EUR',
        'stage' => 'proposal_sent',
        'protection_start' => date('Y-m-d', strtotime('-36 days')),
        'protection_end' => date('Y-m-d', strtotime('+54 days')),
        'created_at' => date('Y-m-d', strtotime('-36 days')),
        'notes' => 'Large automotive parts supplier, interested in 5 production lines.',
    ),
    array(
        'id' => 2,
        'company_name' => 'TechParts GmbH',
        'contact_name' => 'Hans Mueller',
        'contact_email' => 'h.mueller@techparts.de',
        'deal_value' => 72000,
        'currency' => 'EUR',
        'stage' => 'demo_scheduled',
        'protection_start' => date('Y-m-d', strtotime('-20 days')),
        'protection_end' => date('Y-m-d', strtotime('+70 days')),
        'created_at' => date('Y-m-d', strtotime('-20 days')),
        'notes' => 'German precision parts manufacturer.',
    ),
    array(
        'id' => 3,
        'company_name' => 'Industrie Lyon SA',
        'contact_name' => 'Marie Laurent',
        'contact_email' => 'm.laurent@industrie-lyon.fr',
        'deal_value' => 38000,
        'currency' => 'EUR',
        'stage' => 'qualified',
        'protection_start' => date('Y-m-d', strtotime('-8 days')),
        'protection_end' => date('Y-m-d', strtotime('+82 days')),
        'created_at' => date('Y-m-d', strtotime('-8 days')),
        'notes' => 'Regional manufacturing group.',
    ),
    array(
        'id' => 4,
        'company_name' => 'Nordic Assembly AB',
        'contact_name' => 'Erik Johansson',
        'contact_email' => 'erik@nordic-assembly.se',
        'deal_value' => 95000,
        'currency' => 'EUR',
        'stage' => 'negotiation',
        'protection_start' => date('Y-m-d', strtotime('-60 days')),
        'protection_end' => date('Y-m-d', strtotime('+30 days')),
        'created_at' => date('Y-m-d', strtotime('-60 days')),
        'notes' => 'Automotive assembly plant.',
    ),
    array(
        'id' => 5,
        'company_name' => 'Precision Tools Ltd',
        'contact_name' => 'John Smith',
        'contact_email' => 'j.smith@precision-tools.co.uk',
        'deal_value' => 28000,
        'currency' => 'EUR',
        'stage' => 'closed_won',
        'protection_start' => date('Y-m-d', strtotime('-95 days')),
        'protection_end' => date('Y-m-d', strtotime('-5 days')),
        'created_at' => date('Y-m-d', strtotime('-95 days')),
        'notes' => 'Successfully closed deal.',
    ),
);

// Get real deals or use demo
if ($partner && isset($partner['id'])) {
    global $wpdb;
    $deals = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}teeptrak_deals
         WHERE partner_id = %d
         ORDER BY created_at DESC",
        $partner['id']
    ), ARRAY_A);
    if (empty($deals)) {
        $deals = $demo_deals;
    }
} else {
    $deals = $demo_deals;
}

// Stage configuration
$stages = array(
    'registered' => array('label' => __('Registered', 'teeptrak-partner'), 'color' => '#9CA3AF', 'bg' => '#F3F4F6'),
    'qualified' => array('label' => __('Qualified', 'teeptrak-partner'), 'color' => '#3B82F6', 'bg' => '#DBEAFE'),
    'demo_scheduled' => array('label' => __('Demo Scheduled', 'teeptrak-partner'), 'color' => '#F59E0B', 'bg' => '#FEF3C7'),
    'proposal_sent' => array('label' => __('Proposal Sent', 'teeptrak-partner'), 'color' => '#8B5CF6', 'bg' => '#F3E8FF'),
    'negotiation' => array('label' => __('Negotiation', 'teeptrak-partner'), 'color' => '#F97316', 'bg' => '#FFEDD5'),
    'closed_won' => array('label' => __('Closed Won', 'teeptrak-partner'), 'color' => '#22C55E', 'bg' => '#DCFCE7'),
    'closed_lost' => array('label' => __('Closed Lost', 'teeptrak-partner'), 'color' => '#DC2626', 'bg' => '#FEE2E2'),
);

// Calculate stats
$active_deals = array_filter($deals, function($d) {
    return !in_array($d['stage'], ['closed_won', 'closed_lost']);
});
$total_pipeline = array_sum(array_column($active_deals, 'deal_value'));
$won_deals = array_filter($deals, function($d) { return $d['stage'] === 'closed_won'; });

// Icon helper
function tt_deals_icon($name, $size = 20) {
    $icons = array(
        'plus' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>',
        'file-text' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
        'trending-up' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
        'check-circle' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'shield' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
        'clock' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'x' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'search' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'filter' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>',
        'eye' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'edit' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>

<div class="tt-deals-page">
    <!-- Page Header -->
    <div class="tt-page-header tt-mb-6">
        <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
            <div>
                <h1 class="tt-page-title"><?php _e('Deal Registration', 'teeptrak-partner'); ?></h1>
                <p class="tt-page-subtitle"><?php _e('Register and track your opportunities with 90-day deal protection.', 'teeptrak-partner'); ?></p>
            </div>
            <button type="button" class="tt-btn tt-btn-primary" id="tt-new-deal-btn">
                <?php echo tt_deals_icon('plus', 18); ?>
                <?php _e('Register New Deal', 'teeptrak-partner'); ?>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="tt-stats-grid tt-mb-6">
        <div class="tt-stat-card">
            <div class="tt-stat-icon" style="background-color: #DBEAFE; color: #3B82F6;">
                <?php echo tt_deals_icon('file-text', 20); ?>
            </div>
            <div class="tt-stat-content">
                <div class="tt-stat-value"><?php echo count($active_deals); ?></div>
                <div class="tt-stat-label"><?php _e('Active Deals', 'teeptrak-partner'); ?></div>
            </div>
        </div>
        <div class="tt-stat-card">
            <div class="tt-stat-icon" style="background-color: #FEE2E2; color: #E63946;">
                <?php echo tt_deals_icon('trending-up', 20); ?>
            </div>
            <div class="tt-stat-content">
                <div class="tt-stat-value"><?php echo teeptrak_format_currency($total_pipeline); ?></div>
                <div class="tt-stat-label"><?php _e('Pipeline Value', 'teeptrak-partner'); ?></div>
            </div>
        </div>
        <div class="tt-stat-card">
            <div class="tt-stat-icon" style="background-color: #DCFCE7; color: #22C55E;">
                <?php echo tt_deals_icon('check-circle', 20); ?>
            </div>
            <div class="tt-stat-content">
                <div class="tt-stat-value"><?php echo count($won_deals); ?></div>
                <div class="tt-stat-label"><?php _e('Deals Won', 'teeptrak-partner'); ?></div>
            </div>
        </div>
        <div class="tt-stat-card">
            <div class="tt-stat-icon" style="background-color: #FEF3C7; color: #F59E0B;">
                <?php echo tt_deals_icon('shield', 20); ?>
            </div>
            <div class="tt-stat-content">
                <div class="tt-stat-value">90</div>
                <div class="tt-stat-label"><?php _e('Days Protection', 'teeptrak-partner'); ?></div>
            </div>
        </div>
    </div>

    <!-- Deals Table Card -->
    <div class="tt-card">
        <div class="tt-card-header">
            <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
                <h2 class="tt-card-title"><?php _e('Your Deals', 'teeptrak-partner'); ?></h2>
                <div class="tt-flex tt-items-center tt-gap-3">
                    <!-- Search -->
                    <div class="tt-search-input">
                        <?php echo tt_deals_icon('search', 16); ?>
                        <input type="text" id="tt-deal-search" placeholder="<?php esc_attr_e('Search deals...', 'teeptrak-partner'); ?>">
                    </div>
                    <!-- Filter -->
                    <select id="tt-stage-filter" class="tt-select">
                        <option value=""><?php _e('All Stages', 'teeptrak-partner'); ?></option>
                        <?php foreach ($stages as $key => $stage) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($stage['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="tt-card-body tt-p-0">
            <?php if (!empty($deals)) : ?>
                <div class="tt-table-wrapper">
                    <table class="tt-table tt-deals-table" id="tt-deals-table">
                        <thead>
                            <tr>
                                <th><?php _e('Company', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Contact', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Value', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Stage', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Protection', 'teeptrak-partner'); ?></th>
                                <th><?php _e('Registered', 'teeptrak-partner'); ?></th>
                                <th class="tt-text-right"><?php _e('Actions', 'teeptrak-partner'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deals as $deal) :
                                $stage = $stages[$deal['stage']] ?? $stages['registered'];

                                // Calculate protection days
                                $protection_days = 0;
                                $protection_percent = 0;
                                if (!empty($deal['protection_end'])) {
                                    $end = new DateTime($deal['protection_end']);
                                    $now = new DateTime();
                                    if ($now < $end) {
                                        $protection_days = $now->diff($end)->days;
                                        $protection_percent = min(100, ($protection_days / 90) * 100);
                                    }
                                }
                                $protection_class = $protection_days > 60 ? 'is-safe' : ($protection_days > 30 ? 'is-warning' : 'is-danger');
                                $is_closed = in_array($deal['stage'], ['closed_won', 'closed_lost']);
                            ?>
                                <tr data-stage="<?php echo esc_attr($deal['stage']); ?>">
                                    <td>
                                        <div class="tt-deal-company">
                                            <span class="tt-font-semibold"><?php echo esc_html($deal['company_name']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tt-deal-contact">
                                            <span><?php echo esc_html($deal['contact_name'] ?? '-'); ?></span>
                                            <?php if (!empty($deal['contact_email'])) : ?>
                                                <span class="tt-text-sm tt-text-gray-400"><?php echo esc_html($deal['contact_email']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tt-font-semibold"><?php echo teeptrak_format_currency($deal['deal_value'], $deal['currency'] ?? 'EUR'); ?></span>
                                    </td>
                                    <td>
                                        <span class="tt-stage-badge" style="background-color: <?php echo esc_attr($stage['bg']); ?>; color: <?php echo esc_attr($stage['color']); ?>;">
                                            <?php echo esc_html($stage['label']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!$is_closed) : ?>
                                            <div class="tt-protection-cell">
                                                <div class="tt-protection-bar">
                                                    <div class="tt-protection-fill <?php echo esc_attr($protection_class); ?>" style="width: <?php echo esc_attr($protection_percent); ?>%;"></div>
                                                </div>
                                                <span class="tt-text-sm <?php echo $protection_days <= 30 ? 'tt-text-red' : 'tt-text-gray-500'; ?>">
                                                    <?php printf(__('%d days', 'teeptrak-partner'), $protection_days); ?>
                                                </span>
                                            </div>
                                        <?php else : ?>
                                            <span class="tt-text-sm tt-text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="tt-text-sm tt-text-gray-500">
                                            <?php echo date_i18n(get_option('date_format'), strtotime($deal['created_at'])); ?>
                                        </span>
                                    </td>
                                    <td class="tt-text-right">
                                        <div class="tt-action-buttons">
                                            <button type="button" class="tt-action-btn tt-view-deal" data-deal-id="<?php echo esc_attr($deal['id']); ?>" title="<?php esc_attr_e('View', 'teeptrak-partner'); ?>">
                                                <?php echo tt_deals_icon('eye', 16); ?>
                                            </button>
                                            <?php if (!$is_closed) : ?>
                                                <button type="button" class="tt-action-btn tt-edit-deal" data-deal-id="<?php echo esc_attr($deal['id']); ?>" title="<?php esc_attr_e('Edit', 'teeptrak-partner'); ?>">
                                                    <?php echo tt_deals_icon('edit', 16); ?>
                                                </button>
                                            <?php endif; ?>
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
                        <?php echo tt_deals_icon('file-text', 48); ?>
                    </div>
                    <h3 class="tt-empty-title"><?php _e('No deals registered yet', 'teeptrak-partner'); ?></h3>
                    <p class="tt-empty-desc"><?php _e('Register your first opportunity to get 90-day deal protection', 'teeptrak-partner'); ?></p>
                    <button type="button" class="tt-btn tt-btn-primary" id="tt-new-deal-btn-empty">
                        <?php echo tt_deals_icon('plus', 18); ?>
                        <?php _e('Register Your First Deal', 'teeptrak-partner'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Deal Protection Info -->
    <div class="tt-info-banner tt-mt-6">
        <div class="tt-info-icon">
            <?php echo tt_deals_icon('shield', 24); ?>
        </div>
        <div class="tt-info-content">
            <h3 class="tt-info-title"><?php _e('Deal Protection Policy', 'teeptrak-partner'); ?></h3>
            <p class="tt-info-text"><?php _e('All registered deals receive 90-day protection. During this period, TeepTrak will not accept the same opportunity from another partner. Make sure to update your deals regularly to extend protection if needed.', 'teeptrak-partner'); ?></p>
        </div>
    </div>
</div>

<!-- Deal Registration Modal -->
<div class="tt-modal" id="tt-deal-modal">
    <div class="tt-modal-backdrop" id="tt-modal-backdrop"></div>
    <div class="tt-modal-container">
        <div class="tt-modal-header">
            <h2 class="tt-modal-title"><?php _e('Register New Deal', 'teeptrak-partner'); ?></h2>
            <button type="button" class="tt-modal-close" id="tt-modal-close">
                <?php echo tt_deals_icon('x', 20); ?>
            </button>
        </div>
        <form id="tt-deal-form" class="tt-modal-form">
            <?php wp_nonce_field('teeptrak_deal_nonce', 'deal_nonce'); ?>

            <div class="tt-modal-body">
                <!-- Company Information -->
                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php _e('Company Information', 'teeptrak-partner'); ?></h3>

                    <div class="tt-form-group">
                        <label for="company_name" class="tt-label"><?php _e('Company Name', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                        <input type="text" id="company_name" name="company_name" class="tt-input" required placeholder="<?php esc_attr_e('e.g., Acme Manufacturing', 'teeptrak-partner'); ?>">
                    </div>

                    <div class="tt-form-row">
                        <div class="tt-form-group">
                            <label for="industry" class="tt-label"><?php _e('Industry', 'teeptrak-partner'); ?></label>
                            <select id="industry" name="industry" class="tt-select">
                                <option value=""><?php _e('Select industry', 'teeptrak-partner'); ?></option>
                                <option value="automotive"><?php _e('Automotive', 'teeptrak-partner'); ?></option>
                                <option value="aerospace"><?php _e('Aerospace', 'teeptrak-partner'); ?></option>
                                <option value="electronics"><?php _e('Electronics', 'teeptrak-partner'); ?></option>
                                <option value="food_beverage"><?php _e('Food & Beverage', 'teeptrak-partner'); ?></option>
                                <option value="pharmaceutical"><?php _e('Pharmaceutical', 'teeptrak-partner'); ?></option>
                                <option value="machinery"><?php _e('Machinery', 'teeptrak-partner'); ?></option>
                                <option value="plastics"><?php _e('Plastics', 'teeptrak-partner'); ?></option>
                                <option value="other"><?php _e('Other', 'teeptrak-partner'); ?></option>
                            </select>
                        </div>
                        <div class="tt-form-group">
                            <label for="machines_count" class="tt-label"><?php _e('Number of Machines', 'teeptrak-partner'); ?></label>
                            <input type="number" id="machines_count" name="machines_count" class="tt-input" min="1" placeholder="<?php esc_attr_e('e.g., 10', 'teeptrak-partner'); ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php _e('Contact Information', 'teeptrak-partner'); ?></h3>

                    <div class="tt-form-row">
                        <div class="tt-form-group">
                            <label for="contact_name" class="tt-label"><?php _e('Contact Name', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                            <input type="text" id="contact_name" name="contact_name" class="tt-input" required placeholder="<?php esc_attr_e('e.g., Jean Dupont', 'teeptrak-partner'); ?>">
                        </div>
                        <div class="tt-form-group">
                            <label for="contact_title" class="tt-label"><?php _e('Job Title', 'teeptrak-partner'); ?></label>
                            <input type="text" id="contact_title" name="contact_title" class="tt-input" placeholder="<?php esc_attr_e('e.g., Production Manager', 'teeptrak-partner'); ?>">
                        </div>
                    </div>

                    <div class="tt-form-row">
                        <div class="tt-form-group">
                            <label for="contact_email" class="tt-label"><?php _e('Email', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                            <input type="email" id="contact_email" name="contact_email" class="tt-input" required placeholder="<?php esc_attr_e('email@company.com', 'teeptrak-partner'); ?>">
                        </div>
                        <div class="tt-form-group">
                            <label for="contact_phone" class="tt-label"><?php _e('Phone', 'teeptrak-partner'); ?></label>
                            <input type="tel" id="contact_phone" name="contact_phone" class="tt-input" placeholder="<?php esc_attr_e('+33 1 23 45 67 89', 'teeptrak-partner'); ?>">
                        </div>
                    </div>
                </div>

                <!-- Deal Information -->
                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php _e('Deal Information', 'teeptrak-partner'); ?></h3>

                    <div class="tt-form-row">
                        <div class="tt-form-group">
                            <label for="deal_value" class="tt-label"><?php _e('Estimated Deal Value', 'teeptrak-partner'); ?></label>
                            <div class="tt-input-group">
                                <span class="tt-input-addon">&euro;</span>
                                <input type="number" id="deal_value" name="deal_value" class="tt-input" min="0" step="1000" placeholder="<?php esc_attr_e('45000', 'teeptrak-partner'); ?>">
                            </div>
                        </div>
                        <div class="tt-form-group">
                            <label for="expected_close" class="tt-label"><?php _e('Expected Close Date', 'teeptrak-partner'); ?></label>
                            <input type="date" id="expected_close" name="expected_close" class="tt-input" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="tt-form-group">
                        <label for="notes" class="tt-label"><?php _e('Additional Notes', 'teeptrak-partner'); ?></label>
                        <textarea id="notes" name="notes" class="tt-textarea" rows="3" placeholder="<?php esc_attr_e('Any additional information about this opportunity...', 'teeptrak-partner'); ?>"></textarea>
                    </div>
                </div>

                <!-- Protection Notice -->
                <div class="tt-form-notice">
                    <div class="tt-notice-icon">
                        <?php echo tt_deals_icon('shield', 20); ?>
                    </div>
                    <div class="tt-notice-content">
                        <strong><?php _e('90-Day Deal Protection', 'teeptrak-partner'); ?></strong>
                        <p><?php _e('Once registered, this opportunity will be protected for 90 days. TeepTrak will not accept the same deal from another partner during this period.', 'teeptrak-partner'); ?></p>
                    </div>
                </div>
            </div>

            <div class="tt-modal-footer">
                <button type="button" class="tt-btn tt-btn-outline" id="tt-cancel-deal">
                    <?php _e('Cancel', 'teeptrak-partner'); ?>
                </button>
                <button type="submit" class="tt-btn tt-btn-primary" id="tt-submit-deal">
                    <?php echo tt_deals_icon('plus', 18); ?>
                    <?php _e('Register Deal', 'teeptrak-partner'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Deals Page Styles */
.tt-deals-page {
    max-width: 1200px;
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

/* Stats Grid */
.tt-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 768px) {
    .tt-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tt-stat-card {
    background: var(--tt-white);
    border-radius: 12px;
    padding: 16px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    align-items: center;
    gap: 12px;
}

.tt-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tt-stat-value {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--tt-gray-900);
}

.tt-stat-label {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
}

/* Search and Filter */
.tt-search-input {
    position: relative;
    display: flex;
    align-items: center;
}

.tt-search-input svg {
    position: absolute;
    left: 12px;
    color: var(--tt-gray-400);
}

.tt-search-input input {
    padding: 8px 12px 8px 38px;
    border: 1px solid var(--tt-gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    width: 200px;
}

.tt-search-input input:focus {
    outline: none;
    border-color: var(--tt-red);
}

.tt-select {
    padding: 8px 32px 8px 12px;
    border: 1px solid var(--tt-gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
    appearance: none;
    cursor: pointer;
}

.tt-select:focus {
    outline: none;
    border-color: var(--tt-red);
}

/* Deals Table */
.tt-deals-table th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--tt-gray-500);
}

.tt-deal-company {
    display: flex;
    flex-direction: column;
}

.tt-deal-contact {
    display: flex;
    flex-direction: column;
}

.tt-stage-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Protection Cell */
.tt-protection-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.tt-protection-bar {
    width: 60px;
    height: 6px;
    background: var(--tt-gray-100);
    border-radius: 3px;
    overflow: hidden;
}

.tt-protection-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.tt-protection-fill.is-safe { background-color: #22C55E; }
.tt-protection-fill.is-warning { background-color: #F59E0B; }
.tt-protection-fill.is-danger { background-color: #DC2626; }

/* Action Buttons */
.tt-action-buttons {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

.tt-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    background: transparent;
    color: var(--tt-gray-400);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.tt-action-btn:hover {
    background: var(--tt-gray-100);
    color: var(--tt-gray-700);
}

/* Info Banner */
.tt-info-banner {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: #F0F9FF;
    border: 1px solid #BAE6FD;
    border-radius: 12px;
}

.tt-info-icon {
    color: #0284C7;
    flex-shrink: 0;
}

.tt-info-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 4px 0;
}

.tt-info-text {
    font-size: 0.875rem;
    color: var(--tt-gray-600);
    margin: 0;
    line-height: 1.5;
}

/* Modal */
.tt-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.tt-modal.is-open {
    display: flex;
}

.tt-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
}

.tt-modal-container {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.tt-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--tt-gray-100);
}

.tt-modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0;
}

.tt-modal-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--tt-gray-400);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tt-modal-close:hover {
    background: var(--tt-gray-100);
    color: var(--tt-gray-700);
}

.tt-modal-body {
    padding: 24px;
    overflow-y: auto;
}

.tt-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--tt-gray-100);
}

/* Form Styles */
.tt-form-section {
    margin-bottom: 24px;
}

.tt-form-section-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 16px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--tt-gray-100);
}

.tt-form-group {
    margin-bottom: 16px;
}

.tt-form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

@media (max-width: 480px) {
    .tt-form-row {
        grid-template-columns: 1fr;
    }
}

.tt-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tt-gray-700);
    margin-bottom: 6px;
}

.tt-required {
    color: var(--tt-red);
}

.tt-input,
.tt-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--tt-gray-200);
    border-radius: 8px;
    font-size: 0.9375rem;
    font-family: inherit;
    transition: border-color 0.15s ease;
}

.tt-input:focus,
.tt-textarea:focus,
.tt-select:focus {
    outline: none;
    border-color: var(--tt-red);
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
}

.tt-input-group {
    display: flex;
}

.tt-input-addon {
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: var(--tt-gray-50);
    border: 1px solid var(--tt-gray-200);
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 0.9375rem;
    color: var(--tt-gray-500);
}

.tt-input-group .tt-input {
    border-radius: 0 8px 8px 0;
}

.tt-form-notice {
    display: flex;
    gap: 12px;
    padding: 16px;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 8px;
}

.tt-notice-icon {
    color: var(--tt-red);
    flex-shrink: 0;
}

.tt-notice-content {
    font-size: 0.875rem;
}

.tt-notice-content strong {
    color: var(--tt-gray-900);
}

.tt-notice-content p {
    margin: 4px 0 0;
    color: var(--tt-gray-600);
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tt-deal-modal');
    const openBtns = document.querySelectorAll('#tt-new-deal-btn, #tt-new-deal-btn-empty');
    const closeBtns = document.querySelectorAll('#tt-modal-close, #tt-cancel-deal, #tt-modal-backdrop');
    const form = document.getElementById('tt-deal-form');
    const searchInput = document.getElementById('tt-deal-search');
    const stageFilter = document.getElementById('tt-stage-filter');
    const tableRows = document.querySelectorAll('#tt-deals-table tbody tr');

    // Open modal
    openBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            });
        }
    });

    // Close modal
    closeBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            });
        }
    });

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            // Show loading state
            const submitBtn = document.getElementById('tt-submit-deal');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<?php _e('Registering...', 'teeptrak-partner'); ?>';
            submitBtn.disabled = true;

            // Submit via AJAX
            fetch(teeptrakData.apiUrl + 'deals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': teeptrakData.nonce,
                },
                body: JSON.stringify(Object.fromEntries(formData)),
            })
            .then(response => response.json())
            .then(data => {
                if (data.id) {
                    alert('<?php _e('Deal registered successfully!', 'teeptrak-partner'); ?>');
                    window.location.reload();
                } else {
                    alert(data.message || '<?php _e('Error registering deal', 'teeptrak-partner'); ?>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('<?php _e('Error registering deal', 'teeptrak-partner'); ?>');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', filterDeals);
    }

    // Stage filter
    if (stageFilter) {
        stageFilter.addEventListener('change', filterDeals);
    }

    function filterDeals() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedStage = stageFilter ? stageFilter.value : '';

        tableRows.forEach(row => {
            const companyName = row.querySelector('.tt-deal-company')?.textContent.toLowerCase() || '';
            const contactName = row.querySelector('.tt-deal-contact span')?.textContent.toLowerCase() || '';
            const rowStage = row.dataset.stage;

            const matchesSearch = companyName.includes(searchTerm) || contactName.includes(searchTerm);
            const matchesStage = !selectedStage || rowStage === selectedStage;

            row.style.display = matchesSearch && matchesStage ? '' : 'none';
        });
    }
});
</script>

<?php get_footer(); ?>
