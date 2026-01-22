<?php
/**
 * PWA Support for TeepTrak Partner Theme V3
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add PWA meta tags to head
 */
function teeptrak_pwa_meta_tags() {
    $theme_color = get_theme_mod('teeptrak_primary_color', '#E63946');
    $brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
    ?>
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="<?php echo esc_attr($theme_color); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr($brand_name); ?> Partner">
    <link rel="manifest" href="<?php echo esc_url(TEEPTRAK_URI . '/manifest.json'); ?>">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/favicon-16x16.png'); ?>">

    <!-- Splash screens for iOS -->
    <link rel="apple-touch-startup-image" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/splash-640x1136.png'); ?>" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/splash-750x1334.png'); ?>" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="<?php echo esc_url(TEEPTRAK_ASSETS . '/icons/splash-1242x2208.png'); ?>" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
    <?php
}
add_action('wp_head', 'teeptrak_pwa_meta_tags');

/**
 * Register service worker
 */
function teeptrak_register_service_worker() {
    if (!teeptrak_is_portal_page()) {
        return;
    }
    ?>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo esc_url(TEEPTRAK_URI . '/service-worker.js'); ?>')
                    .then(function(registration) {
                        console.log('ServiceWorker registered:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed:', error);
                    });
            });
        }
    </script>
    <?php
}
add_action('wp_footer', 'teeptrak_register_service_worker');

/**
 * Add install prompt for PWA
 */
function teeptrak_pwa_install_prompt() {
    if (!teeptrak_is_portal_page()) {
        return;
    }
    ?>
    <div id="pwa-install-prompt" class="tt-pwa-install-prompt tt-hidden">
        <div class="tt-pwa-install-content">
            <div class="tt-pwa-install-icon">
                <?php echo teeptrak_icon('download', 24); ?>
            </div>
            <div class="tt-pwa-install-text">
                <strong><?php esc_html_e('Install App', 'teeptrak-partner'); ?></strong>
                <span><?php esc_html_e('Add to your home screen for quick access', 'teeptrak-partner'); ?></span>
            </div>
            <button class="tt-btn tt-btn-primary tt-btn-sm" id="pwa-install-btn">
                <?php esc_html_e('Install', 'teeptrak-partner'); ?>
            </button>
            <button class="tt-btn tt-btn-ghost tt-btn-sm" id="pwa-install-dismiss">
                <?php echo teeptrak_icon('x', 16); ?>
            </button>
        </div>
    </div>

    <script>
        let deferredPrompt;
        const installPrompt = document.getElementById('pwa-install-prompt');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-install-dismiss');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Check if already dismissed
            if (localStorage.getItem('pwa-install-dismissed')) {
                return;
            }

            installPrompt.classList.remove('tt-hidden');
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;

                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    console.log('PWA installed');
                }

                deferredPrompt = null;
                installPrompt.classList.add('tt-hidden');
            });
        }

        if (dismissBtn) {
            dismissBtn.addEventListener('click', () => {
                installPrompt.classList.add('tt-hidden');
                localStorage.setItem('pwa-install-dismissed', 'true');
            });
        }

        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            installPrompt.classList.add('tt-hidden');
            deferredPrompt = null;
        });
    </script>

    <style>
        .tt-pwa-install-prompt {
            position: fixed;
            bottom: var(--tt-space-6);
            left: 50%;
            transform: translateX(-50%);
            z-index: var(--tt-z-tooltip);
            animation: tt-slide-up 0.3s ease;
        }

        .tt-pwa-install-content {
            display: flex;
            align-items: center;
            gap: var(--tt-space-3);
            padding: var(--tt-space-3) var(--tt-space-4);
            background-color: var(--tt-white);
            border-radius: var(--tt-radius-xl);
            box-shadow: var(--tt-shadow-lg);
        }

        .tt-pwa-install-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--tt-radius-lg);
            background-color: var(--tt-red-100);
            color: var(--tt-red);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tt-pwa-install-text {
            display: flex;
            flex-direction: column;
        }

        .tt-pwa-install-text strong {
            font-size: var(--tt-font-size-sm);
            color: var(--tt-gray-900);
        }

        .tt-pwa-install-text span {
            font-size: var(--tt-font-size-xs);
            color: var(--tt-gray-500);
        }

        @media (max-width: 640px) {
            .tt-pwa-install-prompt {
                left: var(--tt-space-4);
                right: var(--tt-space-4);
                transform: none;
            }
        }
    </style>
    <?php
}
add_action('wp_footer', 'teeptrak_pwa_install_prompt');

