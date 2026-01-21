<?php
/**
 * Front Page Template - TeepTrak Partner Portal
 * Expert Copywriting Implementation - ENGLISH VERSION
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();
?>

<!-- ============================================
     HERO SECTION
     ============================================ -->
<section class="tt-hero">
    <div class="tt-hero-bg">
        <div class="tt-hero-overlay"></div>
        <?php if (get_theme_mod('hero_video_url')) : ?>
            <video class="tt-hero-video" autoplay muted loop playsinline>
                <source src="<?php echo esc_url(get_theme_mod('hero_video_url')); ?>" type="video/mp4">
            </video>
        <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-factory.jpg" alt="" class="tt-hero-image">
        <?php endif; ?>
    </div>

    <div class="tt-container">
        <div class="tt-hero-content">
            <span class="tt-hero-badge"><?php esc_html_e('Partner Program 2026', 'teeptrak-partner'); ?></span>

            <h1 class="tt-hero-title">
                <?php esc_html_e('Join the partner network equipping', 'teeptrak-partner'); ?>
                <span class="tt-text-gradient"><?php esc_html_e('360+ factories across 30 countries', 'teeptrak-partner'); ?></span>
            </h1>

            <p class="tt-hero-subtitle">
                <?php esc_html_e('Become a certified TeepTrak partner and offer your clients Europe\'s #1 OEE monitoring solution. 15-30% commissions, 90-day deal protection, free certification.', 'teeptrak-partner'); ?>
            </p>

            <div class="tt-hero-actions">
                <a href="<?php echo esc_url(teeptrak_get_application_url()); ?>" class="tt-btn tt-btn-primary tt-btn-xl">
                    <?php esc_html_e('Apply now', 'teeptrak-partner'); ?>
                    <?php echo teeptrak_icon('arrow-right', 20); ?>
                </a>
                <a href="#programme" class="tt-btn tt-btn-outline-white tt-btn-xl">
                    <?php esc_html_e('Explore the program', 'teeptrak-partner'); ?>
                </a>
            </div>

            <div class="tt-hero-stats">
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">360+</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('factories equipped', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-hero-stat-divider"></div>
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">30+</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('countries', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-hero-stat-divider"></div>
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">+23%</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('average OEE gain', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-hero-stat-divider"></div>
                <div class="tt-hero-stat">
                    <span class="tt-hero-stat-value">50+</span>
                    <span class="tt-hero-stat-label"><?php esc_html_e('active partners', 'teeptrak-partner'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="tt-scroll-indicator">
        <span><?php esc_html_e('Discover', 'teeptrak-partner'); ?></span>
        <div class="tt-scroll-arrow"></div>
    </div>
</section>

<!-- ============================================
     CLIENT LOGOS SECTION
     ============================================ -->
<section class="tt-logos-section">
    <div class="tt-container">
        <p class="tt-logos-label"><?php esc_html_e('Trusted by our partners\' clients', 'teeptrak-partner'); ?></p>
    </div>
    <div class="tt-logos-slider">
        <div class="tt-logos-track">
            <?php
            $logos = array('stellantis', 'renault', 'alstom', 'airbus', 'hutchinson', 'thales');
            // Duplicate for infinite scroll
            $logos = array_merge($logos, $logos);
            foreach ($logos as $logo) :
            ?>
                <div class="tt-logo-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logos/<?php echo esc_attr($logo); ?>.svg"
                         alt="<?php echo esc_attr(ucfirst($logo)); ?>" loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================
     VALUE PROPOSITION SECTION
     ============================================ -->
<section id="programme" class="tt-section tt-benefits-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('Why join us', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('A program designed for your growth', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('TeepTrak isn\'t just a vendor. We\'re your growth partner in Industry 4.0, with practical tools to help you close faster and better.', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-benefits-grid">
            <!-- Benefit 1: Revenue -->
            <div class="tt-benefit-card tt-animate-fade-up">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #FEE2E2; --icon-color: #eb352b;">
                    <?php echo teeptrak_icon('dollar-sign', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Attractive commissions', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('15-30% on every signed contract. A typical client = €25K, that\'s up to €7,500 for you.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('Up to 30%', 'teeptrak-partner'); ?></span>
            </div>

            <!-- Benefit 2: Protection -->
            <div class="tt-benefit-card tt-animate-fade-up" style="--delay: 0.1s;">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #DBEAFE; --icon-color: #3B82F6;">
                    <?php echo teeptrak_icon('shield', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('90-day protection', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('Your opportunities are protected. Focus on selling, not internal competition.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('Deal protection', 'teeptrak-partner'); ?></span>
            </div>

            <!-- Benefit 3: Certification -->
            <div class="tt-benefit-card tt-animate-fade-up" style="--delay: 0.2s;">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #D1FAE5; --icon-color: #22C55E;">
                    <?php echo teeptrak_icon('graduation-cap', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Free certification', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('Become an OEE expert in 2 weeks. Official badge + sales resources included.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('10-15h training', 'teeptrak-partner'); ?></span>
            </div>

            <!-- Benefit 4: Sales Tools -->
            <div class="tt-benefit-card tt-animate-fade-up" style="--delay: 0.3s;">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #FEF3C7; --icon-color: #F59E0B;">
                    <?php echo teeptrak_icon('trending-up', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Complete sales toolkit', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('ROI calculator, personalized demos, ready-to-use industry case studies.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('Turnkey', 'teeptrak-partner'); ?></span>
            </div>

            <!-- Benefit 5: Support -->
            <div class="tt-benefit-card tt-animate-fade-up" style="--delay: 0.4s;">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #E0E7FF; --icon-color: #6366F1;">
                    <?php echo teeptrak_icon('handshake', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Dedicated support', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('Personal Partner Manager. Pre-sales and technical support included.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('Dedicated manager', 'teeptrak-partner'); ?></span>
            </div>

            <!-- Benefit 6: Co-Marketing -->
            <div class="tt-benefit-card tt-animate-fade-up" style="--delay: 0.5s;">
                <div class="tt-benefit-icon-wrapper" style="--icon-bg: #FCE7F3; --icon-color: #EC4899;">
                    <?php echo teeptrak_icon('users', 28); ?>
                </div>
                <h3 class="tt-benefit-title"><?php esc_html_e('Co-marketing & leads', 'teeptrak-partner'); ?></h3>
                <p class="tt-benefit-description">
                    <?php esc_html_e('Listing on our website, qualified leads, joint webinars, industry events.', 'teeptrak-partner'); ?>
                </p>
                <span class="tt-benefit-highlight"><?php esc_html_e('Shared leads', 'teeptrak-partner'); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     VIDEO SECTION
     ============================================ -->
<section class="tt-section tt-video-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('Discover TeepTrak', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('2 minutes to understand why our clients choose us', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-video-player tt-animate-fade-up">
            <div class="tt-video-poster" id="video-poster">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/video-thumbnail.jpg" alt="TeepTrak Demo">
                <button class="tt-video-play-btn" aria-label="<?php esc_attr_e('Play video', 'teeptrak-partner'); ?>">
                    <?php echo teeptrak_icon('play', 32); ?>
                </button>
                <span class="tt-video-duration">2:34</span>
            </div>
            <div class="tt-video-iframe" id="video-iframe" style="display: none;">
                <!-- YouTube iframe inserted by JS -->
            </div>
        </div>

        <div class="tt-video-features">
            <div class="tt-video-feature">
                <?php echo teeptrak_icon('clock', 20); ?>
                <span><?php esc_html_e('Deployment < 1h per line', 'teeptrak-partner'); ?></span>
            </div>
            <div class="tt-video-feature">
                <?php echo teeptrak_icon('check-circle', 20); ?>
                <span><?php esc_html_e('100% OPC-UA compatible', 'teeptrak-partner'); ?></span>
            </div>
            <div class="tt-video-feature">
                <?php echo teeptrak_icon('trending-up', 20); ?>
                <span><?php esc_html_e('Results in 30 days', 'teeptrak-partner'); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     PARTNER TIERS SECTION
     ============================================ -->
<section id="tiers" class="tt-section tt-tiers-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('Partner Levels', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('Grow with us, unlock more benefits', 'teeptrak-partner'); ?></h2>
            <p class="tt-section-subtitle"><?php esc_html_e('All partners start at Bronze level. Progress automatically based on your results.', 'teeptrak-partner'); ?></p>
        </div>

        <div class="tt-tiers-modern">
            <?php
            $tiers = array(
                'bronze' => array(
                    'name' => 'Bronze',
                    'commission_rate' => 15,
                    'color' => '#CD7F32',
                    'requirements' => __('Basic certification', 'teeptrak-partner'),
                    'featured' => false,
                    'benefits' => array(
                        __('Deal Registration & 90-day Protection', 'teeptrak-partner'),
                        __('Access to online training', 'teeptrak-partner'),
                        __('Sales resources', 'teeptrak-partner'),
                        __('Email support (48h)', 'teeptrak-partner'),
                    )
                ),
                'silver' => array(
                    'name' => 'Silver',
                    'commission_rate' => 20,
                    'color' => '#A8A8A8',
                    'requirements' => __('2+ deals closed, advanced certification', 'teeptrak-partner'),
                    'featured' => false,
                    'benefits' => array(
                        __('Everything in Bronze +', 'teeptrak-partner'),
                        __('Co-marketing materials', 'teeptrak-partner'),
                        __('Priority technical support', 'teeptrak-partner'),
                        __('Quarterly Business Reviews', 'teeptrak-partner'),
                    )
                ),
                'gold' => array(
                    'name' => 'Gold',
                    'commission_rate' => 25,
                    'color' => '#FFD700',
                    'requirements' => __('5+ deals, €100K+ pipeline, full certification', 'teeptrak-partner'),
                    'featured' => true,
                    'benefits' => array(
                        __('Everything in Silver +', 'teeptrak-partner'),
                        __('Dedicated Partner Success Manager', 'teeptrak-partner'),
                        __('Shared qualified leads', 'teeptrak-partner'),
                        __('Joint client presentations', 'teeptrak-partner'),
                        __('Early access to new features', 'teeptrak-partner'),
                    )
                ),
                'platinum' => array(
                    'name' => 'Platinum',
                    'commission_rate' => 30,
                    'color' => '#E5E4E2',
                    'requirements' => __('10+ deals, €250K+ pipeline, strategic alignment', 'teeptrak-partner'),
                    'featured' => false,
                    'benefits' => array(
                        __('Everything in Gold +', 'teeptrak-partner'),
                        __('Strategic planning sessions', 'teeptrak-partner'),
                        __('Sponsorship & event access', 'teeptrak-partner'),
                        __('Custom marketing campaigns', 'teeptrak-partner'),
                        __('Preferred Implementation Partner', 'teeptrak-partner'),
                    )
                )
            );

            foreach ($tiers as $tier_key => $tier) :
            ?>
                <div class="tt-tier-modern <?php echo !empty($tier['featured']) ? 'is-featured' : ''; ?> tt-animate-fade-up" data-tier="<?php echo esc_attr($tier_key); ?>">
                    <?php if (!empty($tier['featured'])) : ?>
                        <span class="tt-tier-badge"><?php esc_html_e('Most Popular', 'teeptrak-partner'); ?></span>
                    <?php endif; ?>

                    <div class="tt-tier-header" style="--tier-color: <?php echo esc_attr($tier['color']); ?>;">
                        <h3 class="tt-tier-name"><?php echo esc_html($tier['name']); ?></h3>
                        <div class="tt-tier-commission">
                            <span class="tt-tier-rate"><?php echo esc_html($tier['commission_rate']); ?>%</span>
                            <span class="tt-tier-label"><?php esc_html_e('commission', 'teeptrak-partner'); ?></span>
                        </div>
                    </div>

                    <div class="tt-tier-requirements">
                        <span class="tt-tier-req-label"><?php esc_html_e('Requirements:', 'teeptrak-partner'); ?></span>
                        <span class="tt-tier-req-text"><?php echo esc_html($tier['requirements']); ?></span>
                    </div>

                    <ul class="tt-tier-benefits">
                        <?php foreach ($tier['benefits'] as $benefit) : ?>
                            <li>
                                <?php echo teeptrak_icon('check', 16); ?>
                                <span><?php echo esc_html($benefit); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tt-tier-action">
                        <?php if ($tier_key === 'bronze') : ?>
                            <a href="<?php echo esc_url(teeptrak_get_application_url()); ?>" class="tt-btn tt-btn-primary tt-btn-block">
                                <?php esc_html_e('Get started', 'teeptrak-partner'); ?>
                                <?php echo teeptrak_icon('arrow-right', 16); ?>
                            </a>
                        <?php elseif ($tier_key === 'platinum') : ?>
                            <a href="mailto:partners@teeptrak.com" class="tt-btn tt-btn-outline tt-btn-block">
                                <?php esc_html_e('Contact us', 'teeptrak-partner'); ?>
                            </a>
                        <?php else : ?>
                            <a href="#programme" class="tt-btn tt-btn-outline tt-btn-block">
                                <?php esc_html_e('Learn more', 'teeptrak-partner'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="tt-tiers-hint">
            <?php echo teeptrak_icon('info', 16); ?>
            <?php esc_html_e('All partners start at Bronze and progress automatically based on their results.', 'teeptrak-partner'); ?>
        </p>
    </div>
</section>

<!-- ============================================
     TESTIMONIALS SECTION
     ============================================ -->
<section class="tt-section tt-testimonials-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('Testimonials', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('What our partners say about us', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-testimonials-grid">
            <!-- Testimonial 1 -->
            <div class="tt-testimonial-card tt-animate-fade-up">
                <div class="tt-testimonial-quote"><?php echo teeptrak_icon('quote', 32); ?></div>
                <p class="tt-testimonial-text">
                    "<?php esc_html_e('TeepTrak enabled us to create a new Industry 4.0 offering for our existing clients. In 8 months, we generated €180K in additional revenue.', 'teeptrak-partner'); ?>"
                </p>
                <div class="tt-testimonial-author">
                    <div class="tt-testimonial-avatar">MD</div>
                    <div class="tt-testimonial-info">
                        <span class="tt-testimonial-name">Marc Dubois</span>
                        <span class="tt-testimonial-role">CEO @ IndustriaConsult</span>
                    </div>
                    <span class="tt-testimonial-tier tt-tier-gold">Gold</span>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="tt-testimonial-card tt-animate-fade-up" style="--delay: 0.15s;">
                <div class="tt-testimonial-quote"><?php echo teeptrak_icon('quote', 32); ?></div>
                <p class="tt-testimonial-text">
                    "<?php esc_html_e('The pre-sales support is exceptional. The TeepTrak team helped us close 3 difficult deals with their technical experts.', 'teeptrak-partner'); ?>"
                </p>
                <div class="tt-testimonial-author">
                    <div class="tt-testimonial-avatar">SM</div>
                    <div class="tt-testimonial-info">
                        <span class="tt-testimonial-name">Sophie Martin</span>
                        <span class="tt-testimonial-role">Sales Director @ TechSI France</span>
                    </div>
                    <span class="tt-testimonial-tier tt-tier-silver">Silver</span>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="tt-testimonial-card tt-animate-fade-up" style="--delay: 0.3s;">
                <div class="tt-testimonial-quote"><?php echo teeptrak_icon('quote', 32); ?></div>
                <p class="tt-testimonial-text">
                    "<?php esc_html_e('We were looking for a complement to our MES solutions. TeepTrak integrates perfectly and our clients see concrete results within 30 days.', 'teeptrak-partner'); ?>"
                </p>
                <div class="tt-testimonial-author">
                    <div class="tt-testimonial-avatar">JL</div>
                    <div class="tt-testimonial-info">
                        <span class="tt-testimonial-name">Jean-Pierre Lefevre</span>
                        <span class="tt-testimonial-role">Partner @ Digital Factory Solutions</span>
                    </div>
                    <span class="tt-testimonial-tier tt-tier-platinum">Platinum</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     PROCESS TIMELINE SECTION
     ============================================ -->
<section class="tt-section tt-process-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('How it works', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('From application to first deal in 4 weeks', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-process-timeline">
            <?php
            $steps = array(
                array(
                    'icon' => 'clipboard',
                    'title' => __('Application', 'teeptrak-partner'),
                    'description' => __('Fill out the form (5 min). Our team reviews your profile within 48h.', 'teeptrak-partner'),
                ),
                array(
                    'icon' => 'handshake',
                    'title' => __('Onboarding', 'teeptrak-partner'),
                    'description' => __('Welcome call + partner portal access. Program and tools presentation.', 'teeptrak-partner'),
                ),
                array(
                    'icon' => 'graduation-cap',
                    'title' => __('Certification', 'teeptrak-partner'),
                    'description' => __('OEE + TeepTrak training (10-15h online). Badge and resources unlocked upon completion.', 'teeptrak-partner'),
                ),
                array(
                    'icon' => 'rocket',
                    'title' => __('First deal', 'teeptrak-partner'),
                    'description' => __('Identify your first opportunities. Our team supports you through to closing.', 'teeptrak-partner'),
                )
            );

            foreach ($steps as $index => $step) :
            ?>
                <div class="tt-process-step tt-animate-fade-up" style="--delay: <?php echo ($index * 0.15); ?>s;">
                    <div class="tt-process-number"><?php echo ($index + 1); ?></div>
                    <div class="tt-process-content">
                        <div class="tt-process-icon">
                            <?php echo teeptrak_icon($step['icon'], 24); ?>
                        </div>
                        <h3 class="tt-process-title"><?php echo esc_html($step['title']); ?></h3>
                        <p class="tt-process-description"><?php echo esc_html($step['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="tt-process-cta tt-text-center tt-animate-fade-up">
            <a href="<?php echo esc_url(teeptrak_get_application_url()); ?>" class="tt-btn tt-btn-primary tt-btn-xl">
                <?php esc_html_e('Start now', 'teeptrak-partner'); ?>
                <?php echo teeptrak_icon('arrow-right', 20); ?>
            </a>
            <p class="tt-process-cta-hint"><?php esc_html_e('First commission in 30 days', 'teeptrak-partner'); ?></p>
        </div>
    </div>
</section>

<!-- ============================================
     TARGET PARTNERS SECTION
     ============================================ -->
<section class="tt-section tt-target-section">
    <div class="tt-container">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('Who is this for?', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('This program is made for you if...', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-target-grid">
            <!-- Systems Integrators -->
            <div class="tt-target-card tt-animate-fade-up">
                <div class="tt-target-icon">
                    <?php echo teeptrak_icon('activity', 32); ?>
                </div>
                <h3 class="tt-target-title"><?php esc_html_e('MES/ERP Integrators', 'teeptrak-partner'); ?></h3>
                <p class="tt-target-description">
                    <?php esc_html_e('You deploy industrial solutions and are looking for a plug & play OEE module to complement your offering.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-target-highlight">
                    <?php echo teeptrak_icon('check', 16); ?>
                    <span><?php esc_html_e('TeepTrak integrates in just a few hours with 100% of OPC-UA protocols.', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <!-- VARs -->
            <div class="tt-target-card tt-animate-fade-up" style="--delay: 0.15s;">
                <div class="tt-target-icon">
                    <?php echo teeptrak_icon('folder', 32); ?>
                </div>
                <h3 class="tt-target-title"><?php esc_html_e('Industrial Resellers (VAR)', 'teeptrak-partner'); ?></h3>
                <p class="tt-target-description">
                    <?php esc_html_e('You sell industrial equipment and want to offer value-added software to increase your margins.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-target-highlight">
                    <?php echo teeptrak_icon('check', 16); ?>
                    <span><?php esc_html_e('Recurring SaaS solution = recurring revenue.', 'teeptrak-partner'); ?></span>
                </div>
            </div>

            <!-- Consultants -->
            <div class="tt-target-card tt-animate-fade-up" style="--delay: 0.3s;">
                <div class="tt-target-icon">
                    <?php echo teeptrak_icon('clipboard', 32); ?>
                </div>
                <h3 class="tt-target-title"><?php esc_html_e('Lean/Six Sigma Consultants', 'teeptrak-partner'); ?></h3>
                <p class="tt-target-description">
                    <?php esc_html_e('You support operational excellence and need reliable data to demonstrate gains.', 'teeptrak-partner'); ?>
                </p>
                <div class="tt-target-highlight">
                    <?php echo teeptrak_icon('check', 16); ?>
                    <span><?php esc_html_e('Real-time dashboards to prove the ROI of your interventions.', 'teeptrak-partner'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     STATS SECTION (Dark)
     ============================================ -->
<section class="tt-section tt-stats-section">
    <div class="tt-container">
        <div class="tt-stats-grid">
            <div class="tt-stats-content">
                <h2 class="tt-stats-title"><?php esc_html_e('Established credibility since 2018', 'teeptrak-partner'); ?></h2>
                <p class="tt-stats-description">
                    <?php esc_html_e('Since 2018, TeepTrak has helped European manufacturers reduce downtime and improve their OEE. Our partners benefit from this established credibility.', 'teeptrak-partner'); ?>
                </p>
            </div>
            <div class="tt-stats-numbers">
                <div class="tt-stat-item">
                    <span class="tt-stat-value">360+</span>
                    <span class="tt-stat-label"><?php esc_html_e('factories equipped', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-stat-item">
                    <span class="tt-stat-value">30+</span>
                    <span class="tt-stat-label"><?php esc_html_e('countries covered', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-stat-item">
                    <span class="tt-stat-value">+23%</span>
                    <span class="tt-stat-label"><?php esc_html_e('average OEE gain', 'teeptrak-partner'); ?></span>
                </div>
                <div class="tt-stat-item">
                    <span class="tt-stat-value">4-8x</span>
                    <span class="tt-stat-label"><?php esc_html_e('ROI in 6 months', 'teeptrak-partner'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     FAQ SECTION
     ============================================ -->
<section class="tt-section tt-faq-section">
    <div class="tt-container tt-container-sm">
        <div class="tt-section-header tt-text-center">
            <span class="tt-section-tag"><?php esc_html_e('FAQ', 'teeptrak-partner'); ?></span>
            <h2 class="tt-section-title"><?php esc_html_e('Frequently asked questions', 'teeptrak-partner'); ?></h2>
        </div>

        <div class="tt-faq-list">
            <?php
            $faqs = array(
                array(
                    'question' => __('How long does it take to get certified?', 'teeptrak-partner'),
                    'answer' => __('Basic certification takes 10-15 hours online, spread out however you prefer. Most partners complete it in 2 weeks.', 'teeptrak-partner'),
                ),
                array(
                    'question' => __('How does deal protection work?', 'teeptrak-partner'),
                    'answer' => __('As soon as you register an opportunity, it\'s protected for 90 days. If another partner or our direct team works the same account, you remain the priority.', 'teeptrak-partner'),
                ),
                array(
                    'question' => __('Is there a minimum sales quota?', 'teeptrak-partner'),
                    'answer' => __('No. We prefer quality over quantity. That said, active partners (1+ deal/quarter) receive more support and leads.', 'teeptrak-partner'),
                ),
                array(
                    'question' => __('How are commissions paid?', 'teeptrak-partner'),
                    'answer' => __('30 days after client payment. Bank transfer or invoice according to your preference.', 'teeptrak-partner'),
                ),
                array(
                    'question' => __('Can I combine this with other partnerships?', 'teeptrak-partner'),
                    'answer' => __('Absolutely. Many of our partners also represent complementary MES, ERP, or automation solutions.', 'teeptrak-partner'),
                )
            );

            foreach ($faqs as $index => $faq) :
            ?>
                <div class="tt-faq-item tt-animate-fade-up" style="--delay: <?php echo ($index * 0.1); ?>s;">
                    <button class="tt-faq-question" aria-expanded="false">
                        <span><?php echo esc_html($faq['question']); ?></span>
                        <span class="tt-faq-icon"><?php echo teeptrak_icon('plus', 20); ?></span>
                    </button>
                    <div class="tt-faq-answer">
                        <p><?php echo esc_html($faq['answer']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================
     FINAL CTA SECTION
     ============================================ -->
<section class="tt-section tt-cta-section">
    <div class="tt-cta-shapes">
        <div class="tt-cta-shape tt-cta-shape-1"></div>
        <div class="tt-cta-shape tt-cta-shape-2"></div>
    </div>

    <div class="tt-container tt-text-center">
        <h2 class="tt-cta-title"><?php esc_html_e('Ready to grow your business with TeepTrak?', 'teeptrak-partner'); ?></h2>
        <p class="tt-cta-subtitle"><?php esc_html_e('Limited spots per region. Apply now and receive a response within 48 hours.', 'teeptrak-partner'); ?></p>

        <a href="<?php echo esc_url(teeptrak_get_application_url()); ?>" class="tt-btn tt-btn-white tt-btn-xl">
            <?php esc_html_e('Become a partner', 'teeptrak-partner'); ?>
            <?php echo teeptrak_icon('arrow-right', 20); ?>
        </a>

        <p class="tt-cta-contact">
            <?php esc_html_e('Questions?', 'teeptrak-partner'); ?>
            <a href="mailto:partners@teeptrak.com">partners@teeptrak.com</a>
        </p>

        <div class="tt-cta-trust">
            <span><?php echo teeptrak_icon('lock', 16); ?></span>
            <span><?php esc_html_e('Selective program', 'teeptrak-partner'); ?></span>
            <span>•</span>
            <span><?php esc_html_e('Free certification', 'teeptrak-partner'); ?></span>
            <span>•</span>
            <span><?php esc_html_e('No volume commitment', 'teeptrak-partner'); ?></span>
        </div>
    </div>
</section>

<?php get_footer(); ?>
