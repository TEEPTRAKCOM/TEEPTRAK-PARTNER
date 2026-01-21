<?php
/**
 * Template Name: Deal Registration
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$partner = teeptrak_get_current_partner();
$deals = teeptrak_get_partner_deals();
$deal_stats = teeptrak_get_deal_stats();
$stages = teeptrak_get_deal_stages();
?>

<!-- Page Header -->
<div class="tt-page-header tt-flex tt-justify-between tt-items-start">
    <div>
        <h1 class="tt-page-title"><?php esc_html_e('Deal Registration', 'teeptrak-partner'); ?></h1>
        <p class="tt-page-subtitle"><?php esc_html_e('Register opportunities and get 90-day deal protection', 'teeptrak-partner'); ?></p>
    </div>
    <button class="tt-btn tt-btn-primary" id="new-deal-btn">
        <?php echo teeptrak_icon('file-plus', 16); ?>
        <?php esc_html_e('Register New Deal', 'teeptrak-partner'); ?>
    </button>
</div>

<!-- Stats Row -->
<div class="tt-stats-row">
    <div class="tt-stat-box">
        <div class="tt-stat-box-value"><?php echo esc_html($deal_stats['total']); ?></div>
        <div class="tt-stat-box-label"><?php esc_html_e('Total Deals', 'teeptrak-partner'); ?></div>
    </div>
    <div class="tt-stat-box">
        <div class="tt-stat-box-value" style="color: #3B82F6;"><?php echo esc_html($deal_stats['active']); ?></div>
        <div class="tt-stat-box-label"><?php esc_html_e('Active Deals', 'teeptrak-partner'); ?></div>
    </div>
    <div class="tt-stat-box">
        <div class="tt-stat-box-value" style="color: #22C55E;"><?php echo esc_html(teeptrak_format_currency($deal_stats['pipeline_value'])); ?></div>
        <div class="tt-stat-box-label"><?php esc_html_e('Pipeline Value', 'teeptrak-partner'); ?></div>
    </div>
</div>

<!-- Deals Table -->
<div class="tt-card">
    <?php if (empty($deals)) : ?>
        <div class="tt-empty-state">
            <div class="tt-empty-icon">
                <?php echo teeptrak_icon('file-text', 80); ?>
            </div>
            <h3 class="tt-empty-title"><?php esc_html_e('No deals registered yet', 'teeptrak-partner'); ?></h3>
            <p class="tt-empty-text"><?php esc_html_e('Register your first opportunity to get 90-day protection', 'teeptrak-partner'); ?></p>
            <button class="tt-btn tt-btn-primary" id="new-deal-btn-empty">
                <?php echo teeptrak_icon('file-plus', 16); ?>
                <?php esc_html_e('Register New Deal', 'teeptrak-partner'); ?>
            </button>
        </div>
    <?php else : ?>
        <div class="tt-table-wrapper">
            <table class="tt-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Company', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Contact', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Value', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Stage', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Protection', 'teeptrak-partner'); ?></th>
                        <th><?php esc_html_e('Actions', 'teeptrak-partner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deals as $deal) :
                        $days_left = teeptrak_get_protection_days($deal['protection_end']);
                    ?>
                        <tr>
                            <td>
                                <div class="tt-font-semibold"><?php echo esc_html($deal['company_name']); ?></div>
                                <div class="tt-text-xs tt-text-gray-500"><?php echo esc_html($deal['industry'] ?? ''); ?></div>
                            </td>
                            <td>
                                <div><?php echo esc_html($deal['contact_name'] ?? '-'); ?></div>
                                <div class="tt-text-xs tt-text-gray-500"><?php echo esc_html($deal['contact_email'] ?? ''); ?></div>
                            </td>
                            <td class="tt-font-semibold">
                                <?php echo esc_html(teeptrak_format_currency($deal['deal_value'] ?? 0)); ?>
                            </td>
                            <td>
                                <?php teeptrak_stage_badge($deal['stage'] ?? 'registered'); ?>
                            </td>
                            <td>
                                <?php teeptrak_protection_bar($deal['protection_end']); ?>
                            </td>
                            <td>
                                <button class="tt-btn tt-btn-ghost tt-btn-sm"><?php esc_html_e('View', 'teeptrak-partner'); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Register Deal Modal -->
<div class="tt-modal-overlay" id="deal-modal">
    <div class="tt-modal">
        <div class="tt-modal-header">
            <h3 class="tt-modal-title"><?php esc_html_e('Register New Deal', 'teeptrak-partner'); ?></h3>
            <button class="tt-modal-close" id="modal-close">
                <?php echo teeptrak_icon('x', 24); ?>
            </button>
        </div>
        <form id="deal-form">
            <?php wp_nonce_field('teeptrak_nonce', 'teeptrak_nonce_field'); ?>
            <div class="tt-modal-body">
                <div class="tt-grid tt-gap-4" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="tt-form-group" style="grid-column: span 2;">
                        <label class="tt-label tt-label-required"><?php esc_html_e('Company Name', 'teeptrak-partner'); ?></label>
                        <input type="text" name="company_name" class="tt-input" placeholder="<?php esc_attr_e('e.g., Acme Manufacturing', 'teeptrak-partner'); ?>" required>
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Industry', 'teeptrak-partner'); ?></label>
                        <select name="industry" class="tt-select">
                            <option value=""><?php esc_html_e('Select industry...', 'teeptrak-partner'); ?></option>
                            <option value="Automotive"><?php esc_html_e('Automotive', 'teeptrak-partner'); ?></option>
                            <option value="Aerospace & Defense"><?php esc_html_e('Aerospace & Defense', 'teeptrak-partner'); ?></option>
                            <option value="Food & Beverage"><?php esc_html_e('Food & Beverage', 'teeptrak-partner'); ?></option>
                            <option value="Pharmaceuticals"><?php esc_html_e('Pharmaceuticals', 'teeptrak-partner'); ?></option>
                            <option value="Electronics"><?php esc_html_e('Electronics', 'teeptrak-partner'); ?></option>
                            <option value="Machinery & Equipment"><?php esc_html_e('Machinery & Equipment', 'teeptrak-partner'); ?></option>
                            <option value="Packaging"><?php esc_html_e('Packaging', 'teeptrak-partner'); ?></option>
                            <option value="Other Manufacturing"><?php esc_html_e('Other Manufacturing', 'teeptrak-partner'); ?></option>
                        </select>
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Company Size', 'teeptrak-partner'); ?></label>
                        <select name="company_size" class="tt-select">
                            <option value=""><?php esc_html_e('Select size...', 'teeptrak-partner'); ?></option>
                            <option value="SME"><?php esc_html_e('SME (<250 employees)', 'teeptrak-partner'); ?></option>
                            <option value="Mid-Market"><?php esc_html_e('Mid-Market (250-1000)', 'teeptrak-partner'); ?></option>
                            <option value="Enterprise"><?php esc_html_e('Enterprise (1000+)', 'teeptrak-partner'); ?></option>
                        </select>
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Contact Name', 'teeptrak-partner'); ?></label>
                        <input type="text" name="contact_name" class="tt-input" placeholder="<?php esc_attr_e('e.g., Jean Dupont', 'teeptrak-partner'); ?>">
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Contact Title', 'teeptrak-partner'); ?></label>
                        <input type="text" name="contact_title" class="tt-input" placeholder="<?php esc_attr_e('e.g., Plant Manager', 'teeptrak-partner'); ?>">
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Contact Email', 'teeptrak-partner'); ?></label>
                        <input type="email" name="contact_email" class="tt-input" placeholder="<?php esc_attr_e('jean.dupont@company.com', 'teeptrak-partner'); ?>">
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Contact Phone', 'teeptrak-partner'); ?></label>
                        <input type="tel" name="contact_phone" class="tt-input" placeholder="<?php esc_attr_e('+33 X XX XX XX XX', 'teeptrak-partner'); ?>">
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Estimated Deal Value (€)', 'teeptrak-partner'); ?></label>
                        <input type="number" name="deal_value" class="tt-input" placeholder="50000">
                        <p class="tt-form-helper"><?php esc_html_e('Estimated annual contract value', 'teeptrak-partner'); ?></p>
                    </div>

                    <div class="tt-form-group">
                        <label class="tt-label"><?php esc_html_e('Expected Close Date', 'teeptrak-partner'); ?></label>
                        <input type="date" name="expected_close" class="tt-input">
                    </div>

                    <div class="tt-form-group" style="grid-column: span 2;">
                        <label class="tt-label"><?php esc_html_e('Notes', 'teeptrak-partner'); ?></label>
                        <textarea name="notes" class="tt-textarea" rows="3" placeholder="<?php esc_attr_e('Brief description of the opportunity, pain points, current solutions...', 'teeptrak-partner'); ?>" maxlength="500"></textarea>
                    </div>
                </div>
            </div>
            <div class="tt-modal-footer">
                <button type="button" class="tt-btn tt-btn-secondary" id="modal-cancel"><?php esc_html_e('Cancel', 'teeptrak-partner'); ?></button>
                <button type="submit" class="tt-btn tt-btn-primary" id="submit-deal">
                    <?php esc_html_e('Register Deal', 'teeptrak-partner'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