/**
 * Generate dynamic manifest.json
 */
function teeptrak_pwa_manifest() {
    if ($_SERVER['REQUEST_URI'] !== '/manifest.json') {
        return;
    }

    $brand_name = get_theme_mod('teeptrak_brand_name', 'TeepTrak');
    $theme_color = get_theme_mod('teeptrak_primary_color', '#E63946');

    $manifest = array(
        'name'             => $brand_name . ' Partner Portal',
        'short_name'       => $brand_name,
        'description'      => __('Partner portal for managing deals, training, and commissions', 'teeptrak-partner'),
        'start_url'        => home_url('/dashboard/'),
        'scope'            => home_url('/'),
        'display'          => 'standalone',
        'orientation'      => 'portrait-primary',
        'theme_color'      => $theme_color,
        'background_color' => '#FFFFFF',
        'icons'            => array(
            array(
                'src'     => TEEPTRAK_ASSETS . '/icons/icon-72x72.png',
                'sizes'   => '72x72',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ),
            array(
                'src'     => TEEPTRAK_ASSETS . '/icons/icon-96x96.png',
                'sizes'   => '96x96',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ),
            array(
                'src'     => TEEPTRAK_ASSETS . '/icons/icon-128x128.png',
                'sizes'   => '128x128',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ),
            array(
                'src'     => TEEPTRAK_ASSETS . '/icons/icon-192x192.png',
                'sizes'   => '192x192',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ),
            array(
                'src'     => TEEPTRAK_ASSETS . '/icons/icon-512x512.png',
                'sizes'   => '512x512',
                'type'    => 'image/png',
                'purpose' => 'any maskable',
            ),
        ),
        'categories'       => array('business', 'productivity'),
        'shortcuts'        => array(
            array(
                'name'  => __('Dashboard', 'teeptrak-partner'),
                'url'   => home_url('/dashboard/'),
                'icons' => array(
                    array(
                        'src'   => TEEPTRAK_ASSETS . '/icons/shortcut-dashboard.png',
                        'sizes' => '96x96',
                    ),
                ),
            ),
            array(
                'name'  => __('Register Deal', 'teeptrak-partner'),
                'url'   => home_url('/deals/'),
                'icons' => array(
                    array(
                        'src'   => TEEPTRAK_ASSETS . '/icons/shortcut-deals.png',
                        'sizes' => '96x96',
                    ),
                ),
            ),
            array(
                'name'  => __('Commissions', 'teeptrak-partner'),
                'url'   => home_url('/commissions/'),
                'icons' => array(
                    array(
                        'src'   => TEEPTRAK_ASSETS . '/icons/shortcut-commissions.png',
                        'sizes' => '96x96',
                    ),
                ),
            ),
        ),
    );

    header('Content-Type: application/json');
    echo wp_json_encode($manifest);
    exit;
}
add_action('template_redirect', 'teeptrak_pwa_manifest');

/**
 * Add offline page rewrite rule
 */
function teeptrak_pwa_rewrite_rules() {
    add_rewrite_rule('^offline/?$', 'index.php?teeptrak_offline=1', 'top');
}
add_action('init', 'teeptrak_pwa_rewrite_rules');

/**
 * Add offline query var
 */
function teeptrak_pwa_query_vars($vars) {
    $vars[] = 'teeptrak_offline';
    return $vars;
}
add_filter('query_vars', 'teeptrak_pwa_query_vars');

/**
 * Handle offline page
 */
function teeptrak_pwa_offline_template($template) {
    if (get_query_var('teeptrak_offline')) {
        return TEEPTRAK_DIR . '/template-parts/offline.php';
    }
    return $template;
}
add_filter('template_include', 'teeptrak_pwa_offline_template');
