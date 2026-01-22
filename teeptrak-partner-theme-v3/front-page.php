<?php
/**
 * Template Name: Partner Portal Landing
 * Front Page Template
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

// Redirect logged-in partners to dashboard
if (is_user_logged_in() && current_user_can('partner')) {
    wp_redirect(home_url('/dashboard/'));
    exit;
}

get_header();

$brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
?>

<!-- Hero Section -->
<section class="tt-hero">
    <div class="tt-container">
        <div class="tt-hero-content">
            <span class="tt-badge tt-badge-primary tt-badge-lg">
                <?php esc_html_e('Partner Program', 'teeptrak-partner'); ?>
            </span>
            <h1 class="tt-hero-title">
                <?php
                printf(
                    /* translators: %s: brand name */
                    esc_html__('Grow Your Business with %s', 'teeptrak-partner'),
                    esc_html($brand_name)
                );
                ?>
            </h1>
            <p class="tt-hero-subtitle">
                <?php esc_html_e('Join our network of successful partners delivering industrial IoT solutions to manufacturers worldwide. Earn competitive commissions while helping factories optimize their operations.', 'teeptrak-partner'); ?>
            </p>
            <div class="tt-hero-actions">
                <a href="<?php echo esc_url(home_url('/register/')); ?>" class="tt-btn tt-btn-primary tt-btn-lg">
                    <?php esc_html_e('Become a Partner', 'teeptrak-partner'); ?>
                    <?php echo teeptrak_icon('arrow-right', 20); ?>
                </a>
                <a href="#benefits" class="tt-btn tt-btn-secondary tt-btn-lg">
                    <?php esc_html_e('Learn More', 'teeptrak-partner'); ?>
                </a>
            </div>
            <div class="tt-hero-stats">
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">360+</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('Factories Powered', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">50+</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('Active Partners', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">15%</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('Avg. OEE Improvement', 'teeptrak-partner'); ?></span>
                </div>
            </div>
        </div>
        <div class="tt-hero-image">
            <img src="<?php echo esc_url(TEEPTRAK_ASSETS . '/images/hero-dashboard.png'); ?>" alt="<?php esc_attr_e('Partner Dashboard', 'teeptrak-partner'); ?>">
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="tt-section tt-section-gray">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('Why Partner With Us?', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('Access exclusive benefits designed to help you succeed', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-benefits-grid">
            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('dollar-sign', 32); ?>
                </div>
                <h3><?php esc_html_e('Competitive Commissions', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Earn up to 20% commission on every deal. Premium and Elite partners receive enhanced rates and recurring revenue opportunities.', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('shield', 32); ?>
                </div>
                <h3><?php esc_html_e('Deal Protection', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('90-day deal registration protection ensures your leads are secured. Focus on closing without worrying about competition.', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('book-open', 32); ?>
                </div>
                <h3><?php esc_html_e('Comprehensive Training', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Access our complete training library with technical certifications, sales playbooks, and product deep-dives.', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('users', 32); ?>
                </div>
                <h3><?php esc_html_e('Dedicated Support', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Get assigned partner manager support, priority technical assistance, and access to co-selling opportunities.', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('folder', 32); ?>
                </div>
                <h3><?php esc_html_e('Sales Resources', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Download professional presentations, case studies, ROI calculators, and customizable proposals.', 'teeptrak-partner'); ?></p>
            </div>

            <div class="tt-benefit-card">
                <div class="tt-benefit-icon">
                    <?php echo teeptrak_icon('trending-up', 32); ?>
                </div>
                <h3><?php esc_html_e('Market Opportunity', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Tap into the growing Industry 4.0 market. Our solutions address real pain points with proven ROI.', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Partner Tiers Section -->
<section id="tiers" class="tt-section">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('Partner Tiers', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('Unlock more benefits as you grow', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-tiers-grid">
            <div class="tt-tier-card">
                <div class="tt-tier-header tt-tier-registered">
                    <h3><?php esc_html_e('Registered', 'teeptrak-partner'); ?></h3>
                    <p class="tt-tier-commission">10% <?php esc_html_e('Commission', 'teeptrak-partner'); ?></p>
                </div>
                <ul class="tt-tier-features">
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Deal registration', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Basic training access', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Sales materials', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Email support', 'teeptrak-partner'); ?></li>
                </ul>
                <div class="tt-tier-requirement">
                    <span><?php esc_html_e('Entry level', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <div class="tt-tier-card">
                <div class="tt-tier-header tt-tier-certified">
                    <h3><?php esc_html_e('Certified', 'teeptrak-partner'); ?></h3>
                    <p class="tt-tier-commission">12% <?php esc_html_e('Commission', 'teeptrak-partner'); ?></p>
                </div>
                <ul class="tt-tier-features">
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Everything in Registered', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Full training library', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Technical certification', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Priority support', 'teeptrak-partner'); ?></li>
                </ul>
                <div class="tt-tier-requirement">
                    <span><?php esc_html_e('Requires: 3 closed deals', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <div class="tt-tier-card tt-tier-featured">
                <div class="tt-tier-badge"><?php esc_html_e('Popular', 'teeptrak-partner'); ?></div>
                <div class="tt-tier-header tt-tier-premium">
                    <h3><?php esc_html_e('Premium', 'teeptrak-partner'); ?></h3>
                    <p class="tt-tier-commission">15% <?php esc_html_e('Commission', 'teeptrak-partner'); ?></p>
                </div>
                <ul class="tt-tier-features">
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Everything in Certified', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Dedicated partner manager', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Co-selling support', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Lead sharing', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Quarterly business reviews', 'teeptrak-partner'); ?></li>
                </ul>
                <div class="tt-tier-requirement">
                    <span><?php esc_html_e('Requires: €100K annual revenue', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <div class="tt-tier-card">
                <div class="tt-tier-header tt-tier-elite">
                    <h3><?php esc_html_e('Elite', 'teeptrak-partner'); ?></h3>
                    <p class="tt-tier-commission">20% <?php esc_html_e('Commission', 'teeptrak-partner'); ?></p>
                </div>
                <ul class="tt-tier-features">
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Everything in Premium', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('MDF (Marketing Development Funds)', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Joint marketing campaigns', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Executive sponsorship', 'teeptrak-partner'); ?></li>
                    <li><?php echo teeptrak_icon('check', 16); ?> <?php esc_html_e('Early product access', 'teeptrak-partner'); ?></li>
                </ul>
                <div class="tt-tier-requirement">
                    <span><?php esc_html_e('Requires: €250K annual revenue', 'teeptrak-partner'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="tt-section tt-section-gray">
    <div class="tt-container">
        <div class="tt-section-header">
            <h2 class="tt-section-title"><?php esc_html_e('How It Works', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('Get started in three simple steps', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-steps-grid">
            <div class="tt-step">
                <div class="tt-step-number">1</div>
                <h3><?php esc_html_e('Apply & Get Approved', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Submit your application. Our team will review and approve qualified partners within 48 hours.', 'teeptrak-partner'); ?></p>
            </div>
            <div class="tt-step-arrow"><?php echo teeptrak_icon('arrow-right', 24); ?></div>
            <div class="tt-step">
                <div class="tt-step-number">2</div>
                <h3><?php esc_html_e('Complete Training', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Access our training platform to learn about our products, sales methodology, and technical requirements.', 'teeptrak-partner'); ?></p>
            </div>
            <div class="tt-step-arrow"><?php echo teeptrak_icon('arrow-right', 24); ?></div>
            <div class="tt-step">
                <div class="tt-step-number">3</div>
                <h3><?php esc_html_e('Start Selling', 'teeptrak-partner'); ?></h3>
                <p><?php esc_html_e('Register deals, track your pipeline, and earn commissions on closed business.', 'teeptrak-partner'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="tt-section tt-cta-section">
    <div class="tt-container">
        <div class="tt-cta-content">
            <h2><?php esc_html_e('Ready to Grow Your Business?', 'teeptrak-partner'); ?></h2>
            <p><?php esc_html_e('Join our partner network and start earning commissions on industrial IoT solutions.', 'teeptrak-partner'); ?></p>
            <div class="tt-cta-actions">
                <a href="<?php echo esc_url(home_url('/register/')); ?>" class="tt-btn tt-btn-white tt-btn-lg">
                    <?php esc_html_e('Apply Now', 'teeptrak-partner'); ?>
                    <?php echo teeptrak_icon('arrow-right', 20); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="tt-btn tt-btn-outline-white tt-btn-lg">
                    <?php esc_html_e('Contact Sales', 'teeptrak-partner'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
