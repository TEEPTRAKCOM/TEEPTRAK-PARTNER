<?php
/**
 * Template Name: Resource Library
 * Partner resource library with tier-gating
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
$current_tier = $partner['tier'] ?? 'gold'; // Default to gold for demo
$tier_config = teeptrak_get_tier_config($current_tier);

// Tier hierarchy for access control
$tier_levels = array('bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4);
$current_tier_level = $tier_levels[$current_tier] ?? 1;

// Resource categories from content guide
$categories = array(
    'sales' => array(
        'label' => __('Sales Materials', 'teeptrak-partner'),
        'icon' => 'briefcase',
        'color' => '#3B82F6',
    ),
    'technical' => array(
        'label' => __('Technical Docs', 'teeptrak-partner'),
        'icon' => 'cpu',
        'color' => '#8B5CF6',
    ),
    'marketing' => array(
        'label' => __('Marketing', 'teeptrak-partner'),
        'icon' => 'megaphone',
        'color' => '#F59E0B',
    ),
    'case_studies' => array(
        'label' => __('Case Studies', 'teeptrak-partner'),
        'icon' => 'file-text',
        'color' => '#22C55E',
    ),
    'training' => array(
        'label' => __('Training', 'teeptrak-partner'),
        'icon' => 'graduation-cap',
        'color' => '#E63946',
    ),
);

// Demo resources from content.json
$demo_resources = array(
    // Bronze tier resources (All partners)
    array(
        'id' => 1,
        'title' => __('TeepTrak Product Overview', 'teeptrak-partner'),
        'description' => __('Complete overview of TeepTrak OEE solutions including features, benefits, and technical specifications.', 'teeptrak-partner'),
        'category' => 'sales',
        'type' => 'pdf',
        'file_size' => '2.4 MB',
        'min_tier' => 'bronze',
        'downloads' => 234,
        'updated' => date('Y-m-d', strtotime('-5 days')),
    ),
    array(
        'id' => 2,
        'title' => __('Partner Sales Deck 2026', 'teeptrak-partner'),
        'description' => __('Ready-to-use sales presentation with customizable slides for partner meetings.', 'teeptrak-partner'),
        'category' => 'sales',
        'type' => 'pptx',
        'file_size' => '8.1 MB',
        'min_tier' => 'bronze',
        'downloads' => 189,
        'updated' => date('Y-m-d', strtotime('-2 days')),
    ),
    array(
        'id' => 3,
        'title' => __('ROI Calculator Spreadsheet', 'teeptrak-partner'),
        'description' => __('Excel template for calculating customer ROI based on their current OEE and improvement targets.', 'teeptrak-partner'),
        'category' => 'sales',
        'type' => 'xlsx',
        'file_size' => '156 KB',
        'min_tier' => 'bronze',
        'downloads' => 312,
        'updated' => date('Y-m-d', strtotime('-10 days')),
    ),
    array(
        'id' => 4,
        'title' => __('Technical Installation Guide', 'teeptrak-partner'),
        'description' => __('Step-by-step installation procedures for TeepTrak sensors and gateway devices.', 'teeptrak-partner'),
        'category' => 'technical',
        'type' => 'pdf',
        'file_size' => '5.7 MB',
        'min_tier' => 'bronze',
        'downloads' => 98,
        'updated' => date('Y-m-d', strtotime('-15 days')),
    ),
    array(
        'id' => 5,
        'title' => __('Product Brochure - English', 'teeptrak-partner'),
        'description' => __('Customer-facing brochure highlighting key features and benefits of TeepTrak solutions.', 'teeptrak-partner'),
        'category' => 'marketing',
        'type' => 'pdf',
        'file_size' => '3.2 MB',
        'min_tier' => 'bronze',
        'downloads' => 445,
        'updated' => date('Y-m-d', strtotime('-7 days')),
    ),

    // Silver tier resources
    array(
        'id' => 6,
        'title' => __('Case Study: Automotive OEM', 'teeptrak-partner'),
        'description' => __('Detailed case study of 23% OEE improvement at a major automotive supplier.', 'teeptrak-partner'),
        'category' => 'case_studies',
        'type' => 'pdf',
        'file_size' => '1.8 MB',
        'min_tier' => 'silver',
        'downloads' => 156,
        'updated' => date('Y-m-d', strtotime('-12 days')),
    ),
    array(
        'id' => 7,
        'title' => __('Co-branded Email Templates', 'teeptrak-partner'),
        'description' => __('Ready-to-send email templates for prospecting and follow-ups with customizable branding.', 'teeptrak-partner'),
        'category' => 'marketing',
        'type' => 'zip',
        'file_size' => '2.1 MB',
        'min_tier' => 'silver',
        'downloads' => 89,
        'updated' => date('Y-m-d', strtotime('-8 days')),
    ),
    array(
        'id' => 8,
        'title' => __('API Documentation', 'teeptrak-partner'),
        'description' => __('Complete API reference for TeepTrak integration with third-party systems.', 'teeptrak-partner'),
        'category' => 'technical',
        'type' => 'pdf',
        'file_size' => '4.3 MB',
        'min_tier' => 'silver',
        'downloads' => 67,
        'updated' => date('Y-m-d', strtotime('-20 days')),
    ),

    // Gold tier resources
    array(
        'id' => 9,
        'title' => __('Competitive Battle Cards', 'teeptrak-partner'),
        'description' => __('Detailed comparison against main competitors with talking points and objection handling.', 'teeptrak-partner'),
        'category' => 'sales',
        'type' => 'pdf',
        'file_size' => '1.2 MB',
        'min_tier' => 'gold',
        'downloads' => 78,
        'updated' => date('Y-m-d', strtotime('-3 days')),
    ),
    array(
        'id' => 10,
        'title' => __('2026 Product Roadmap', 'teeptrak-partner'),
        'description' => __('Confidential product roadmap for upcoming features and enhancements.', 'teeptrak-partner'),
        'category' => 'technical',
        'type' => 'pdf',
        'file_size' => '2.8 MB',
        'min_tier' => 'gold',
        'downloads' => 45,
        'updated' => date('Y-m-d', strtotime('-1 day')),
    ),
    array(
        'id' => 11,
        'title' => __('Advanced Sales Training Video', 'teeptrak-partner'),
        'description' => __('In-depth sales methodology training for complex enterprise deals.', 'teeptrak-partner'),
        'category' => 'training',
        'type' => 'video',
        'file_size' => '156 MB',
        'min_tier' => 'gold',
        'downloads' => 34,
        'updated' => date('Y-m-d', strtotime('-14 days')),
    ),

    // Platinum tier resources
    array(
        'id' => 12,
        'title' => __('Executive Briefing Kit', 'teeptrak-partner'),
        'description' => __('Materials for C-level presentations including board-ready slides and executive summaries.', 'teeptrak-partner'),
        'category' => 'sales',
        'type' => 'zip',
        'file_size' => '15.4 MB',
        'min_tier' => 'platinum',
        'downloads' => 23,
        'updated' => date('Y-m-d', strtotime('-6 days')),
    ),
    array(
        'id' => 13,
        'title' => __('White-label Marketing Pack', 'teeptrak-partner'),
        'description' => __('Complete white-label marketing materials for co-branded campaigns.', 'teeptrak-partner'),
        'category' => 'marketing',
        'type' => 'zip',
        'file_size' => '45.2 MB',
        'min_tier' => 'platinum',
        'downloads' => 12,
        'updated' => date('Y-m-d', strtotime('-9 days')),
    ),
);

// Get real resources or use demo
if ($partner && isset($partner['id'])) {
    global $wpdb;
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}teeptrak_resources'");
    if ($table_exists) {
        $resources = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}teeptrak_resources ORDER BY updated_at DESC", ARRAY_A);
    }
}
if (empty($resources)) {
    $resources = $demo_resources;
}

// File type icons
$file_icons = array(
    'pdf' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="#DC2626"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path fill="#fff" d="M14 2v6h6"/><text x="12" y="17" font-size="6" fill="#fff" text-anchor="middle" font-weight="bold">PDF</text></svg>',
    'xlsx' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="#22C55E"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path fill="#fff" d="M14 2v6h6"/><text x="12" y="17" font-size="5" fill="#fff" text-anchor="middle" font-weight="bold">XLS</text></svg>',
    'pptx' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="#F59E0B"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path fill="#fff" d="M14 2v6h6"/><text x="12" y="17" font-size="5" fill="#fff" text-anchor="middle" font-weight="bold">PPT</text></svg>',
    'zip' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="#8B5CF6"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path fill="#fff" d="M14 2v6h6"/><text x="12" y="17" font-size="5" fill="#fff" text-anchor="middle" font-weight="bold">ZIP</text></svg>',
    'video' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="#3B82F6"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path fill="#fff" d="M14 2v6h6"/><polygon fill="#fff" points="10,10 10,16 15,13"/></svg>',
);

// Icon helper
function tt_resources_icon($name, $size = 20) {
    $icons = array(
        'briefcase' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
        'cpu' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>',
        'megaphone' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 11 18-5v12L3 13v-2z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>',
        'file-text' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
        'graduation-cap' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'download' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
        'lock' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
        'search' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'grid' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>',
        'list' => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}

// Tier badge colors
$tier_badges = array(
    'bronze' => array('bg' => '#FEF3C7', 'color' => '#92400E', 'border' => '#F59E0B'),
    'silver' => array('bg' => '#F3F4F6', 'color' => '#374151', 'border' => '#9CA3AF'),
    'gold' => array('bg' => '#FEF3C7', 'color' => '#92400E', 'border' => '#EAB308'),
    'platinum' => array('bg' => '#F3F4F6', 'color' => '#374151', 'border' => '#6B7280'),
);
?>

<div class="tt-resources-page">
    <!-- Page Header -->
    <div class="tt-page-header tt-mb-6">
        <div class="tt-flex tt-items-center tt-justify-between tt-flex-wrap tt-gap-4">
            <div>
                <h1 class="tt-page-title"><?php _e('Resource Library', 'teeptrak-partner'); ?></h1>
                <p class="tt-page-subtitle"><?php _e('Sales materials, technical docs, and marketing assets to help you succeed.', 'teeptrak-partner'); ?></p>
            </div>
            <div class="tt-tier-access-badge">
                <span class="tt-tier-dot tt-tier-dot-<?php echo esc_attr($current_tier); ?>"></span>
                <?php printf(__('Your Access: %s Tier', 'teeptrak-partner'), esc_html($tier_config['name'])); ?>
            </div>
        </div>
    </div>

    <!-- Category Filters -->
    <div class="tt-category-filters tt-mb-6">
        <button type="button" class="tt-category-btn is-active" data-category="all">
            <?php echo tt_resources_icon('grid', 16); ?>
            <?php _e('All', 'teeptrak-partner'); ?>
        </button>
        <?php foreach ($categories as $key => $cat) : ?>
            <button type="button" class="tt-category-btn" data-category="<?php echo esc_attr($key); ?>">
                <?php echo tt_resources_icon($cat['icon'], 16); ?>
                <?php echo esc_html($cat['label']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Search Bar -->
    <div class="tt-search-bar tt-mb-6">
        <div class="tt-search-input-lg">
            <?php echo tt_resources_icon('search', 20); ?>
            <input type="text" id="tt-resource-search" placeholder="<?php esc_attr_e('Search resources...', 'teeptrak-partner'); ?>">
        </div>
    </div>

    <!-- Resources Grid -->
    <div class="tt-resources-grid" id="tt-resources-grid">
        <?php foreach ($resources as $resource) :
            $cat = $categories[$resource['category']] ?? $categories['sales'];
            $min_tier_level = $tier_levels[$resource['min_tier']] ?? 1;
            $has_access = $current_tier_level >= $min_tier_level;
            $tier_badge = $tier_badges[$resource['min_tier']] ?? $tier_badges['bronze'];
            $file_icon = $file_icons[$resource['type']] ?? $file_icons['pdf'];
        ?>
            <div class="tt-resource-card <?php echo !$has_access ? 'is-locked' : ''; ?>" data-category="<?php echo esc_attr($resource['category']); ?>">
                <?php if (!$has_access) : ?>
                    <div class="tt-resource-lock">
                        <?php echo tt_resources_icon('lock', 24); ?>
                    </div>
                <?php endif; ?>

                <div class="tt-resource-header">
                    <div class="tt-resource-type">
                        <?php echo $file_icon; ?>
                        <span class="tt-resource-type-label"><?php echo strtoupper(esc_html($resource['type'])); ?></span>
                    </div>
                    <span class="tt-tier-badge-small" style="background: <?php echo esc_attr($tier_badge['bg']); ?>; color: <?php echo esc_attr($tier_badge['color']); ?>; border-color: <?php echo esc_attr($tier_badge['border']); ?>;">
                        <?php echo ucfirst(esc_html($resource['min_tier'])); ?>
                    </span>
                </div>

                <h3 class="tt-resource-title"><?php echo esc_html($resource['title']); ?></h3>
                <p class="tt-resource-desc"><?php echo esc_html($resource['description']); ?></p>

                <div class="tt-resource-meta">
                    <span class="tt-resource-size"><?php echo esc_html($resource['file_size']); ?></span>
                    <span class="tt-resource-divider">|</span>
                    <span class="tt-resource-downloads"><?php printf(__('%d downloads', 'teeptrak-partner'), $resource['downloads']); ?></span>
                </div>

                <div class="tt-resource-footer">
                    <?php if ($has_access) : ?>
                        <a href="#" class="tt-btn tt-btn-primary tt-btn-sm tt-btn-block tt-download-btn" data-resource-id="<?php echo esc_attr($resource['id']); ?>">
                            <?php echo tt_resources_icon('download', 16); ?>
                            <?php _e('Download', 'teeptrak-partner'); ?>
                        </a>
                    <?php else : ?>
                        <div class="tt-locked-message">
                            <?php printf(__('Requires %s tier or higher', 'teeptrak-partner'), ucfirst($resource['min_tier'])); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Upgrade CTA for non-platinum partners -->
    <?php if ($current_tier !== 'platinum') : ?>
        <div class="tt-upgrade-banner tt-mt-8">
            <div class="tt-upgrade-content">
                <h3 class="tt-upgrade-title"><?php _e('Unlock More Resources', 'teeptrak-partner'); ?></h3>
                <p class="tt-upgrade-text"><?php _e('Upgrade your partner tier to access premium resources, exclusive case studies, and advanced sales tools.', 'teeptrak-partner'); ?></p>
            </div>
            <a href="<?php echo esc_url(home_url('/commissions/')); ?>" class="tt-btn tt-btn-outline-white">
                <?php _e('View Tier Requirements', 'teeptrak-partner'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Resources Page Styles */
