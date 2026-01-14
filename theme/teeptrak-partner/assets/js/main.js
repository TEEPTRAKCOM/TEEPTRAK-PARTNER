/**
 * TeepTrak Partner Portal - Main JavaScript
 * 
 * @package TeepTrak_Partner
 * @version 1.0.0
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        TeepTrak.init();
    });

    // Main namespace
    window.TeepTrak = {
        /**
         * Initialize all components
         */
        init: function() {
            this.initSidebar();
            this.initLanguageSwitcher();
            this.initNotifications();
            this.initForms();
            this.initModals();
            this.initTables();
            this.initCharts();
            this.initSmoothScroll();
        },

        /**
         * Sidebar toggle for mobile
         */
        initSidebar: function() {
            const sidebar = document.getElementById('tt-sidebar');
            const overlay = document.getElementById('tt-sidebar-overlay');
            const menuToggle = document.getElementById('tt-menu-toggle');
            const sidebarClose = document.getElementById('tt-sidebar-close');

            if (!sidebar) return;

            // Open sidebar
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.add('is-open');
                    overlay.classList.add('is-visible');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close sidebar
            const closeSidebar = function() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                document.body.style.overflow = '';
            };

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            });
        },

        /**
         * Language switcher
         */
        initLanguageSwitcher: function() {
            const langButtons = document.querySelectorAll('.tt-lang-btn');

            langButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const lang = this.dataset.lang;
                    
                    // Update active state
                    langButtons.forEach(function(b) {
                        b.classList.remove('is-active');
                    });
                    this.classList.add('is-active');

                    // Redirect to language URL (for WPML/Polylang)
                    if (window.teeptrakData && window.teeptrakData.langUrls && window.teeptrakData.langUrls[lang]) {
                        window.location.href = window.teeptrakData.langUrls[lang];
                    }
                });
            });
        },

        /**
         * Notifications
         */
        initNotifications: function() {
            const notificationBtn = document.getElementById('tt-notifications-btn');
            
            if (!notificationBtn) return;

            // Load notifications count
            this.loadNotificationsCount();

            // Toggle notifications panel
            notificationBtn.addEventListener('click', function() {
                TeepTrak.toggleNotificationsPanel();
            });
        },

        /**
         * Load notifications count
         */
        loadNotificationsCount: function() {
            if (!window.teeptrakData || !window.teeptrakData.isLoggedIn) return;

            fetch(window.teeptrakData.apiUrl + 'notifications/count', {
                headers: {
                    'X-WP-Nonce': window.teeptrakData.nonce
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                const badge = document.querySelector('.tt-notification-badge');
                if (badge && data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                }
            })
            .catch(function(error) {
                console.log('Notifications not available');
            });
        },

        /**
         * Toggle notifications panel
         */
        toggleNotificationsPanel: function() {
            // Implementation for notifications dropdown
            console.log('Toggle notifications panel');
        },

        /**
         * Initialize forms
         */
        initForms: function() {
            // Deal registration form
            const dealForm = document.getElementById('tt-deal-form');
            if (dealForm) {
                dealForm.addEventListener('submit', this.handleDealSubmit.bind(this));
            }

            // Withdrawal form
            const withdrawForm = document.getElementById('tt-withdraw-form');
            if (withdrawForm) {
                withdrawForm.addEventListener('submit', this.handleWithdrawSubmit.bind(this));
            }

            // Partner registration form
            const partnerForm = document.getElementById('tt-partner-registration-form');
            if (partnerForm) {
                partnerForm.addEventListener('submit', this.handlePartnerRegistration.bind(this));
            }
        },

        /**
         * Handle deal form submission
         */
        handleDealSubmit: function(e) {
            e.preventDefault();
            
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = window.teeptrakData.i18n.loading;

            const formData = new FormData(form);
            const data = {};
            formData.forEach(function(value, key) {
                data[key] = value;
            });

            fetch(window.teeptrakData.apiUrl + 'deals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': window.teeptrakData.nonce
                },
                body: JSON.stringify(data)
            })
            .then(function(response) { return response.json(); })
            .then(function(result) {
                if (result.id) {
                    TeepTrak.showNotification('success', result.message || 'Deal registered successfully!');
                    form.reset();
                    
                    // Refresh deals list if on deals page
                    if (typeof TeepTrak.loadDeals === 'function') {
                        TeepTrak.loadDeals();
                    }
                } else {
                    throw new Error(result.message || 'Error registering deal');
                }
            })
            .catch(function(error) {
                TeepTrak.showNotification('error', error.message);
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        },

        /**
         * Handle withdrawal form submission
         */
        handleWithdrawSubmit: function(e) {
            e.preventDefault();
            
            if (!confirm(window.teeptrakData.i18n.confirm)) {
                return;
            }

            const form = e.target;
            const formData = new FormData(form);
            const data = {};
            formData.forEach(function(value, key) {
                data[key] = value;
            });

            fetch(window.teeptrakData.apiUrl + 'commissions/withdraw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': window.teeptrakData.nonce
                },
                body: JSON.stringify(data)
            })
            .then(function(response) { return response.json(); })
            .then(function(result) {
                if (result.success) {
                    TeepTrak.showNotification('success', result.message);
                    window.location.reload();
                } else {
                    throw new Error(result.message);
                }
            })
            .catch(function(error) {
                TeepTrak.showNotification('error', error.message);
            });
        },

        /**
         * Handle partner registration
         */
        handlePartnerRegistration: function(e) {
            e.preventDefault();
            
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = window.teeptrakData.i18n.loading;

            const formData = new FormData(form);

            fetch(window.teeptrakData.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(result) {
                if (result.success) {
                    TeepTrak.showNotification('success', result.data.message);
                    window.location.href = result.data.redirect;
                } else {
                    throw new Error(result.data.message);
                }
            })
            .catch(function(error) {
                TeepTrak.showNotification('error', error.message);
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        },

        /**
         * Initialize modals
         */
        initModals: function() {
            // Modal triggers
            document.querySelectorAll('[data-modal]').forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalId = this.dataset.modal;
                    TeepTrak.openModal(modalId);
                });
            });

            // Modal close buttons
            document.querySelectorAll('.tt-modal-close, .tt-modal-overlay').forEach(function(el) {
                el.addEventListener('click', function() {
                    TeepTrak.closeModal();
                });
            });

            // Close on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    TeepTrak.closeModal();
                }
            });
        },

        /**
         * Open modal
         */
        openModal: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
        },

        /**
         * Close modal
         */
        closeModal: function() {
            const openModal = document.querySelector('.tt-modal.is-open');
            if (openModal) {
                openModal.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        },

        /**
         * Initialize tables
         */
        initTables: function() {
            // Sortable tables
            document.querySelectorAll('.tt-table-sortable th[data-sort]').forEach(function(th) {
                th.addEventListener('click', function() {
                    const table = this.closest('table');
                    const column = this.dataset.sort;
                    const order = this.dataset.order === 'asc' ? 'desc' : 'asc';
                    
                    TeepTrak.sortTable(table, column, order);
                    this.dataset.order = order;
                });
            });
        },

        /**
         * Sort table
         */
        sortTable: function(table, column, order) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort(function(a, b) {
                const aVal = a.querySelector('[data-column="' + column + '"]')?.textContent || '';
                const bVal = b.querySelector('[data-column="' + column + '"]')?.textContent || '';
                
                if (order === 'asc') {
                    return aVal.localeCompare(bVal, undefined, { numeric: true });
                } else {
                    return bVal.localeCompare(aVal, undefined, { numeric: true });
                }
            });
            
            rows.forEach(function(row) {
                tbody.appendChild(row);
            });
        },

        /**
         * Initialize charts
         */
        initCharts: function() {
            // Pipeline chart
            const pipelineChart = document.getElementById('tt-pipeline-chart');
            if (pipelineChart && window.Chart) {
                this.renderPipelineChart(pipelineChart);
            }

            // Commission chart
            const commissionChart = document.getElementById('tt-commission-chart');
            if (commissionChart && window.Chart) {
                this.renderCommissionChart(commissionChart);
            }
        },

        /**
         * Render pipeline chart
         */
        renderPipelineChart: function(canvas) {
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: ['Qualified', 'Proposal', 'Negotiation', 'Closed Won'],
                    datasets: [{
                        data: [30, 25, 20, 25],
                        backgroundColor: ['#3B82F6', '#F59E0B', '#8B5CF6', '#22C55E'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },

        /**
         * Render commission chart
         */
        renderCommissionChart: function(canvas) {
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Commissions (€)',
                        data: [4500, 3200, 5100, 2800, 6200, 4000],
                        backgroundColor: '#EB352B',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        },

        /**
         * Smooth scroll for anchor links
         */
        initSmoothScroll: function() {
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        },

        /**
         * Show notification toast
         */
        showNotification: function(type, message) {
            // Remove existing notifications
            const existing = document.querySelector('.tt-toast');
            if (existing) {
                existing.remove();
            }

            // Create toast
            const toast = document.createElement('div');
            toast.className = 'tt-toast tt-toast-' + type;
            toast.innerHTML = `
                <div class="tt-toast-icon">
                    ${type === 'success' 
                        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>'
                        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
                    }
                </div>
                <span class="tt-toast-message">${message}</span>
                <button class="tt-toast-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;

            document.body.appendChild(toast);

            // Add styles if not present
            if (!document.getElementById('tt-toast-styles')) {
                const styles = document.createElement('style');
                styles.id = 'tt-toast-styles';
                styles.textContent = `
                    .tt-toast {
                        position: fixed;
                        bottom: 24px;
                        right: 24px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 16px 20px;
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                        z-index: 9999;
                        animation: slideIn 0.3s ease;
                    }
                    .tt-toast-success .tt-toast-icon { color: #22C55E; }
                    .tt-toast-error .tt-toast-icon { color: #EF4444; }
                    .tt-toast-close {
                        background: none;
                        border: none;
                        cursor: pointer;
                        color: #9CA3AF;
                        padding: 4px;
                    }
                    .tt-toast-close:hover { color: #4B5563; }
                    @keyframes slideIn {
                        from { transform: translateX(100px); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                `;
                document.head.appendChild(styles);
            }

            // Auto remove after 5 seconds
            setTimeout(function() {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        },

        /**
         * Copy to clipboard
         */
        copyToClipboard: function(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                const originalText = button.textContent;
                button.textContent = '✓ Copied!';
                setTimeout(function() {
                    button.textContent = originalText;
                }, 2000);
            });
        },

        /**
         * Format currency
         */
        formatCurrency: function(amount, currency) {
            const symbols = { EUR: '€', USD: '$', GBP: '£', CNY: '¥' };
            const symbol = symbols[currency] || currency;
            return symbol + new Intl.NumberFormat().format(amount);
        },

        /**
         * API call helper
         */
        api: function(endpoint, method, data) {
            const options = {
                method: method || 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': window.teeptrakData.nonce
                }
            };

            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }

            return fetch(window.teeptrakData.apiUrl + endpoint, options)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('API Error: ' + response.statusText);
                    }
                    return response.json();
                });
        }
    };

})();
