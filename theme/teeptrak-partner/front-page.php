<?php
/**
 * Template Name: Landing Page
 * Template for the public-facing landing page
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<!-- Hero Section -->
<section class="tt-landing-hero">
    <div class="tt-container">
        <div class="tt-grid lg:tt-grid-cols-2 tt-gap-12 tt-items-center">
            <div>
                <div class="tt-hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <span><?php _e('Global Partner Program', 'teeptrak-partner'); ?></span>
                </div>
                
                <h1 class="tt-hero-title">
                    <?php _e('Grow Your Business with', 'teeptrak-partner'); ?>
                    <span class="tt-text-red">TeepTrak</span>
                </h1>
                
                <p class="tt-hero-subtitle">
                    <?php _e('Join our global partner network and help manufacturers achieve 5-30% productivity gains with plug-and-play OEE solutions', 'teeptrak-partner'); ?>
                </p>
                
                <div class="tt-flex tt-flex-col sm:tt-flex-row tt-gap-4">
                    <a href="<?php echo home_url('/become-partner/'); ?>" class="tt-btn tt-btn-primary tt-btn-xl">
                        <?php _e('Apply Now', 'teeptrak-partner'); ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <a href="#benefits" class="tt-btn tt-btn-outline tt-btn-xl">
                        <?php _e('Learn More', 'teeptrak-partner'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="tt-stats-grid">
                <div class="tt-stat-card">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#EB352B" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <p class="tt-stat-value">50+</p>
                    <p class="tt-stat-label"><?php _e('Channel Partners', 'teeptrak-partner'); ?></p>
                </div>
                
                <div class="tt-stat-card">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#EB352B" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <p class="tt-stat-value">30+</p>
                    <p class="tt-stat-label"><?php _e('Countries', 'teeptrak-partner'); ?></p>
                </div>
                
                <div class="tt-stat-card">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#EB352B" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <p class="tt-stat-value">360+</p>
                    <p class="tt-stat-label"><?php _e('Connected Plants', 'teeptrak-partner'); ?></p>
                </div>
                
                <div class="tt-stat-card">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#EB352B" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <p class="tt-stat-value">120+</p>
                    <p class="tt-stat-label"><?php _e('Industrial Clients', 'teeptrak-partner'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Client Logos -->
<section class="tt-logos-section">
    <div class="tt-container">
        <div class="tt-logos-wrapper">
            <?php
            $logos = array('STELLANTIS', 'ALSTOM', 'THALES', 'RENAULT', 'AIRBUS', 'BOSCH');
            foreach ($logos as $logo) :
            ?>
                <span class="tt-logo-item"><?php echo esc_html($logo); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="tt-landing-section">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('Why Partner with TeepTrak?', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Join the leading industrial IoT partner ecosystem', 'teeptrak-partner'); ?></p>
        
        <div class="tt-benefits-grid">
            <?php
            $benefits = array(
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>',
                    'color' => '#22C55E',
                    'bg' => '#DCFCE7',
                    'title' => __('Proven ROI', 'teeptrak-partner'),
                    'desc' => __('5-30% productivity gains with ROI in 6-12 months', 'teeptrak-partner'),
                ),
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
                    'color' => '#3B82F6',
                    'bg' => '#DBEAFE',
                    'title' => __('Major References', 'teeptrak-partner'),
                    'desc' => __('Stellantis, Alstom, Thales, Renault and 120+ clients', 'teeptrak-partner'),
                ),
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
                    'color' => '#F59E0B',
                    'bg' => '#FEF3C7',
                    'title' => __('Plug & Play', 'teeptrak-partner'),
                    'desc' => __('Quick deployment, faster time-to-value for customers', 'teeptrak-partner'),
                ),
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>',
                    'color' => '#8B5CF6',
                    'bg' => '#EDE9FE',
                    'title' => __('Recurring Revenue', 'teeptrak-partner'),
                    'desc' => __('SaaS model with attractive partner margins', 'teeptrak-partner'),
                ),
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
                    'color' => '#EB352B',
                    'bg' => '#FEE2E2',
                    'title' => __('Training & Certification', 'teeptrak-partner'),
                    'desc' => __('Comprehensive Academy with certifications', 'teeptrak-partner'),
                ),
                array(
                    'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>',
                    'color' => '#06B6D4',
                    'bg' => '#CFFAFE',
                    'title' => __('Marketing Support', 'teeptrak-partner'),
                    'desc' => __('Co-branded materials and MDF funds', 'teeptrak-partner'),
                ),
            );
            
            foreach ($benefits as $benefit) :
            ?>
                <div class="tt-benefit-card">
                    <div class="tt-benefit-icon" style="background-color: <?php echo esc_attr($benefit['bg']); ?>; color: <?php echo esc_attr($benefit['color']); ?>;">
                        <?php echo $benefit['icon']; ?>
                    </div>
                    <h3 class="tt-text-lg tt-font-semibold tt-mb-2"><?php echo esc_html($benefit['title']); ?></h3>
                    <p class="tt-text-gray-500"><?php echo esc_html($benefit['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Tiers Section -->
<section id="tiers" class="tt-landing-section tt-landing-section-alt">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('Partner Tiers & Commissions', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Grow with us and unlock higher rewards', 'teeptrak-partner'); ?></p>
        
        <div class="tt-tiers-grid">
            <?php
            $tiers = array(
                array(
                    'name' => __('Bronze', 'teeptrak-partner'),
                    'rate' => '15%',
                    'gradient' => 'linear-gradient(135deg, #CD7F32 0%, #8B4513 100%)',
                    'popular' => false,
                ),
                array(
                    'name' => __('Silver', 'teeptrak-partner'),
                    'rate' => '20%',
                    'gradient' => 'linear-gradient(135deg, #D1D5DB 0%, #6B7280 100%)',
                    'popular' => false,
                ),
                array(
                    'name' => __('Gold', 'teeptrak-partner'),
                    'rate' => '25%',
                    'gradient' => 'linear-gradient(135deg, #FDE047 0%, #CA8A04 100%)',
                    'popular' => true,
                ),
                array(
                    'name' => __('Platinum', 'teeptrak-partner'),
                    'rate' => '30%',
                    'gradient' => 'linear-gradient(135deg, #F3F4F6 0%, #9CA3AF 100%)',
                    'popular' => false,
                ),
            );
            
            foreach ($tiers as $tier) :
            ?>
                <div class="tt-tier-card <?php echo $tier['popular'] ? 'is-popular' : ''; ?>">
                    <?php if ($tier['popular']) : ?>
                        <div class="tt-tier-popular-badge"><?php _e('Popular', 'teeptrak-partner'); ?></div>
                    <?php endif; ?>
                    
                    <div class="tt-tier-card-header" style="background: <?php echo esc_attr($tier['gradient']); ?>;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <h3 class="tt-text-xl tt-font-bold tt-mt-2"><?php echo esc_html($tier['name']); ?></h3>
                        <p class="tt-text-4xl tt-font-bold tt-mt-2"><?php echo esc_html($tier['rate']); ?></p>
                        <p class="tt-text-white/80"><?php _e('Commission', 'teeptrak-partner'); ?></p>
                    </div>
                    
                    <div class="tt-tier-card-body">
                        <a href="<?php echo home_url('/become-partner/'); ?>" class="tt-btn tt-btn-primary tt-btn-block">
                            <?php _e('Apply Now', 'teeptrak-partner'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Process Section -->
<section id="process" class="tt-landing-section">
    <div class="tt-container">
        <h2 class="tt-section-title"><?php _e('How It Works', 'teeptrak-partner'); ?></h2>
        <p class="tt-section-subtitle"><?php _e('Get started in 4 simple steps', 'teeptrak-partner'); ?></p>
        
        <div class="tt-process-grid">
            <?php
            $steps = array(
                array(
                    'num' => '01',
                    'title' => __('Apply', 'teeptrak-partner'),
                    'desc' => __('Complete the partner application form', 'teeptrak-partner'),
                    'icon' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>',
                ),
                array(
                    'num' => '02',
                    'title' => __('Onboard', 'teeptrak-partner'),
                    'desc' => __('Sign agreement and complete training', 'teeptrak-partner'),
                    'icon' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
                ),
                array(
                    'num' => '03',
                    'title' => __('Certify', 'teeptrak-partner'),
                    'desc' => __('Pass certification quiz (80% minimum)', 'teeptrak-partner'),
                    'icon' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>',
                ),
                array(
                    'num' => '04',
                    'title' => __('Sell & Earn', 'teeptrak-partner'),
                    'desc' => __('Register deals and earn commissions', 'teeptrak-partner'),
                    'icon' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                ),
            );
            
            foreach ($steps as $step) :
            ?>
                <div class="tt-process-step">
                    <div class="tt-process-icon-wrapper">
                        <div class="tt-process-icon">
                            <?php echo $step['icon']; ?>
                        </div>
                        <div class="tt-process-num"><?php echo esc_html($step['num']); ?></div>
                    </div>
                    <h3 class="tt-text-lg tt-font-semibold tt-mb-2"><?php echo esc_html($step['title']); ?></h3>
                    <p class="tt-text-gray-500 tt-text-sm"><?php echo esc_html($step['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="tt-cta-section">
    <div class="tt-container tt-text-center">
        <h2 class="tt-cta-title"><?php _e('Ready to Grow Your Business?', 'teeptrak-partner'); ?></h2>
        <p class="tt-cta-subtitle"><?php _e('Join 50+ partners in 30+ countries', 'teeptrak-partner'); ?></p>
        <a href="<?php echo home_url('/become-partner/'); ?>" class="tt-btn tt-btn-lg" style="background: white; color: #EB352B;">
            <?php _e('Become a Partner', 'teeptrak-partner'); ?>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</section>

<style>
/* Landing Page Specific Styles */
.tt-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #FEE2E2;
    color: #EB352B;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
}

.tt-hero-title {
    font-size: 48px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .tt-hero-title { font-size: 32px; }
}

.tt-hero-subtitle {
    font-size: 18px;
    color: #6B7280;
    margin-bottom: 32px;
    line-height: 1.6;
}

.tt-text-red { color: #EB352B; }

.tt-logos-section {
    padding: 40px 0;
    background-color: #F9FAFB;
    border-top: 1px solid #E5E7EB;
    border-bottom: 1px solid #E5E7EB;
}

.tt-logos-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 48px;
    opacity: 0.5;
}

.tt-logo-item {
    font-size: 20px;
    font-weight: 700;
    color: #9CA3AF;
    letter-spacing: 2px;
}

.tt-process-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    text-align: center;
}

@media (max-width: 768px) {
    .tt-process-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tt-process-step {
    text-align: center;
}

.tt-process-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
}

.tt-process-icon {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background-color: #FEE2E2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #EB352B;
}

.tt-process-num {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 32px;
    height: 32px;
    background-color: #EB352B;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

.tt-tier-popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #EB352B;
    color: white;
    padding: 4px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.tt-tier-card {
    position: relative;
}
</style>

<?php get_footer(); ?>
