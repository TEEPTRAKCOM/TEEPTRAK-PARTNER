/**
 * TeepTrak Partner Theme V3 - Notifications Module
 *
 * Real-time notifications with polling and push support
 *
 * @package TeepTrak_Partner_Theme_V3
 */

(function() {
    'use strict';

    window.TeepTrak = window.TeepTrak || {};
    TeepTrak.Notifications = {};

    // Configuration
    const config = {
        pollInterval: 30000, // 30 seconds
        maxVisible: 5,
        soundEnabled: true
    };

    let pollTimer = null;
    let unreadCount = 0;

    /**
     * Initialize notifications
     */
    TeepTrak.Notifications.init = function() {
        this.bell = document.querySelector('.tt-notification-bell');
        this.dropdown = document.querySelector('.tt-notification-dropdown');
        this.badge = document.querySelector('.tt-notification-badge');
        this.list = document.querySelector('.tt-notification-list');

        if (!this.bell) return;

        this.bindEvents();
        this.loadNotifications();
        this.startPolling();
        this.initPushNotifications();
    };

    /**
     * Bind event handlers
     */
    TeepTrak.Notifications.bindEvents = function() {
        const self = this;

        // Toggle dropdown
        this.bell.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            self.toggleDropdown();
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!self.dropdown?.contains(e.target) && !self.bell?.contains(e.target)) {
                self.closeDropdown();
            }
        });

        // Mark all as read
        document.querySelector('.tt-mark-all-read')?.addEventListener('click', function(e) {
            e.preventDefault();
            self.markAllRead();
        });

        // View all link
        document.querySelector('.tt-view-all-notifications')?.addEventListener('click', function() {
            self.closeDropdown();
        });
    };

    /**
     * Toggle dropdown
     */
    TeepTrak.Notifications.toggleDropdown = function() {
        if (this.dropdown.classList.contains('tt-show')) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    };

    TeepTrak.Notifications.openDropdown = function() {
        this.dropdown.classList.add('tt-show');
        this.loadNotifications();
    };

    TeepTrak.Notifications.closeDropdown = function() {
        this.dropdown.classList.remove('tt-show');
    };

    /**
     * Load notifications
     */
    TeepTrak.Notifications.loadNotifications = function() {
        const self = this;

        TeepTrak.ajax('teeptrak_get_notifications', {
            limit: config.maxVisible
        }, {
            onSuccess: function(data) {
                self.renderNotifications(data.notifications);
                self.updateBadge(data.unread_count);
            }
        });
    };

    /**
     * Render notifications list
     */
    TeepTrak.Notifications.renderNotifications = function(notifications) {
        if (!this.list) return;

        if (notifications.length === 0) {
            this.list.innerHTML = `
                <div class="tt-notification-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <p>${teeptrak_i18n?.no_notifications || 'No notifications'}</p>
                </div>
            `;
            return;
        }

        this.list.innerHTML = notifications.map(function(n) {
            return `
                <div class="tt-notification-item ${n.is_read ? '' : 'tt-unread'}" data-id="${n.id}">
                    <div class="tt-notification-icon tt-notification-${n.type}">
                        ${TeepTrak.Notifications.getIcon(n.type)}
                    </div>
                    <div class="tt-notification-content">
                        <p class="tt-notification-message">${n.message}</p>
                        <span class="tt-notification-time">${TeepTrak.formatDate(n.created_at, 'relative')}</span>
                    </div>
                    ${n.action_url ? `
                        <a href="${n.action_url}" class="tt-notification-action">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    ` : ''}
                </div>
            `;
        }).join('');

        // Click handlers for unread items
        this.list.querySelectorAll('.tt-notification-item.tt-unread').forEach(function(item) {
            item.addEventListener('click', function() {
                TeepTrak.Notifications.markAsRead(this.dataset.id);
            });
        });
    };

    /**
     * Get notification icon
     */
    TeepTrak.Notifications.getIcon = function(type) {
        const icons = {
            deal: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
            commission: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
            training: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
            tier: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
            system: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
            default: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>'
        };
        return icons[type] || icons.default;
    };

    /**
     * Update badge count
     */
    TeepTrak.Notifications.updateBadge = function(count) {
        count = parseInt(count) || 0;

        if (!this.badge) return;

        if (count > 0) {
            this.badge.textContent = count > 99 ? '99+' : count;
            this.badge.classList.remove('tt-hidden');
        } else {
            this.badge.classList.add('tt-hidden');
        }

        // Update document title
        if (count > 0 && count !== unreadCount) {
            document.title = `(${count}) ${document.title.replace(/^\(\d+\)\s*/, '')}`;
        } else if (count === 0) {
            document.title = document.title.replace(/^\(\d+\)\s*/, '');
        }

        unreadCount = count;
    };

    /**
     * Mark notification as read
     */
    TeepTrak.Notifications.markAsRead = function(id) {
        const self = this;

        TeepTrak.ajax('teeptrak_mark_notification_read', {
            notification_id: id
        }, {
            onSuccess: function() {
                const item = self.list.querySelector(`[data-id="${id}"]`);
                if (item) {
                    item.classList.remove('tt-unread');
                }
                self.updateBadge(unreadCount - 1);
            }
        });
    };

    /**
     * Mark all as read
     */
    TeepTrak.Notifications.markAllRead = function() {
        const self = this;

        TeepTrak.ajax('teeptrak_mark_all_notifications_read', {}, {
            onSuccess: function() {
                self.list.querySelectorAll('.tt-notification-item').forEach(function(item) {
                    item.classList.remove('tt-unread');
                });
                self.updateBadge(0);
            }
        });
    };

    /**
     * Start polling for new notifications
     */
    TeepTrak.Notifications.startPolling = function() {
        const self = this;

        if (pollTimer) {
            clearInterval(pollTimer);
        }

        pollTimer = setInterval(function() {
            self.checkForNew();
        }, config.pollInterval);

        // Stop polling when tab is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(pollTimer);
            } else {
                self.startPolling();
            }
        });
    };

    /**
     * Check for new notifications
     */
    TeepTrak.Notifications.checkForNew = function() {
        const self = this;

        TeepTrak.ajax('teeptrak_check_new_notifications', {
            last_count: unreadCount
        }, {
            onSuccess: function(data) {
                if (data.unread_count > unreadCount) {
                    self.updateBadge(data.unread_count);

                    if (data.new_notifications && data.new_notifications.length > 0) {
                        data.new_notifications.forEach(function(n) {
                            self.showToastNotification(n);
                        });

                        if (config.soundEnabled) {
                            self.playSound();
                        }
                    }
                }
            }
        });
    };

    /**
     * Show toast notification
     */
    TeepTrak.Notifications.showToastNotification = function(notification) {
        TeepTrak.showToast(notification.message, notification.type || 'info', 5000);
    };

    /**
     * Play notification sound
     */
    TeepTrak.Notifications.playSound = function() {
        const audio = new Audio(teeptrak_settings?.notification_sound || '');
        audio.volume = 0.5;
        audio.play().catch(function() {
            // Ignore autoplay errors
        });
    };

    /**
     * Initialize push notifications
     */
    TeepTrak.Notifications.initPushNotifications = function() {
        if (!('Notification' in window) || !('serviceWorker' in navigator)) {
            return;
        }

        const self = this;
        const enableBtn = document.querySelector('.tt-enable-push');

        if (!enableBtn) return;

        // Check current permission
        if (Notification.permission === 'granted') {
            enableBtn.classList.add('tt-hidden');
            this.subscribeToPush();
        } else if (Notification.permission === 'denied') {
            enableBtn.classList.add('tt-hidden');
        }

        enableBtn.addEventListener('click', function() {
            self.requestPushPermission();
        });
    };

    /**
     * Request push notification permission
     */
    TeepTrak.Notifications.requestPushPermission = function() {
        const self = this;

        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                self.subscribeToPush();
                document.querySelector('.tt-enable-push')?.classList.add('tt-hidden');
            }
        });
    };

    /**
     * Subscribe to push notifications
     */
    TeepTrak.Notifications.subscribeToPush = function() {
        if (!teeptrak_settings?.vapid_public_key) return;

        navigator.serviceWorker.ready.then(function(registration) {
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: TeepTrak.Notifications.urlBase64ToUint8Array(teeptrak_settings.vapid_public_key)
            });
        }).then(function(subscription) {
            // Send subscription to server
            TeepTrak.ajax('teeptrak_save_push_subscription', {
                subscription: JSON.stringify(subscription)
            });
        }).catch(function(error) {
            console.error('Push subscription error:', error);
        });
    };

    /**
     * URL base64 to Uint8Array helper
     */
    TeepTrak.Notifications.urlBase64ToUint8Array = function(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    };

    /**
     * Show desktop notification
     */
    TeepTrak.Notifications.showDesktopNotification = function(title, options) {
        if (Notification.permission !== 'granted') return;

        const notification = new Notification(title, {
            icon: teeptrak_settings?.icon_url || '',
            badge: teeptrak_settings?.badge_url || '',
            ...options
        });

        notification.onclick = function() {
            window.focus();
            if (options.url) {
                window.location.href = options.url;
            }
            notification.close();
        };

        setTimeout(function() {
            notification.close();
        }, 5000);
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            TeepTrak.Notifications.init();
        });
    } else {
        TeepTrak.Notifications.init();
    }

})();
