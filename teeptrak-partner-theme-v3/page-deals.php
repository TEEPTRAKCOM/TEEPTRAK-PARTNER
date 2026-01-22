<?php
/**
 * Template Name: Partner Deals
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
$view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'kanban';
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

// Get deals for list view
$deals = teeptrak_get_partner_deals($user_id);

get_header();
?>

<div class="tt-page-header">
    <div class="tt-page-header-content">
        <h1 class="tt-page-title"><?php esc_html_e('Deal Pipeline', 'teeptrak-partner'); ?></h1>
        <p class="tt-page-subtitle">
            <?php esc_html_e('Manage and track your registered deals', 'teeptrak-partner'); ?>
        </p>
    </div>
    <div class="tt-page-header-actions">
        <div class="tt-view-toggle">
            <button data-view="kanban" class="<?php echo $view === 'kanban' ? 'tt-active' : ''; ?>">
                <?php echo teeptrak_icon('columns', 16); ?>
                <?php esc_html_e('Kanban', 'teeptrak-partner'); ?>
            </button>
            <button data-view="list" class="<?php echo $view === 'list' ? 'tt-active' : ''; ?>">
                <?php echo teeptrak_icon('list', 16); ?>
                <?php esc_html_e('List', 'teeptrak-partner'); ?>
            </button>
        </div>
        <button class="tt-btn tt-btn-primary" data-modal-open="deal-modal">
            <?php echo teeptrak_icon('plus', 16); ?>
            <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
        </button>
    </div>
</div>

<!-- Filters Bar -->
<div class="tt-filters-bar">
    <div class="tt-filters-left">
        <div class="tt-search tt-search-sm">
            <?php echo teeptrak_icon('search', 16); ?>
            <input type="text" class="tt-kanban-search" placeholder="<?php esc_attr_e('Search deals...', 'teeptrak-partner'); ?>">
        </div>
        <select class="tt-select tt-select-sm tt-filter-stage">
            <option value=""><?php esc_html_e('All Stages', 'teeptrak-partner'); ?></option>
            <option value="lead"><?php esc_html_e('Lead', 'teeptrak-partner'); ?></option>
            <option value="qualified"><?php esc_html_e('Qualified', 'teeptrak-partner'); ?></option>
            <option value="proposal"><?php esc_html_e('Proposal', 'teeptrak-partner'); ?></option>
            <option value="negotiation"><?php esc_html_e('Negotiation', 'teeptrak-partner'); ?></option>
            <option value="won"><?php esc_html_e('Won', 'teeptrak-partner'); ?></option>
            <option value="lost"><?php esc_html_e('Lost', 'teeptrak-partner'); ?></option>
        </select>
    </div>
    <div class="tt-filters-right">
        <span class="tt-deals-count">
            <?php
            printf(
                /* translators: %d: number of deals */
                esc_html(_n('%d deal', '%d deals', count($deals), 'teeptrak-partner')),
                count($deals)
            );
            ?>
        </span>
    </div>
</div>

<?php if ($view === 'kanban') : ?>
<!-- Kanban Board -->
<div class="tt-kanban-board">
    <!-- Kanban columns will be rendered by JavaScript -->
    <div class="tt-loading-state">
        <?php echo teeptrak_icon('loader', 32); ?>
        <p><?php esc_html_e('Loading deals...', 'teeptrak-partner'); ?></p>
    </div>
</div>

