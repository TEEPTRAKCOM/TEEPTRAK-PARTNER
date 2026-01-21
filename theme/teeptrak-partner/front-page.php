<?php
/**
 * Template Name: Landing Page
 * Front page template for the public-facing partner landing page
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Content data from teeptrak-partner-content.json
$benefits = array(
    array(
        'icon' => 'dollar-sign',
        'bg_color' => '#FEE2E2',
        'icon_color' => '#E63946',
        'title' => __('Attractive Commissions', 'teeptrak-partner'),
        'description' => __('Earn 15-30% commission on every deal based on your partner tier', 'teeptrak-partner'),
    ),
    array(
        'icon' => 'shield',
        'bg_color' => '#DBEAFE',
        'icon_color' => '#3B82F6',
        'title' => __('90-Day Deal Protection', 'teeptrak-partner'),
        'description' => __('Your registered opportunities are protected for 90 days—no channel conflict', 'teeptrak-partner'),
    ),
    array(
        'icon' => 'graduation-cap',
        'bg_color' => '#DCFCE7',
        'icon_color' => '#22C55E',
        'title' => __('Complete Certification', 'teeptrak-partner'),
        'description' => __('Free training program covering OEE fundamentals, product features, and sales methodology', 'teeptrak-partner'),
    ),
    array(
        'icon' => 'folder',
        'bg_color' => '#FEF3C7',
        'icon_color' => '#F59E0B',
        'title' => __('Sales Enablement', 'teeptrak-partner'),
        'description' => __('Brochures, case studies, ROI calculators, and ready-to-use email templates', 'teeptrak-partner'),
    ),
    array(
        'icon' => 'users',
        'bg_color' => '#F3E8FF',
        'icon_color' => '#9333EA',
        'title' => __('Dedicated Support', 'teeptrak-partner'),
        'description' => __('Personal Partner Success Manager for Gold and Platinum partners', 'teeptrak-partner'),
    ),
    array(
        'icon' => 'activity',
        'bg_color' => '#CFFAFE',
        'icon_color' => '#0891B2',
        'title' => __('Real-Time Dashboard', 'teeptrak-partner'),
        'description' => __('Track deals, commissions, training progress, and performance in one place', 'teeptrak-partner'),
    ),
);

$tiers = array(
    array(
        'name' => __('Bronze', 'teeptrak-partner'),
        'commission_rate' => 15,
        'gradient' => 'linear-gradient(135deg, #CD7F32, #8B4513)',
        'requirements' => __('Complete basic certification', 'teeptrak-partner'),
        'featured' => false,
        'benefits' => array(
            __('Deal Registration & Protection', 'teeptrak-partner'),
            __('Basic Training Access', 'teeptrak-partner'),
            __('Sales Resource Library', 'teeptrak-partner'),
            __('Email Support (48h response)', 'teeptrak-partner'),
        ),
    ),
    array(
        'name' => __('Silver', 'teeptrak-partner'),
        'commission_rate' => 20,
        'gradient' => 'linear-gradient(135deg, #C0C0C0, #A8A8A8)',
        'requirements' => __('2+ closed deals, advanced certification', 'teeptrak-partner'),
        'featured' => false,
        'benefits' => array(
            __('Everything in Bronze', 'teeptrak-partner'),
            __('Co-Marketing Materials', 'teeptrak-partner'),
            __('Priority Technical Support', 'teeptrak-partner'),
            __('Quarterly Business Reviews', 'teeptrak-partner'),
        ),
    ),
    array(
        'name' => __('Gold', 'teeptrak-partner'),
        'commission_rate' => 25,
        'gradient' => 'linear-gradient(135deg, #FFD700, #FFA500)',
        'requirements' => __('5+ deals, €100K+ pipeline, full certification', 'teeptrak-partner'),
        'featured' => true,
        'benefits' => array(
            __('Everything in Silver', 'teeptrak-partner'),
            __('Dedicated Partner Success Manager', 'teeptrak-partner'),
            __('Lead Sharing from TeepTrak Marketing', 'teeptrak-partner'),
            __('Joint Customer Presentations', 'teeptrak-partner'),
            __('Early Access to New Features', 'teeptrak-partner'),
        ),
    ),
    array(
        'name' => __('Platinum', 'teeptrak-partner'),
        'commission_rate' => 30,
        'gradient' => 'linear-gradient(135deg, #E5E4E2, #B4B4B4)',
        'requirements' => __('10+ deals, €250K+ pipeline, strategic alignment', 'teeptrak-partner'),
        'featured' => false,
        'benefits' => array(
            __('Everything in Gold', 'teeptrak-partner'),
            __('Strategic Planning Sessions', 'teeptrak-partner'),
            __('Executive Access & Sponsorship', 'teeptrak-partner'),
            __('Custom Marketing Campaigns', 'teeptrak-partner'),
            __('Preferred Implementation Partner Status', 'teeptrak-partner'),
        ),
    ),
);

$process_steps = array(
    array(
        'number' => 1,
        'icon' => 'clipboard',
        'title' => __('Apply', 'teeptrak-partner'),
        'description' => __('Submit your application with company details and target industries', 'teeptrak-partner'),
    ),
    array(
        'number' => 2,
        'icon' => 'rocket',
        'title' => __('Onboard', 'teeptrak-partner'),
        'description' => __('Complete certification training and access the partner portal', 'teeptrak-partner'),
    ),
    array(
        'number' => 3,
        'icon' => 'file-plus',
        'title' => __('Register Deals', 'teeptrak-partner'),
        'description' => __('Submit opportunities through the portal for 90-day protection', 'teeptrak-partner'),
    ),
    array(
        'number' => 4,
        'icon' => 'handshake',
        'title' => __('Close & Earn', 'teeptrak-partner'),
        'description' => __('Work with our sales team to close deals and earn commissions', 'teeptrak-partner'),
    ),
    array(
        'number' => 5,
        'icon' => 'trending-up',
        'title' => __('Grow', 'teeptrak-partner'),
        'description' => __('Hit milestones to advance tiers and unlock more benefits', 'teeptrak-partner'),
    ),
    array(
        'number' => 6,
        'icon' => 'refresh',
        'title' => __('Renew', 'teeptrak-partner'),
        'description' => __('Annual renewal with performance review and tier assessment', 'teeptrak-partner'),
    ),
);

$ideal_partners = array(
    array(
        'title' => __('Systems Integrators', 'teeptrak-partner'),
        'criteria' => array(
            __('MES/ERP implementation specialists', 'teeptrak-partner'),
            __('Industrial automation companies', 'teeptrak-partner'),
            __('Smart factory consultants', 'teeptrak-partner'),
            __('Minimum 3+ years in manufacturing IT', 'teeptrak-partner'),
        ),
    ),
    array(
        'title' => __('Value-Added Resellers', 'teeptrak-partner'),
        'criteria' => array(
            __('Industrial equipment distributors', 'teeptrak-partner'),
            __('Technology solution providers', 'teeptrak-partner'),
            __('Regional IT specialists', 'teeptrak-partner'),
            __('Strong manufacturing client base', 'teeptrak-partner'),
        ),
    ),
    array(
        'title' => __('Consultants & Advisors', 'teeptrak-partner'),
        'criteria' => array(
            __('Lean/Six Sigma practitioners', 'teeptrak-partner'),
            __('Operations excellence consultants', 'teeptrak-partner'),
            __('Industry 4.0 advisors', 'teeptrak-partner'),
            __('OEE/TPM specialists', 'teeptrak-partner'),
        ),
    ),
);

$result_stats = array(
    array(
        'value' => '+23%',
        'label' => __('Average OEE improvement', 'teeptrak-partner'),
        'sublabel' => __('Across partner-led implementations', 'teeptrak-partner'),
    ),
    array(
        'value' => '<6 ' . __('months', 'teeptrak-partner'),
        'label' => __('Average ROI timeline', 'teeptrak-partner'),
        'sublabel' => __('From deployment to payback', 'teeptrak-partner'),
    ),
    array(
        'value' => '360+',
        'label' => __('Plants worldwide', 'teeptrak-partner'),
        'sublabel' => __('In 30+ countries', 'teeptrak-partner'),
    ),
    array(
        'value' => '98%',
        'label' => __('Customer retention', 'teeptrak-partner'),
        'sublabel' => __('Annual renewal rate', 'teeptrak-partner'),
    ),
);

$client_logos = array('STELLANTIS', 'ALSTOM', 'THALES', 'RENAULT', 'AIRBUS', 'HUTCHINSON');

// Icon SVG helper function
function tt_get_icon($name, $size = 24) {
    $icons = array(
        'dollar-sign' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
        'shield' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
        'graduation-cap' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'folder' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>',
        'users' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'activity' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
        'clipboard' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>',
        'rocket' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>',
        'file-plus' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>',
        'handshake' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 1 0 3-3"></path><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88"></path><path d="m16 16-1.5-1.5"></path><path d="M5 21a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="M19 3h0a2 2 0 0 1 2 2v0"></path><path d="M21 9v10a2 2 0 0 1-2 2h-4"></path><path d="M3 9h6"></path></svg>',
        'trending-up' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
        'refresh' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>',
        'check' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        'arrow-right' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'building' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>

<!-- Hero Section -->
<section class="tt-landing-hero">
    <div class="tt-container">
        <div class="tt-hero-content tt-text-center">
            <h1 class="tt-hero-title">
                <?php _e('Become a TeepTrak Partner', 'teeptrak-partner'); ?>
            </h1>

            <p class="tt-hero-subtitle">
                <?php _e('Join our partner ecosystem and help manufacturers achieve 5-30% productivity gains with proven Industrial IoT solutions deployed in 360+ plants worldwide.', 'teeptrak-partner'); ?>
            </p>

            <div class="tt-hero-cta tt-flex tt-justify-center tt-gap-4 tt-flex-wrap">
                <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn tt-btn-primary tt-btn-xl">
                    <?php _e('Apply Now', 'teeptrak-partner'); ?>
                    <?php echo tt_get_icon('arrow-right', 20); ?>
                </a>
                <a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>" class="tt-btn tt-btn-outline tt-btn-xl">
                    <?php _e('Partner Login', 'teeptrak-partner'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Client Logos Bar -->
<section class="tt-logos-section">
    <div class="tt-container">
        <p class="tt-logos-label"><?php _e('TRUSTED BY INDUSTRY LEADERS', 'teeptrak-partner'); ?></p>
        <div class="tt-logos-wrapper">
            <?php foreach ($client_logos as $logo) : ?>
                <span class="tt-logo-item"><?php echo esc_html($logo); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="tt-landing-section">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('Partner Benefits', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Everything you need to sell, implement, and support TeepTrak solutions', 'teeptrak-partner'); ?></p>

        <div class="tt-benefits-grid">
            <?php foreach ($benefits as $benefit) : ?>
                <div class="tt-benefit-card">
                    <div class="tt-benefit-icon" style="background-color: <?php echo esc_attr($benefit['bg_color']); ?>; color: <?php echo esc_attr($benefit['icon_color']); ?>;">
                        <?php echo tt_get_icon($benefit['icon'], 24); ?>
                    </div>
                    <h3 class="tt-benefit-title"><?php echo esc_html($benefit['title']); ?></h3>
                    <p class="tt-benefit-desc"><?php echo esc_html($benefit['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Partner Tiers Section -->
<section id="tiers" class="tt-landing-section tt-landing-section-alt">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('Partner Tiers', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Grow with us and unlock more benefits at every level', 'teeptrak-partner'); ?></p>

        <div class="tt-tiers-grid">
            <?php foreach ($tiers as $tier) : ?>
                <div class="tt-tier-card <?php echo $tier['featured'] ? 'is-featured' : ''; ?>">
                    <?php if ($tier['featured']) : ?>
                        <div class="tt-tier-featured-badge"><?php _e('Most Popular', 'teeptrak-partner'); ?></div>
                    <?php endif; ?>

                    <div class="tt-tier-header" style="background: <?php echo esc_attr($tier['gradient']); ?>;">
                        <h3 class="tt-tier-name"><?php echo esc_html($tier['name']); ?></h3>
                        <div class="tt-tier-rate">
                            <span class="tt-tier-rate-value"><?php echo esc_html($tier['commission_rate']); ?>%</span>
                            <span class="tt-tier-rate-label"><?php _e('Commission', 'teeptrak-partner'); ?></span>
                        </div>
                    </div>

                    <div class="tt-tier-body">
                        <div class="tt-tier-requirements">
                            <span class="tt-tier-req-label"><?php _e('Requirements:', 'teeptrak-partner'); ?></span>
                            <span class="tt-tier-req-text"><?php echo esc_html($tier['requirements']); ?></span>
                        </div>

                        <ul class="tt-tier-benefits-list">
                            <?php foreach ($tier['benefits'] as $benefit) : ?>
                                <li>
                                    <span class="tt-tier-check"><?php echo tt_get_icon('check', 16); ?></span>
                                    <?php echo esc_html($benefit); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn <?php echo $tier['featured'] ? 'tt-btn-primary' : 'tt-btn-outline'; ?> tt-btn-block">
                            <?php _e('Apply Now', 'teeptrak-partner'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="process" class="tt-landing-section">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('How the Program Works', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Get started in 6 simple steps', 'teeptrak-partner'); ?></p>

        <div class="tt-process-timeline">
            <?php foreach ($process_steps as $index => $step) : ?>
                <div class="tt-process-step">
                    <div class="tt-process-step-icon">
                        <?php echo tt_get_icon($step['icon'], 28); ?>
                        <span class="tt-process-step-num"><?php echo esc_html($step['number']); ?></span>
                    </div>
                    <?php if ($index < count($process_steps) - 1) : ?>
                        <div class="tt-process-connector"></div>
                    <?php endif; ?>
                    <h3 class="tt-process-step-title"><?php echo esc_html($step['title']); ?></h3>
                    <p class="tt-process-step-desc"><?php echo esc_html($step['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ideal Partners Section -->
<section class="tt-landing-section tt-landing-section-alt">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e("Who We're Looking For", 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('We partner with organizations that understand manufacturing operations', 'teeptrak-partner'); ?></p>

        <div class="tt-partners-grid">
            <?php foreach ($ideal_partners as $partner_type) : ?>
                <div class="tt-partner-type-card">
                    <div class="tt-partner-type-icon">
                        <?php echo tt_get_icon('building', 32); ?>
                    </div>
                    <h3 class="tt-partner-type-title"><?php echo esc_html($partner_type['title']); ?></h3>
                    <ul class="tt-partner-type-criteria">
                        <?php foreach ($partner_type['criteria'] as $criterion) : ?>
                            <li><?php echo esc_html($criterion); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Results Stats Section -->
<section class="tt-landing-section">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('Results Our Partners Deliver', 'teeptrak-partner'); ?></h2>

        <div class="tt-results-grid">
            <?php foreach ($result_stats as $stat) : ?>
                <div class="tt-result-card">
                    <div class="tt-result-value"><?php echo esc_html($stat['value']); ?></div>
                    <div class="tt-result-label"><?php echo esc_html($stat['label']); ?></div>
                    <div class="tt-result-sublabel"><?php echo esc_html($stat['sublabel']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="tt-cta-section">
    <div class="tt-container tt-text-center">
        <h2 class="tt-cta-title"><?php _e('Ready to Partner with TeepTrak?', 'teeptrak-partner'); ?></h2>
        <p class="tt-cta-subtitle"><?php _e('Join 50+ partners across 30+ countries helping manufacturers achieve operational excellence.', 'teeptrak-partner'); ?></p>
        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="tt-btn tt-btn-white tt-btn-xl">
            <?php _e('Apply to Become a Partner', 'teeptrak-partner'); ?>
            <?php echo tt_get_icon('arrow-right', 20); ?>
        </a>
        <p class="tt-cta-contact">
            <?php _e('Questions? Contact our partner team at', 'teeptrak-partner'); ?>
            <a href="mailto:partners@teeptrak.com">partners@teeptrak.com</a>
        </p>
    </div>
</section>

<style>
/* Front Page Specific Styles */

