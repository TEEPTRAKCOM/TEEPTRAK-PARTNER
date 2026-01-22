/**
 * TeepTrak Partner Theme V3 - Main JavaScript
 *
 * @package TeepTrak_Partner_Theme_V3
 */

(function() {
    'use strict';

    // Global namespace
    window.TeepTrak = window.TeepTrak || {};

    /**
     * Initialize all components
     */
    TeepTrak.init = function() {
        TeepTrak.initMobileMenu();
        TeepTrak.initDropdowns();
        TeepTrak.initModals();
        TeepTrak.initTabs();
        TeepTrak.initTooltips();
        TeepTrak.initFormValidation();
        TeepTrak.initLanguageSwitcher();
        TeepTrak.initSearch();
        TeepTrak.initCopyToClipboard();
        TeepTrak.initDatePickers();
    };

    /**
     * Mobile menu toggle
     */
    TeepTrak.initMobileMenu = function() {
        const menuToggle = document.querySelector('.tt-mobile-menu-toggle');
        const sidebar = document.querySelector('.tt-sidebar');
        const overlay = document.querySelector('.tt-sidebar-overlay');

        if (!menuToggle || !sidebar) return;

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('tt-sidebar-open');
            document.body.classList.toggle('tt-menu-open');
        });

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('tt-sidebar-open');
                document.body.classList.remove('tt-menu-open');
            });
        }

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('tt-sidebar-open')) {
                sidebar.classList.remove('tt-sidebar-open');
                document.body.classList.remove('tt-menu-open');
            }
        });
    };

    /**
     * Dropdown menus
     */
    TeepTrak.initDropdowns = function() {
        const dropdowns = document.querySelectorAll('.tt-dropdown');

        dropdowns.forEach(function(dropdown) {
            const toggle = dropdown.querySelector('.tt-dropdown-toggle');
            const menu = dropdown.querySelector('.tt-dropdown-menu');

            if (!toggle || !menu) return;

            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close other dropdowns
                document.querySelectorAll('.tt-dropdown-menu.tt-show').forEach(function(m) {
                    if (m !== menu) m.classList.remove('tt-show');
                });

                menu.classList.toggle('tt-show');
            });
        });

        // Close on outside click
        document.addEventListener('click', function() {
            document.querySelectorAll('.tt-dropdown-menu.tt-show').forEach(function(menu) {
                menu.classList.remove('tt-show');
            });
        });
    };

    /**
     * Modal dialogs
     */
    TeepTrak.initModals = function() {
        // Open modal
        document.querySelectorAll('[data-modal-open]').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-modal-open');
                TeepTrak.openModal(modalId);
            });
        });

        // Close modal
        document.querySelectorAll('[data-modal-close]').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                TeepTrak.closeModal();
            });
        });

        // Close on overlay click
        document.querySelectorAll('.tt-modal').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    TeepTrak.closeModal();
                }
            });
        });

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                TeepTrak.closeModal();
            }
        });
    };

    TeepTrak.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.add('tt-show');
        document.body.classList.add('tt-modal-open');

        // Focus first input
        const firstInput = modal.querySelector('input, textarea, select');
        if (firstInput) {
            setTimeout(function() {
                firstInput.focus();
            }, 100);
        }
    };

    TeepTrak.closeModal = function() {
        document.querySelectorAll('.tt-modal.tt-show').forEach(function(modal) {
            modal.classList.remove('tt-show');
        });
        document.body.classList.remove('tt-modal-open');
    };

    /**
     * Tabs
     */
    TeepTrak.initTabs = function() {
        document.querySelectorAll('.tt-tabs').forEach(function(tabContainer) {
            const tabs = tabContainer.querySelectorAll('.tt-tab');
            const panels = tabContainer.closest('.tt-tab-container')?.querySelectorAll('.tt-tab-panel') ||
                          document.querySelectorAll('[data-tab-panel]');

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-tab');

                    // Update tabs
                    tabs.forEach(function(t) {
                        t.classList.remove('tt-active');
                    });
                    this.classList.add('tt-active');

                    // Update panels
                    panels.forEach(function(panel) {
                        if (panel.id === targetId || panel.getAttribute('data-tab-panel') === targetId) {
                            panel.classList.add('tt-active');
                        } else {
                            panel.classList.remove('tt-active');
                        }
                    });
                });
            });
        });
    };

    /**
     * Tooltips
     */
    TeepTrak.initTooltips = function() {
        document.querySelectorAll('[data-tooltip]').forEach(function(element) {
            element.addEventListener('mouseenter', function() {
                const text = this.getAttribute('data-tooltip');
                const position = this.getAttribute('data-tooltip-position') || 'top';

                const tooltip = document.createElement('div');
                tooltip.className = 'tt-tooltip tt-tooltip-' + position;
                tooltip.textContent = text;
                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();

                let top, left;
                switch (position) {
                    case 'top':
                        top = rect.top - tooltipRect.height - 8;
                        left = rect.left + (rect.width - tooltipRect.width) / 2;
                        break;
                    case 'bottom':
                        top = rect.bottom + 8;
                        left = rect.left + (rect.width - tooltipRect.width) / 2;
                        break;
                    case 'left':
                        top = rect.top + (rect.height - tooltipRect.height) / 2;
                        left = rect.left - tooltipRect.width - 8;
                        break;
                    case 'right':
                        top = rect.top + (rect.height - tooltipRect.height) / 2;
                        left = rect.right + 8;
                        break;
                }

                tooltip.style.top = top + window.scrollY + 'px';
                tooltip.style.left = left + window.scrollX + 'px';
                tooltip.classList.add('tt-show');

                this._tooltip = tooltip;
            });

            element.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this._tooltip = null;
                }
            });
        });
    };

    /**
     * Form validation
     */
    TeepTrak.initFormValidation = function() {
        document.querySelectorAll('form[data-validate]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Remove previous errors
                form.querySelectorAll('.tt-error-message').forEach(function(msg) {
                    msg.remove();
                });
                form.querySelectorAll('.tt-input-error').forEach(function(input) {
                    input.classList.remove('tt-input-error');
                });

                // Validate required fields
                form.querySelectorAll('[required]').forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        TeepTrak.showFieldError(field, teeptrak_i18n?.required || 'This field is required');
                    }
                });

                // Validate email fields
                form.querySelectorAll('[type="email"]').forEach(function(field) {
                    if (field.value && !TeepTrak.isValidEmail(field.value)) {
                        isValid = false;
                        TeepTrak.showFieldError(field, teeptrak_i18n?.invalid_email || 'Please enter a valid email');
                    }
                });

                // Validate phone fields
                form.querySelectorAll('[data-validate-phone]').forEach(function(field) {
                    if (field.value && !TeepTrak.isValidPhone(field.value)) {
                        isValid = false;
                        TeepTrak.showFieldError(field, teeptrak_i18n?.invalid_phone || 'Please enter a valid phone number');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    };

    TeepTrak.showFieldError = function(field, message) {
        field.classList.add('tt-input-error');
        const error = document.createElement('div');
        error.className = 'tt-error-message';
        error.textContent = message;
        field.parentNode.appendChild(error);
    };

    TeepTrak.isValidEmail = function(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    };

    TeepTrak.isValidPhone = function(phone) {
        return /^[\d\s\-\+\(\)]{8,20}$/.test(phone);
    };

    /**
     * Language switcher
     */
    TeepTrak.initLanguageSwitcher = function() {
        document.querySelectorAll('.tt-language-option').forEach(function(option) {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const locale = this.getAttribute('data-locale');

                // Update via AJAX
                if (typeof teeptrak_ajax !== 'undefined') {
                    fetch(teeptrak_ajax.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'teeptrak_set_locale',
                            locale: locale,
                            nonce: teeptrak_ajax.nonce
                        })
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        });
    };

    /**
     * Search functionality
     */
    TeepTrak.initSearch = function() {
        const searchInput = document.querySelector('.tt-search-input');
        const searchResults = document.querySelector('.tt-search-results');

        if (!searchInput || !searchResults) return;

        let debounceTimer;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(debounceTimer);

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.remove('tt-show');
                return;
            }

            debounceTimer = setTimeout(function() {
                TeepTrak.performSearch(query, searchResults);
            }, 300);
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('tt-show');
            }
        });
    };

    TeepTrak.performSearch = function(query, container) {
        if (typeof teeptrak_ajax === 'undefined') return;

        fetch(teeptrak_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'teeptrak_search',
                query: query,
                nonce: teeptrak_ajax.nonce
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success && data.data.length > 0) {
                container.innerHTML = data.data.map(function(item) {
                    return '<a href="' + item.url + '" class="tt-search-result-item">' +
                           '<span class="tt-search-result-type">' + item.type + '</span>' +
                           '<span class="tt-search-result-title">' + item.title + '</span>' +
                           '</a>';
                }).join('');
                container.classList.add('tt-show');
            } else {
                container.innerHTML = '<div class="tt-search-no-results">' +
                    (teeptrak_i18n?.no_results || 'No results found') + '</div>';
                container.classList.add('tt-show');
            }
        });
    };

    /**
     * Copy to clipboard
     */
    TeepTrak.initCopyToClipboard = function() {
        document.querySelectorAll('[data-copy]').forEach(function(button) {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-copy');
                navigator.clipboard.writeText(text).then(function() {
                    TeepTrak.showToast(teeptrak_i18n?.copied || 'Copied to clipboard', 'success');
                });
            });
        });
    };

    /**
     * Date pickers
     */
    TeepTrak.initDatePickers = function() {
        document.querySelectorAll('.tt-datepicker').forEach(function(input) {
            // Use native date picker or flatpickr if available
            if (typeof flatpickr !== 'undefined') {
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
            } else {
                input.type = 'date';
            }
        });
    };

    /**
     * Toast notifications
     */
    TeepTrak.showToast = function(message, type, duration) {
        type = type || 'info';
        duration = duration || 3000;

        const toast = document.createElement('div');
        toast.className = 'tt-toast tt-toast-' + type;
        toast.innerHTML = '<span class="tt-toast-message">' + message + '</span>' +
                         '<button class="tt-toast-close">&times;</button>';

        let container = document.querySelector('.tt-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'tt-toast-container';
            document.body.appendChild(container);
        }

        container.appendChild(toast);

        // Animate in
        setTimeout(function() {
            toast.classList.add('tt-show');
        }, 10);

        // Close button
        toast.querySelector('.tt-toast-close').addEventListener('click', function() {
            TeepTrak.closeToast(toast);
        });

        // Auto close
        setTimeout(function() {
            TeepTrak.closeToast(toast);
        }, duration);
    };

    TeepTrak.closeToast = function(toast) {
        toast.classList.remove('tt-show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    };

    /**
     * AJAX helper
     */
    TeepTrak.ajax = function(action, data, options) {
        options = options || {};

        const formData = new URLSearchParams();
        formData.append('action', action);
        formData.append('nonce', teeptrak_ajax.nonce);

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key]);
            }
        }

        return fetch(teeptrak_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(result) {
            if (result.success) {
                if (options.onSuccess) options.onSuccess(result.data);
            } else {
                if (options.onError) options.onError(result.data);
                else TeepTrak.showToast(result.data || 'An error occurred', 'error');
            }
            return result;
        })
        .catch(function(error) {
            if (options.onError) options.onError(error);
            else TeepTrak.showToast('Network error', 'error');
        });
    };

    /**
     * Format currency
     */
    TeepTrak.formatCurrency = function(amount, currency) {
        currency = currency || teeptrak_settings?.currency || 'EUR';
        return new Intl.NumberFormat(teeptrak_settings?.locale || 'en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    };

    /**
     * Format date
     */
    TeepTrak.formatDate = function(date, format) {
        format = format || 'short';
        const d = new Date(date);

        if (format === 'relative') {
            return TeepTrak.relativeTime(d);
        }

        return new Intl.DateTimeFormat(teeptrak_settings?.locale || 'en-US', {
            dateStyle: format
        }).format(d);
    };

    TeepTrak.relativeTime = function(date) {
        const now = new Date();
        const diff = now - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 7) {
            return TeepTrak.formatDate(date, 'short');
        } else if (days > 0) {
            return days + ' ' + (teeptrak_i18n?.days_ago || 'days ago');
        } else if (hours > 0) {
            return hours + ' ' + (teeptrak_i18n?.hours_ago || 'hours ago');
        } else if (minutes > 0) {
            return minutes + ' ' + (teeptrak_i18n?.minutes_ago || 'minutes ago');
        } else {
            return teeptrak_i18n?.just_now || 'Just now';
        }
    };

    /**
     * Loading state
     */
    TeepTrak.setLoading = function(element, loading) {
        if (loading) {
            element.classList.add('tt-loading');
            element.disabled = true;
        } else {
            element.classList.remove('tt-loading');
            element.disabled = false;
        }
    };

    /**
     * Confirm dialog
     */
    TeepTrak.confirm = function(message, options) {
        options = options || {};

        return new Promise(function(resolve) {
            const modal = document.createElement('div');
            modal.className = 'tt-modal tt-confirm-modal';
            modal.innerHTML = '<div class="tt-modal-content">' +
                '<div class="tt-modal-body">' +
                '<p>' + message + '</p>' +
                '</div>' +
                '<div class="tt-modal-footer">' +
                '<button class="tt-btn tt-btn-secondary" data-action="cancel">' +
                    (options.cancelText || teeptrak_i18n?.cancel || 'Cancel') + '</button>' +
                '<button class="tt-btn tt-btn-' + (options.type || 'primary') + '" data-action="confirm">' +
                    (options.confirmText || teeptrak_i18n?.confirm || 'Confirm') + '</button>' +
                '</div>' +
                '</div>';

            document.body.appendChild(modal);
            setTimeout(function() { modal.classList.add('tt-show'); }, 10);

            modal.querySelector('[data-action="confirm"]').addEventListener('click', function() {
                modal.remove();
                resolve(true);
            });

            modal.querySelector('[data-action="cancel"]').addEventListener('click', function() {
                modal.remove();
                resolve(false);
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                    resolve(false);
                }
            });
        });
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', TeepTrak.init);
    } else {
        TeepTrak.init();
    }

})();