<?php else : ?>
<!-- List View -->
<div class="tt-card">
    <div class="tt-card-body tt-card-body-flush">
        <?php if (empty($deals)) : ?>
            <div class="tt-empty-state">
                <?php echo teeptrak_icon('briefcase', 48); ?>
                <h3><?php esc_html_e('No deals registered yet', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Start by registering your first deal to secure protection', 'teeptrak-partner'); ?></p>
                <button class="tt-btn tt-btn-primary" data-modal-open="deal-modal">
                    <?php echo teeptrak_icon('plus', 16); ?>
                    <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
                </button>
            </div>
        <?php else : ?>
            <table class="tt-table tt-table-hover">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Company', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Contact', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Value', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Stage', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Protection', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Expected Close', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Actions', 'teeptrak-partner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deals as $deal) : ?>
                        <tr data-deal-id="<?php echo esc_attr($deal['id']); ?>">
                            <td>
                                <strong><?php echo esc_html($deal['company']); ?></strong>
                            </td>
                            <td>
                                <div class="tt-contact-cell">
                                    <span><?php echo esc_html($deal['contact_name']); ?></span>
                                    <small><?php echo esc_html($deal['contact_email']); ?></small>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo teeptrak_format_currency($deal['value']); ?></strong>
                            </td>
                            <td>
                                <?php echo teeptrak_stage_badge($deal['stage']); ?>
                            </td>
                            <td>
                                <?php echo teeptrak_protection_bar($deal['protection_end']); ?>
                            </td>
                            <td>
                                <?php echo esc_html($deal['expected_close'] ?: '-'); ?>
                            </td>
                            <td>
                                <div class="tt-table-actions">
                                    <button class="tt-btn-icon" data-action="view" data-deal-id="<?php echo esc_attr($deal['id']); ?>" title="<?php esc_attr_e('View', 'teeptrak-partner'); ?>">
                                        <?php echo teeptrak_icon('eye', 16); ?>
                                    </button>
                                    <button class="tt-btn-icon" data-action="edit" data-deal-id="<?php echo esc_attr($deal['id']); ?>" title="<?php esc_attr_e('Edit', 'teeptrak-partner'); ?>">
                                        <?php echo teeptrak_icon('edit-2', 16); ?>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Register Deal Modal -->