/* Hero Section */
.tt-landing-hero {
    padding: 140px 0 80px;
    background: linear-gradient(135deg, #F8F9FA 0%, #FFFFFF 50%, #FEF2F2 100%);
}

.tt-hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.tt-hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.1;
    color: var(--tt-gray-900);
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .tt-hero-title {
        font-size: 2.25rem;
    }
}

.tt-hero-subtitle {
    font-size: 1.25rem;
    color: var(--tt-gray-500);
    line-height: 1.6;
    margin-bottom: 40px;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
}

.tt-hero-cta {
    margin-top: 32px;
}

/* Client Logos */
.tt-logos-section {
    padding: 48px 0;
    background-color: var(--tt-white);
    border-top: 1px solid var(--tt-gray-100);
    border-bottom: 1px solid var(--tt-gray-100);
}

.tt-logos-label {
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    color: var(--tt-gray-400);
    margin-bottom: 24px;
}

.tt-logos-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 48px;
}

.tt-logo-item {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--tt-gray-300);
    letter-spacing: 0.15em;
}

/* Benefits Section */
.tt-benefit-card {
    background: var(--tt-white);
    border-radius: 16px;
    padding: 32px;
    border: 1px solid var(--tt-gray-100);
    transition: all 0.2s ease;
}

.tt-benefit-card:hover {
    box-shadow: var(--tt-shadow-xl);
    transform: translateY(-2px);
    border-color: rgba(230, 57, 70, 0.2);
}

