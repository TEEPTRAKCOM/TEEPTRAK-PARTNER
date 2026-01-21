<?php
/**
 * Template Name: Resources
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$partner = teeptrak_get_current_partner();
$resources = teeptrak_get_resources();
$current_category = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';
?>

<!-- Page Header -->
<div class="tt-page-header">
    <h1 class="tt-page-title"><?php esc_html_e('Resource Library', 'teeptrak-partner'); ?></h1>
    <p class="tt-page-subtitle"><?php esc_html_e('Sales materials, technical docs, and marketing assets', 'teeptrak-partner'); ?></p>
</div>

<!-- Category Filter -->
<div class="tt-filter-tabs">
    <button class="tt-filter-tab <?php echo empty($current_category) ? 'is-active' : ''; ?>" data-category="">
        <?php esc_html_e('All', 'teeptrak-partner'); ?>
    </button>
    <button class="tt-filter-tab <?php echo $current_category === 'sales' ? 'is-active' : ''; ?>" data-category="sales">
        <?php esc_html_e('Sales', 'teeptrak-partner'); ?>
    </button>
    <button class="tt-filter-tab <?php echo $current_category === 'technical' ? 'is-active' : ''; ?>" data-category="technical">
        <?php esc_html_e('Technical', 'teeptrak-partner'); ?>
    </button>
    <button class="tt-filter-tab <?php echo $current_category === 'marketing' ? 'is-active' : ''; ?>" data-category="marketing">
        <?php esc_html_e('Marketing', 'teeptrak-partner'); ?>
    </button>
    <button class="tt-filter-tab <?php echo $current_category === 'case_study' ? 'is-active' : ''; ?>" data-category="case_study">
        <?php esc_html_e('Case Studies', 'teeptrak-partner'); ?>
    </button>
</div>

<!-- Resources Grid -->
<div class="tt-resources-grid" id="resources-grid">
    <?php foreach ($resources as $resource) :
        $has_access = teeptrak_tier_has_access($partner['tier'], $resource['min_tier']);
        $colors = teeptrak_get_file_type_colors($resource['type']);

        // Filter by category
        if (!empty($current_category) && $resource['category'] !== $current_category) {
            continue;
        }
    ?>
        <div class="tt-resource-card <?php echo !$has_access ? 'is-locked' : ''; ?>" data-category="<?php echo esc_attr($resource['category']); ?>">
            <div class="tt-resource-header">
                <div class="tt-resource-icon" style="background-color: <?php echo esc_attr($colors['bg']); ?>; color: <?php echo esc_attr($colors['color']); ?>;">
                    <?php echo teeptrak_resource_icon($resource['type']); ?>
                </div>
                <div>
                    <h3 class="tt-resource-title"><?php echo esc_html($resource['title']); ?></h3>
                    <div class="tt-resource-meta">
                        <?php echo esc_html($resource['type']); ?> &middot; <?php echo esc_html($resource['size']); ?>
                    </div>
                </div>
            </div>

            <p class="tt-resource-desc"><?php echo esc_html($resource['description']); ?></p>

            <div class="tt-resource-footer">
                <?php if ($has_access) : ?>
                    <button class="tt-btn tt-btn-primary tt-btn-sm">
                        <?php echo teeptrak_icon('download', 14); ?>
                        <?php esc_html_e('Download', 'teeptrak-partner'); ?>
                    </button>
                <?php else : ?>
                    <span class="tt-badge tt-badge-warning">
                        <?php echo teeptrak_icon('lock', 12); ?>
                        <?php echo esc_html(ucfirst($resource['min_tier'])); ?>+
                    </span>
                    <span class="tt-text-xs tt-text-gray-500"><?php esc_html_e('Upgrade to access', 'teeptrak-partner'); ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (empty($resources)) : ?>
    <div class="tt-empty-state">
        <div class="tt-empty-icon">
            <?php echo teeptrak_icon('folder', 80); ?>
        </div>
        <h3 class="tt-empty-title"><?php esc_html_e('No resources found', 'teeptrak-partner'); ?></h3>
        <p class="tt-empty-text"><?php esc_html_e('Check back later for new resources', 'teeptrak-partner'); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