.tt-resources-page {
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

/* Tier Access Badge */
.tt-tier-access-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--tt-white);
    border: 1px solid var(--tt-gray-200);
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tt-gray-700);
}

.tt-tier-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.tt-tier-dot-bronze { background: #CD7F32; }
.tt-tier-dot-silver { background: #9CA3AF; }
.tt-tier-dot-gold { background: #EAB308; }
.tt-tier-dot-platinum { background: #6B7280; }

/* Category Filters */
.tt-category-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tt-category-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--tt-white);
    border: 1px solid var(--tt-gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tt-gray-600);
    cursor: pointer;
    transition: all 0.15s ease;
}

.tt-category-btn:hover {
    border-color: var(--tt-gray-300);
    color: var(--tt-gray-700);
}

.tt-category-btn.is-active {
    background: var(--tt-red);
    border-color: var(--tt-red);
    color: white;
}

/* Search Bar */
.tt-search-bar {
    max-width: 400px;
}

.tt-search-input-lg {
    position: relative;
    display: flex;
    align-items: center;
}

.tt-search-input-lg svg {
    position: absolute;
    left: 16px;
    color: var(--tt-gray-400);
}

.tt-search-input-lg input {
    width: 100%;
    padding: 12px 16px 12px 48px;
    border: 1px solid var(--tt-gray-200);
    border-radius: 10px;
    font-size: 0.9375rem;
}

.tt-search-input-lg input:focus {
    outline: none;
    border-color: var(--tt-red);
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
}

/* Resources Grid */
.tt-resources-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1024px) {
    .tt-resources-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .tt-resources-grid {
        grid-template-columns: 1fr;
    }
}

/* Resource Card */
.tt-resource-card {
    background: var(--tt-white);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--tt-gray-100);
    display: flex;
    flex-direction: column;
    position: relative;
    transition: all 0.2s ease;
}