.tt-benefit-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.tt-benefit-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin-bottom: 8px;
}

.tt-benefit-desc {
    font-size: 0.9375rem;
    color: var(--tt-gray-500);
    line-height: 1.6;
    margin: 0;
}

/* Partner Tiers */
.tt-tier-card {
    background: var(--tt-white);
    border-radius: 16px;
    overflow: hidden;
    border: 2px solid var(--tt-gray-100);
    position: relative;
    transition: all 0.2s ease;
}

.tt-tier-card.is-featured {
    border-color: var(--tt-red);
    box-shadow: 0 20px 40px -10px rgba(230, 57, 70, 0.2);
    transform: scale(1.02);
}

.tt-tier-featured-badge {
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--tt-red);
    color: white;
    padding: 6px 20px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0 0 8px 8px;
    z-index: 10;
}

.tt-tier-header {
    padding: 32px 24px;
    text-align: center;
    color: white;
}

.tt-tier-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.tt-tier-rate-value {
    font-size: 3rem;
    font-weight: 700;
    display: block;
    line-height: 1;
}

.tt-tier-rate-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.tt-tier-body {
    padding: 24px;
}

.tt-tier-requirements {
    background: var(--tt-gray-50);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
}

.tt-tier-req-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--tt-gray-500);
    display: block;
    margin-bottom: 4px;
}