<div id="deal-modal" class="tt-modal">
    <div class="tt-modal-content tt-modal-lg">
        <div class="tt-modal-header">
            <h2 class="tt-modal-title"><?php esc_html_e('Register New Deal', 'teeptrak-partner'); ?></h2>
            <button class="tt-modal-close" data-modal-close>&times;</button>
        </div>
        <form id="register-deal-form" data-validate>
            <div class="tt-modal-body">
                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php esc_html_e('Company Information', 'teeptrak-partner'); ?></h3>
                    <div class="tt-form-grid">
                        <div class="tt-form-group tt-form-group-full">
                            <label class="tt-label" for="deal-company"><?php esc_html_e('Company Name', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                            <input type="text" id="deal-company" name="company" class="tt-input" required>
                            <span class="tt-help-text"><?php esc_html_e('Enter the prospect company name', 'teeptrak-partner'); ?></span>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-industry"><?php esc_html_e('Industry', 'teeptrak-partner'); ?></label>
                            <select id="deal-industry" name="industry" class="tt-select">
                                <option value=""><?php esc_html_e('Select industry', 'teeptrak-partner'); ?></option>
                                <option value="automotive"><?php esc_html_e('Automotive', 'teeptrak-partner'); ?></option>
                                <option value="aerospace"><?php esc_html_e('Aerospace', 'teeptrak-partner'); ?></option>
                                <option value="food_beverage"><?php esc_html_e('Food & Beverage', 'teeptrak-partner'); ?></option>
                                <option value="pharma"><?php esc_html_e('Pharmaceutical', 'teeptrak-partner'); ?></option>
                                <option value="packaging"><?php esc_html_e('Packaging', 'teeptrak-partner'); ?></option>
                                <option value="electronics"><?php esc_html_e('Electronics', 'teeptrak-partner'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'teeptrak-partner'); ?></option>
                            </select>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-size"><?php esc_html_e('Company Size', 'teeptrak-partner'); ?></label>
                            <select id="deal-size" name="company_size" class="tt-select">
                                <option value=""><?php esc_html_e('Select size', 'teeptrak-partner'); ?></option>
                                <option value="small"><?php esc_html_e('Small (1-50)', 'teeptrak-partner'); ?></option>
                                <option value="medium"><?php esc_html_e('Medium (51-250)', 'teeptrak-partner'); ?></option>
                                <option value="large"><?php esc_html_e('Large (251-1000)', 'teeptrak-partner'); ?></option>
                                <option value="enterprise"><?php esc_html_e('Enterprise (1000+)', 'teeptrak-partner'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php esc_html_e('Contact Information', 'teeptrak-partner'); ?></h3>
                    <div class="tt-form-grid">
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-contact-name"><?php esc_html_e('Contact Name', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                            <input type="text" id="deal-contact-name" name="contact_name" class="tt-input" required>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-contact-title"><?php esc_html_e('Job Title', 'teeptrak-partner'); ?></label>
                            <input type="text" id="deal-contact-title" name="contact_title" class="tt-input">
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-contact-email"><?php esc_html_e('Email', 'teeptrak-partner'); ?> <span class="tt-required">*</span></label>
                            <input type="email" id="deal-contact-email" name="contact_email" class="tt-input" required>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-contact-phone"><?php esc_html_e('Phone', 'teeptrak-partner'); ?></label>
                            <input type="tel" id="deal-contact-phone" name="contact_phone" class="tt-input" data-validate-phone>
                        </div>
                    </div>
                </div>

                <div class="tt-form-section">
                    <h3 class="tt-form-section-title"><?php esc_html_e('Deal Information', 'teeptrak-partner'); ?></h3>
                    <div class="tt-form-grid">
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-value"><?php esc_html_e('Estimated Value', 'teeptrak-partner'); ?></label>
                            <div class="tt-input-group">
                                <span class="tt-input-prefix">€</span>
                                <input type="number" id="deal-value" name="value" class="tt-input" step="100" min="0">
                            </div>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-expected-close"><?php esc_html_e('Expected Close Date', 'teeptrak-partner'); ?></label>
                            <input type="date" id="deal-expected-close" name="expected_close" class="tt-input">
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-products"><?php esc_html_e('Products of Interest', 'teeptrak-partner'); ?></label>
                            <select id="deal-products" name="products[]" class="tt-select" multiple>
                                <option value="teeptrak_box"><?php esc_html_e('TeepTrak Box', 'teeptrak-partner'); ?></option>
                                <option value="teeptrak_analytics"><?php esc_html_e('TeepTrak Analytics', 'teeptrak-partner'); ?></option>
                                <option value="teeptrak_alerts"><?php esc_html_e('TeepTrak Alerts', 'teeptrak-partner'); ?></option>
                                <option value="teeptrak_reports"><?php esc_html_e('TeepTrak Reports', 'teeptrak-partner'); ?></option>
                            </select>
                        </div>
                        <div class="tt-form-group">
                            <label class="tt-label" for="deal-machines"><?php esc_html_e('Number of Machines', 'teeptrak-partner'); ?></label>
                            <input type="number" id="deal-machines" name="machine_count" class="tt-input" min="1">
                        </div>
                        <div class="tt-form-group tt-form-group-full">
                            <label class="tt-label" for="deal-notes"><?php esc_html_e('Notes', 'teeptrak-partner'); ?></label>
                            <textarea id="deal-notes" name="notes" class="tt-textarea" rows="3" placeholder="<?php esc_attr_e('Add any additional context about this opportunity...', 'teeptrak-partner'); ?>"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tt-info-box">
                    <?php echo teeptrak_icon('shield', 20); ?>
                    <div>
                        <strong><?php esc_html_e('90-Day Deal Protection', 'teeptrak-partner'); ?></strong>
                        <p><?php esc_html_e('Once approved, this deal will be protected for 90 days, ensuring you receive the commission if it closes.', 'teeptrak-partner'); ?></p>
                    </div>
                </div>
            </div>
            <div class="tt-modal-footer">
                <button type="button" class="tt-btn tt-btn-secondary" data-modal-close>
                    <?php esc_html_e('Cancel', 'teeptrak-partner'); ?>
                </button>
                <button type="submit" class="tt-btn tt-btn-primary" id="submit-deal">
                    <?php echo teeptrak_icon('check', 16); ?>
                    <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle deal form submission
    const dealForm = document.getElementById('register-deal-form');
    if (dealForm) {
        dealForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-deal');
            TeepTrak.setLoading(submitBtn, true);

            const formData = new FormData(this);
            const data = {};
            formData.forEach(function(value, key) {
                if (key.endsWith('[]')) {
                    const cleanKey = key.slice(0, -2);
                    if (!data[cleanKey]) data[cleanKey] = [];
                    data[cleanKey].push(value);
                } else {
                    data[key] = value;
                }
            });

            TeepTrak.ajax('teeptrak_register_deal', data, {
                onSuccess: function(result) {
                    TeepTrak.setLoading(submitBtn, false);
                    TeepTrak.closeModal();
                    TeepTrak.showToast(
                        '<?php echo esc_js(__('Deal registered successfully!', 'teeptrak-partner')); ?>',
                        'success'
                    );

                    // Refresh the page or add to kanban
                    if (typeof TeepTrak.Kanban !== 'undefined') {
                        TeepTrak.Kanban.loadDeals();
                    } else {
                        window.location.reload();
                    }
                },
                onError: function(error) {
                    TeepTrak.setLoading(submitBtn, false);
                    TeepTrak.showToast(error || '<?php echo esc_js(__('Error registering deal', 'teeptrak-partner')); ?>', 'error');
                }
            });
        });
    }
});
</script>

<?php get_footer(); ?>
