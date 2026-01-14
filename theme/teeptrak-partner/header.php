<?php
/**
 * Theme Header
 *
 * @package TeepTrak_Partner
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_portal = teeptrak_is_portal_page();
$current_lang = teeptrak_get_current_language();
$languages = teeptrak_get_languages();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo TEEPTRAK_ASSETS; ?>/images/favicon.png">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class($is_portal ? 'tt-portal-page' : 'tt-landing-page'); ?>>
<?php wp_body_open(); ?>

<?php if ($is_portal && is_user_logged_in()) : ?>
    <?php 
    // Get partner data
    $partner = teeptrak_get_current_partner_data();
    $tier_config = teeptrak_get_tier_config($partner['tier'] ?? 'bronze');
    ?>
    
    <!-- Portal Layout -->
    <div class="tt-portal" id="tt-portal">
        <!-- Sidebar Overlay -->
        <div class="tt-sidebar-overlay" id="tt-sidebar-overlay"></div>
        
        <!-- Sidebar -->
        <aside class="tt-portal-sidebar" id="tt-sidebar">
            <!-- Logo -->
            <div class="tt-sidebar-logo">
                <a href="<?php echo home_url('/'); ?>" class="tt-logo">
                    <span class="tt-logo-icon">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="6" fill="#EB352B"/>
                            <path d="M10 12H22V14H10V12ZM10 16H22V18H10V16ZM10 20H18V22H10V20Z" fill="white"/>
                        </svg>
                    </span>
                    <span class="tt-logo-text">
                        <span style="color: white;">teep</span><span style="color: #EB352B;">trak</span>
                    </span>
                </a>
                <button class="tt-sidebar-close lg:tt-hidden" id="tt-sidebar-close" aria-label="<?php esc_attr_e('Close menu', 'teeptrak-partner'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="tt-sidebar-nav">
                <?php
                $nav_items = array(
                    'dashboard' => array(
                        'label' => __('Dashboard', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>',
                    ),
                    'academy' => array(
                        'label' => __('Academy Access', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
                    ),
                    'deals' => array(
                        'label' => __('Deal Registration', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                    ),
                    'training' => array(
                        'label' => __('Training', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
                    ),
                    'resources' => array(
                        'label' => __('Resources', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>',
                    ),
                    'commissions' => array(
                        'label' => __('Commissions', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                    ),
                    'quiz' => array(
                        'label' => __('Certification Quiz', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>',
                    ),
                    'agreement' => array(
                        'label' => __('Agreement', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M12 18v-6"></path><path d="M9 15l3 3 3-3"></path></svg>',
                    ),
                    'schedule' => array(
                        'label' => __('Schedule Call', 'teeptrak-partner'),
                        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                    ),
                );
                
                $current_page = get_post_field('post_name', get_the_ID());
                
                foreach ($nav_items as $slug => $item) :
                    $is_active = ($current_page === $slug);
                    $url = home_url('/' . $slug . '/');
                ?>
                    <a href="<?php echo esc_url($url); ?>" 
                       class="tt-nav-item <?php echo $is_active ? 'is-active' : ''; ?>">
                        <?php echo $item['icon']; ?>
                        <span><?php echo esc_html($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
            
            <!-- Tier Badge -->
            <div class="tt-sidebar-tier">
                <div class="tt-tier-badge tt-tier-badge-<?php echo esc_attr($partner['tier'] ?? 'bronze'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <div>
                        <div class="tt-tier-name"><?php echo esc_html($tier_config['name']); ?></div>
                        <div class="tt-tier-rate"><?php echo esc_html($tier_config['commission_rate']); ?>% <?php _e('Commission', 'teeptrak-partner'); ?></div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="tt-portal-main">
            <!-- Header -->
            <header class="tt-portal-header">
                <div class="tt-header-inner tt-flex tt-items-center tt-justify-between tt-h-full tt-p-4">
                    <!-- Mobile Menu Toggle -->
                    <button class="tt-menu-toggle lg:tt-hidden" id="tt-menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'teeptrak-partner'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    
                    <!-- Right Side -->
                    <div class="tt-flex tt-items-center tt-gap-4 tt-ml-auto">
                        <!-- Language Switcher -->
                        <div class="tt-lang-switcher">
                            <?php foreach ($languages as $lang_code => $lang) : ?>
                                <button class="tt-lang-btn <?php echo ($lang_code === $current_lang) ? 'is-active' : ''; ?>"
                                        data-lang="<?php echo esc_attr($lang_code); ?>">
                                    <?php echo esc_html($lang['code'] ?? strtoupper($lang_code)); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Notifications -->
                        <button class="tt-notification-btn" id="tt-notifications-btn" aria-label="<?php esc_attr_e('Notifications', 'teeptrak-partner'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="tt-notification-badge"></span>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="tt-user-menu">
                            <div class="tt-user-avatar">
                                <?php echo substr($partner['name'] ?? 'U', 0, 1); ?>
                            </div>
                            <div class="tt-user-info md:tt-block tt-hidden">
                                <div class="tt-user-name"><?php echo esc_html($partner['name'] ?? ''); ?></div>
                                <div class="tt-user-company"><?php echo esc_html($partner['company'] ?? ''); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="tt-portal-content">

<?php else : ?>
    <!-- Landing Page Header -->
    <nav class="tt-landing-nav">
        <div class="tt-container">
            <div class="tt-nav-inner tt-flex tt-items-center tt-justify-between" style="height: 64px;">
                <!-- Logo -->
                <a href="<?php echo home_url('/'); ?>" class="tt-logo tt-flex tt-items-center tt-gap-2">
                    <span class="tt-logo-icon">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="6" fill="#EB352B"/>
                            <path d="M10 12H22V14H10V12ZM10 16H22V18H10V16ZM10 20H18V22H10V20Z" fill="white"/>
                        </svg>
                    </span>
                    <span class="tt-logo-text">
                        <span style="color: #232120;">teep</span><span style="color: #EB352B;">trak</span>
                    </span>
                    <span class="tt-logo-divider">| Partners</span>
                </a>
                
                <!-- Desktop Navigation -->
                <div class="tt-nav-links md:tt-flex tt-hidden tt-items-center tt-gap-8">
                    <a href="#benefits" class="tt-nav-link"><?php _e('Benefits', 'teeptrak-partner'); ?></a>
                    <a href="#tiers" class="tt-nav-link"><?php _e('Partner Tiers', 'teeptrak-partner'); ?></a>
                    <a href="#process" class="tt-nav-link"><?php _e('How It Works', 'teeptrak-partner'); ?></a>
                </div>
                
                <!-- Right Side -->
                <div class="tt-flex tt-items-center tt-gap-3">
                    <!-- Language Switcher -->
                    <div class="tt-lang-switcher">
                        <?php foreach ($languages as $lang_code => $lang) : ?>
                            <button class="tt-lang-btn <?php echo ($lang_code === $current_lang) ? 'is-active' : ''; ?>"
                                    data-lang="<?php echo esc_attr($lang_code); ?>">
                                <?php echo esc_html($lang['code'] ?? strtoupper($lang_code)); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo home_url('/dashboard/'); ?>" class="tt-nav-link md:tt-block tt-hidden">
                            <?php _e('Dashboard', 'teeptrak-partner'); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo wp_login_url(home_url('/dashboard/')); ?>" class="tt-nav-link md:tt-block tt-hidden">
                            <?php _e('Partner Login', 'teeptrak-partner'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo home_url('/become-partner/'); ?>" class="tt-btn tt-btn-primary">
                        <?php _e('Become a Partner', 'teeptrak-partner'); ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="tt-landing-main">
<?php endif; ?>