.tt-tier-req-text {
    font-size: 0.875rem;
    color: var(--tt-gray-700);
}

.tt-tier-benefits-list {
    list-style: none;
    padding: 0;
    margin: 0 0 24px 0;
}

.tt-tier-benefits-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 0;
    font-size: 0.875rem;
    color: var(--tt-gray-700);
}

.tt-tier-check {
    color: var(--tt-success);
    flex-shrink: 0;
    margin-top: 2px;
}

/* Process Timeline */
.tt-process-timeline {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    position: relative;
}

@media (max-width: 1024px) {
    .tt-process-timeline {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    .tt-process-timeline {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tt-process-step {
    text-align: center;
    position: relative;
}

.tt-process-step-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--tt-red-light);
    color: var(--tt-red);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    position: relative;
}

.tt-process-step-num {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 28px;
    height: 28px;
    background: var(--tt-red);
    color: white;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tt-process-step-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin-bottom: 8px;
}

.tt-process-step-desc {
    font-size: 0.8125rem;
    color: var(--tt-gray-500);
    line-height: 1.5;
    margin: 0;
}

.tt-process-connector {
    display: none;
}

/* Ideal Partners */
.tt-partners-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

@media (max-width: 768px) {
    .tt-partners-grid {
        grid-template-columns: 1fr;
    }
}

.tt-partner-type-card {
    background: var(--tt-white);
    border-radius: 16px;
    padding: 32px;
    border: 1px solid var(--tt-gray-100);
    text-align: center;
}

.tt-partner-type-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: var(--tt-red-light);
    color: var(--tt-red);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.tt-partner-type-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin-bottom: 16px;
}