.tt-resource-card:hover:not(.is-locked) {
    border-color: rgba(230, 57, 70, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.tt-resource-card.is-locked {
    opacity: 0.7;
}

.tt-resource-lock {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--tt-gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--tt-gray-400);
}

.tt-resource-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.tt-resource-type {
    display: flex;
    align-items: center;
    gap: 8px;
}

.tt-resource-type-label {
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--tt-gray-500);
    letter-spacing: 0.05em;
}

.tt-tier-badge-small {
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid;
}

.tt-resource-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.tt-resource-desc {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
    margin: 0 0 12px 0;
    line-height: 1.5;
    flex: 1;
}

.tt-resource-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    color: var(--tt-gray-400);
    margin-bottom: 16px;
}

.tt-resource-divider {
    opacity: 0.5;
}

.tt-resource-footer {
    margin-top: auto;
}

.tt-locked-message {
    text-align: center;
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
    padding: 10px;
    background: var(--tt-gray-50);
    border-radius: 8px;
}

/* Upgrade Banner */
.tt-upgrade-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 32px;
    background: linear-gradient(135deg, var(--tt-red) 0%, #C62828 100%);
    border-radius: 16px;
}

@media (max-width: 640px) {
    .tt-upgrade-banner {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
}

.tt-upgrade-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    margin: 0 0 4px 0;
}

