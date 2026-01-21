<?php
/**
 * Front Page Template
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$tiers = teeptrak_get_tier_config();
?>

<!-- Hero Section -->
<section class="tt-hero">
    <div class="tt-container">
        <div class="tt-hero-content">
            <h1 class="tt-hero-title"><?php esc_html_e('Become a TeepTrak Partner', 'teeptrak-partner'); ?></h1>
            <p class="tt-hero-subtitle">
                <?php esc_html_e('Join our partner ecosystem and help manufacturers achieve 5-30% productivity gains with proven Industrial IoT solutions deployed in 360+ plants worldwide.', 'teeptrak-partner'); ?>
            </p>
            <div class="tt-hero-actions">
                <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn tt-btn-primary tt-btn-xl">
                    <?php esc_html_e('Apply Now', 'teeptrak-partner'); ?>
                </a>
                <a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>" class="tt-btn tt-btn-outline tt-btn-xl">
                    <?php esc_html_e('Partner Login', 'teeptrak-partner'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Client Logos -->
<section class="tt-logos">
    <div class="tt-container">
        <p class="tt-logos-label"><?php esc_html_e('TRUSTED BY INDUSTRY LEADERS', 'teeptrak-partner'); ?></p>
        <div class="tt-logos-grid">
            <span>STELLANTIS</span>
            <span>ALSTOM</span>
            <span>THALES</span>
            <span>RENAULT</span>
            <span>AIRBUS</span>
            <span>HUTCHINSON</span>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="tt-section">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('Partner Benefits', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('Everything you need to sell, implement, and support TeepTrak solutions', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-benefits-grid">
            <!-- Benefit 1 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #FEE2E2; color: #E63946;">
                    <?php echo teeptrak_icon('dollar-sign', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Attractive Commissions', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Earn 15-30% commission on every deal based on your partner tier', 'teeptrak-partner'); ?></p>
            </div>

            <!-- Benefit 2 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #DBEAFE; color: #3B82F6;">
                    <?php echo teeptrak_icon('shield', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('90-Day Deal Protection', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Your registered opportunities are protected for 90 days—no channel conflict', 'teeptrak-partner'); ?></p>
            </div>

            <!-- Benefit 3 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #DCFCE7; color: #22C55E;">
                    <?php echo teeptrak_icon('graduation-cap', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Complete Certification', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Free training program covering OEE fundamentals, product features, and sales methodology', 'teeptrak-partner'); ?></p>
            </div>

            <!-- Benefit 4 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #FEF3C7; color: #F59E0B;">
                    <?php echo teeptrak_icon('folder', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Sales Enablement', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Brochures, case studies, ROI calculators, and ready-to-use email templates', 'teeptrak-partner'); ?></p>
            </div>

            <!-- Benefit 5 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #F3E8FF; color: #9333EA;">
                    <?php echo teeptrak_icon('users', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Dedicated Support', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Personal Partner Success Manager for Gold and Platinum partners', 'teeptrak-partner'); ?></p>
            </div>

            <!-- Benefit 6 -->
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon" style="background-color: #CFFAFE; color: #0891B2;">
                    <?php echo teeptrak_icon('activity', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Real-Time Dashboard', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-text"><?php esc_html_e('Track deals, commissions, training progress, and performance in one place', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Partner Tiers Section -->
<section id="tiers" class="tt-section tt-section-alt">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('Grow with us and unlock more benefits at every level', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-tiers-grid">
            <?php foreach ($tiers as $tier_key => $tier) : ?>
                <div class="tt-tier-card <?php echo !empty($tier['featured']) ? 'is-featured' : ''; ?>">
                    <div class="tt-tier-card-header" style="background: <?php echo esc_attr($tier['gradient']); ?>;">
                        <div class="tt-tier-name"><?php echo esc_html($tier['name']); ?></div>
                        <div class="tt-tier-rate"><?php echo esc_html($tier['commission_rate']); ?>%</div>
                    </div>
                    <div class="tt-tier-card-body">
                        <p class="tt-tier-requirement"><?php echo esc_html($tier['requirements']); ?></p>
                        <ul class="tt-tier-benefits">
                            <?php foreach ($tier['benefits'] as $benefit) : ?>
                                <li>
                                    <?php echo teeptrak_icon('check', 16); ?>
                                    <?php echo esc_html($benefit); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="process" class="tt-section">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('How the Program Works', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-process-grid">
            <div class="tt-process-step">
                <div class="tt-process-number">1</div>
                <h3 class="tt-process-title"><?php esc_html_e('Apply', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Submit your application with company details and target industries', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-process-step">
                <div class="tt-process-number">2</div>
                <h3 class="tt-process-title"><?php esc_html_e('Onboard', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Complete certification training and access the partner portal', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-process-step">
                <div class="tt-process-number">3</div>
                <h3 class="tt-process-title"><?php esc_html_e('Register Deals', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Submit opportunities through the portal for 90-day protection', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-process-step">
                <div class="tt-process-number">4</div>
                <h3 class="tt-process-title"><?php esc_html_e('Close & Earn', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Work with our sales team to close deals and earn commissions', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-process-step">
                <div class="tt-process-number">5</div>
                <h3 class="tt-process-title"><?php esc_html_e('Grow', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Hit milestones to advance tiers and unlock more benefits', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-process-step">
                <div class="tt-process-number">6</div>
                <h3 class="tt-process-title"><?php esc_html_e('Renew', 'teeptrak-partner'); ?></h3>
                <p class="tt-process-text"><?php esc_html_e('Annual renewal with performance review and tier assessment', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Ideal Partners Section -->
<section class="tt-section tt-section-alt">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e("Who We're Looking For", 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('We partner with organizations that understand manufacturing operations', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-partners-grid">
            <div class="tt-partner-type-card">
                <h3 class="tt-partner-type-title"><?php esc_html_e('Systems Integrators', 'teeptrak-partner'); ?></h3>
                <ul class="tt-partner-type-list">
                    <li><?php esc_html_e('MES/ERP implementation specialists', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Industrial automation companies', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Smart factory consultants', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Minimum 3+ years in manufacturing IT', 'teeptrak-partner'); ?></li>
                </ul>
            </div>

            <div class="tt-partner-type-card">
                <h3 class="tt-partner-type-title"><?php esc_html_e('Value-Added Resellers', 'teeptrak-partner'); ?></h3>
                <ul class="tt-partner-type-list">
                    <li><?php esc_html_e('Industrial equipment distributors', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Technology solution providers', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Regional IT specialists', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Strong manufacturing client base', 'teeptrak-partner'); ?></li>
                </ul>
            </div>

            <div class="tt-partner-type-card">
                <h3 class="tt-partner-type-title"><?php esc_html_e('Consultants & Advisors', 'teeptrak-partner'); ?></h3>
                <ul class="tt-partner-type-list">
                    <li><?php esc_html_e('Lean/Six Sigma practitioners', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Operations excellence consultants', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('Industry 4.0 advisors', 'teeptrak-partner'); ?></li>
                    <li><?php esc_html_e('OEE/TPM specialists', 'teeptrak-partner'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="tt-section">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('Results Our Partners Deliver', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-stats-grid">
            <div class="tt-stat-card">
                <div class="tt-stat-value">+23%</div>
                <div class="tt-stat-label"><?php esc_html_e('Average OEE improvement', 'teeptrak-partner'); ?></div>
                <div class="tt-stat-sublabel"><?php esc_html_e('Across partner-led implementations', 'teeptrak-partner'); ?></div>
            </div>

            <div class="tt-stat-card">
                <div class="tt-stat-value">&lt;6 <?php esc_html_e('months', 'teeptrak-partner'); ?></div>
                <div class="tt-stat-label"><?php esc_html_e('Average ROI timeline', 'teeptrak-partner'); ?></div>
                <div class="tt-stat-sublabel"><?php esc_html_e('From deployment to payback', 'teeptrak-partner'); ?></div>
            </div>

            <div class="tt-stat-card">
                <div class="tt-stat-value">360+</div>
                <div class="tt-stat-label"><?php esc_html_e('Plants worldwide', 'teeptrak-partner'); ?></div>
                <div class="tt-stat-sublabel"><?php esc_html_e('In 30+ countries', 'teeptrak-partner'); ?></div>
            </div>

            <div class="tt-stat-card">
                <div class="tt-stat-value">98%</div>
                <div class="tt-stat-label"><?php esc_html_e('Customer retention', 'teeptrak-partner'); ?></div>
                <div class="tt-stat-sublabel"><?php esc_html_e('Annual renewal rate', 'teeptrak-partner'); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="tt-cta-section">
    <div class="tt-container">
        <h2 class="tt-cta-title"><?php esc_html_e('Ready to Partner with TeepTrak?', 'teeptrak-partner'); ?></h2>
        <p class="tt-cta-subtitle"><?php esc_html_e('Join 50+ partners across 30+ countries helping manufacturers achieve operational excellence.', 'teeptrak-partner'); ?></p>
        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn tt-btn-white tt-btn-xl">
            <?php esc_html_e('Apply to Become a Partner', 'teeptrak-partner'); ?>
        </a>
        <p class="tt-cta-secondary">
            <?php esc_html_e('Questions? Contact our partner team at', 'teeptrak-partner'); ?>
            <a href="mailto:partners@teeptrak.com">partners@teeptrak.com</a>
        </p>
    </div>
</section>

<?php get_footer(); ?>