.tt-partner-type-criteria {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}

.tt-partner-type-criteria li {
    position: relative;
    padding-left: 20px;
    margin-bottom: 10px;
    font-size: 0.875rem;
    color: var(--tt-gray-600);
}

.tt-partner-type-criteria li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: var(--tt-red);
    font-weight: bold;
}

/* Results Stats */
.tt-results-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

@media (max-width: 768px) {
    .tt-results-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tt-result-card {
    text-align: center;
    padding: 32px 16px;
}

.tt-result-value {
    font-size: 3rem;
    font-weight: 700;
    color: var(--tt-red);
    line-height: 1;
    margin-bottom: 12px;
}

.tt-result-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--tt-gray-900);
    margin-bottom: 4px;
}

.tt-result-sublabel {
    font-size: 0.875rem;
    color: var(--tt-gray-500);
}

/* CTA Section */
.tt-cta-section {
    padding: 100px 0;
    background: var(--tt-dark);
}

.tt-cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 16px;
}

.tt-cta-subtitle {
    font-size: 1.25rem;
    color: rgba(255,255,255,0.8);
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.tt-btn-white {
    background: white;
    color: var(--tt-red);
}

.tt-btn-white:hover {
    background: var(--tt-gray-100);
}

.tt-cta-contact {
    margin-top: 32px;
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.6);
}

.tt-cta-contact a {
    color: white;
    text-decoration: underline;
}

.tt-cta-contact a:hover {
    color: var(--tt-coral);
}
</style>

<?php get_footer(); ?>