.tt-upgrade-text {
    font-size: 0.9375rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
}

.tt-btn-outline-white {
    background: transparent;
    color: white;
    border: 2px solid white;
    flex-shrink: 0;
}

.tt-btn-outline-white:hover {
    background: white;
    color: var(--tt-red);
}

/* Button sizes */
.tt-btn-sm {
    padding: 8px 14px;
    font-size: 0.8125rem;
}

.tt-btn-block {
    width: 100%;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryBtns = document.querySelectorAll('.tt-category-btn');
    const searchInput = document.getElementById('tt-resource-search');
    const resourceCards = document.querySelectorAll('.tt-resource-card');

    // Category filter
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
            filterResources();
        });
    });

    // Search filter
    if (searchInput) {
        searchInput.addEventListener('input', filterResources);
    }

    function filterResources() {
        const activeCategory = document.querySelector('.tt-category-btn.is-active')?.dataset.category || 'all';
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

        resourceCards.forEach(card => {
            const cardCategory = card.dataset.category;
            const cardTitle = card.querySelector('.tt-resource-title')?.textContent.toLowerCase() || '';
            const cardDesc = card.querySelector('.tt-resource-desc')?.textContent.toLowerCase() || '';

            const matchesCategory = activeCategory === 'all' || cardCategory === activeCategory;
            const matchesSearch = cardTitle.includes(searchTerm) || cardDesc.includes(searchTerm);

            card.style.display = matchesCategory && matchesSearch ? '' : 'none';
        });
    }

    // Download tracking
    const downloadBtns = document.querySelectorAll('.tt-download-btn');
    downloadBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const resourceId = this.dataset.resourceId;
            // In production, this would trigger download and track analytics
            alert('<?php _e('Download started!', 'teeptrak-partner'); ?>');
        });
    });
});
</script>

<?php get_footer(); ?>
