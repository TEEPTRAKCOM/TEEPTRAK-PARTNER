/**
 * TeepTrak Partner Theme 2026 - Main JavaScript
 *
 * @package TeepTrak_Partner_Theme_2026
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        TeepTrak.init();
    });

    // Main TeepTrak object
    window.TeepTrak = {
        init: function() {
            this.initMobileMenu();
            this.initModals();
            this.initForms();
            this.initCategoryFilters();
            this.initSmoothScroll();
            this.initTooltips();
            this.initDropdowns();
            this.initToasts();
        },

        // Mobile Sidebar Toggle
        initMobileMenu: function() {
            var toggle = document.getElementById('mobile-menu-toggle');
            var sidebar = document.querySelector('.tt-sidebar');
            var overlay = document.getElementById('sidebar-overlay');

            if (!toggle || !sidebar) return;

            // Create overlay if doesn't exist
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'sidebar-overlay';
                overlay.className = 'tt-sidebar-overlay';
                document.body.appendChild(overlay);
            }

            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-visible');
                document.body.classList.toggle('sidebar-open');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                document.body.classList.remove('sidebar-open');
            });

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    sidebar.classList.remove('is-open');
                    overlay.classList.remove('is-visible');
                    document.body.classList.remove('sidebar-open');
                }
            });
        },

        // Modal Functionality
        initModals: function() {
            var self = this;

            // Open modal triggers
            document.querySelectorAll('[data-modal]').forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    var modalId = this.getAttribute('data-modal');
                    self.openModal(modalId);
                });
            });

            // Close modal triggers
            document.querySelectorAll('[data-modal-close]').forEach(function(closer) {
                closer.addEventListener('click', function(e) {
                    e.preventDefault();
                    var modal = this.closest('.tt-modal');
                    if (modal) {
                        self.closeModal(modal.id);
                    }
                });
            });

            // Close on backdrop click
            document.querySelectorAll('.tt-modal').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        self.closeModal(modal.id);
                    }
                });
            });

            // Close on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    var openModal = document.querySelector('.tt-modal.is-open');
                    if (openModal) {
                        self.closeModal(openModal.id);
                    }
                }
            });
        },

        openModal: function(modalId) {
            var modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.add('is-open');
            document.body.classList.add('modal-open');

            // Focus first input
            var firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
            if (firstInput) {
                setTimeout(function() {
                    firstInput.focus();
                }, 100);
            }
        },

        closeModal: function(modalId) {
            var modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.remove('is-open');
            document.body.classList.remove('modal-open');

            // Reset form if exists
            var form = modal.querySelector('form');
            if (form) {
                form.reset();
                this.clearFormErrors(form);
            }
        },

        // Form Handling
        initForms: function() {
            var self = this;

            // Deal registration form
            var dealForm = document.getElementById('deal-registration-form');
            if (dealForm) {
                dealForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    self.handleDealSubmit(this);
                });
            }

            // Generic AJAX forms
            document.querySelectorAll('form[data-ajax]').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    self.handleAjaxForm(this);
                });
            });

            // Real-time validation
            document.querySelectorAll('.tt-form-control[required]').forEach(function(input) {
                input.addEventListener('blur', function() {
                    self.validateField(this);
                });

                input.addEventListener('input', function() {
                    if (this.closest('.tt-form-group').classList.contains('has-error')) {
                        self.validateField(this);
                    }
                });
            });
        },

        handleDealSubmit: function(form) {
            var self = this;

            // Validate form
            if (!this.validateForm(form)) {
                return;
            }

            var submitBtn = form.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="tt-spinner"></span> Submitting...';

            var formData = new FormData(form);
            formData.append('action', 'teeptrak_register_deal');
            formData.append('nonce', teeptrakData.nonce);

            fetch(teeptrakData.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    self.showToast('Deal registered successfully!', 'success');
                    self.closeModal('deal-modal');

                    // Reload page to show new deal
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    self.showToast(data.data || 'An error occurred', 'error');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                self.showToast('Network error. Please try again.', 'error');
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        },

        handleAjaxForm: function(form) {
            var self = this;
            var action = form.getAttribute('data-ajax');

            if (!this.validateForm(form)) {
                return;
            }

            var submitBtn = form.querySelector('button[type="submit"]');
            var originalText = submitBtn ? submitBtn.innerHTML : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="tt-spinner"></span> Processing...';
            }

            var formData = new FormData(form);
            formData.append('action', action);
            formData.append('nonce', teeptrakData.nonce);

            fetch(teeptrakData.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    self.showToast(data.data.message || 'Success!', 'success');

                    if (data.data.redirect) {
                        window.location.href = data.data.redirect;
                    } else if (data.data.reload) {
                        window.location.reload();
                    }
                } else {
                    self.showToast(data.data || 'An error occurred', 'error');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                self.showToast('Network error. Please try again.', 'error');
            })
            .finally(function() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        },

        validateForm: function(form) {
            var self = this;
            var isValid = true;

            form.querySelectorAll('[required]').forEach(function(field) {
                if (!self.validateField(field)) {
                    isValid = false;
                }
            });

            // Email validation
            form.querySelectorAll('input[type="email"]').forEach(function(field) {
                if (field.value && !self.isValidEmail(field.value)) {
                    self.setFieldError(field, 'Please enter a valid email address');
                    isValid = false;
                }
            });

            return isValid;
        },

        validateField: function(field) {
            var formGroup = field.closest('.tt-form-group');
            if (!formGroup) return true;

            this.clearFieldError(field);

            if (field.hasAttribute('required') && !field.value.trim()) {
                this.setFieldError(field, 'This field is required');
                return false;
            }

            if (field.type === 'email' && field.value && !this.isValidEmail(field.value)) {
                this.setFieldError(field, 'Please enter a valid email address');
                return false;
            }

            return true;
        },

        setFieldError: function(field, message) {
            var formGroup = field.closest('.tt-form-group');
            if (!formGroup) return;

            formGroup.classList.add('has-error');

            var existingError = formGroup.querySelector('.tt-form-error');
            if (existingError) {
                existingError.textContent = message;
            } else {
                var error = document.createElement('span');
                error.className = 'tt-form-error';
                error.textContent = message;
                formGroup.appendChild(error);
            }
        },

        clearFieldError: function(field) {
            var formGroup = field.closest('.tt-form-group');
            if (!formGroup) return;

            formGroup.classList.remove('has-error');
            var error = formGroup.querySelector('.tt-form-error');
            if (error) {
                error.remove();
            }
        },

        clearFormErrors: function(form) {
            form.querySelectorAll('.has-error').forEach(function(group) {
                group.classList.remove('has-error');
            });
            form.querySelectorAll('.tt-form-error').forEach(function(error) {
                error.remove();
            });
        },

        isValidEmail: function(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },

        // Category Filters (Resources page)
        initCategoryFilters: function() {
            var self = this;
            var filterTabs = document.querySelectorAll('.tt-filter-tab');
            var resourceGrid = document.getElementById('resources-grid');

            if (!filterTabs.length || !resourceGrid) return;

            filterTabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var category = this.getAttribute('data-category');

                    // Update active tab
                    filterTabs.forEach(function(t) {
                        t.classList.remove('is-active');
                    });
                    this.classList.add('is-active');

                    // Filter resources
                    self.filterResources(category);

                    // Update URL without reload
                    var url = new URL(window.location);
                    if (category) {
                        url.searchParams.set('cat', category);
                    } else {
                        url.searchParams.delete('cat');
                    }
                    window.history.pushState({}, '', url);
                });
            });
        },

        filterResources: function(category) {
            var cards = document.querySelectorAll('.tt-resource-card');

            cards.forEach(function(card) {
                if (!category || card.getAttribute('data-category') === category) {
                    card.style.display = '';
                    card.classList.add('tt-fade-in');
                } else {
                    card.style.display = 'none';
                    card.classList.remove('tt-fade-in');
                }
            });
        },

        // Smooth Scroll
        initSmoothScroll: function() {
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    var targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    var target = document.querySelector(targetId);
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

        // Tooltips
        initTooltips: function() {
            document.querySelectorAll('[data-tooltip]').forEach(function(el) {
                var tooltip = document.createElement('div');
                tooltip.className = 'tt-tooltip';
                tooltip.textContent = el.getAttribute('data-tooltip');
                el.style.position = 'relative';
                el.appendChild(tooltip);

                el.addEventListener('mouseenter', function() {
                    tooltip.classList.add('is-visible');
                });

                el.addEventListener('mouseleave', function() {
                    tooltip.classList.remove('is-visible');
                });
            });
        },

        // Dropdowns
        initDropdowns: function() {
            var self = this;

            document.querySelectorAll('.tt-dropdown-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var dropdown = this.closest('.tt-dropdown');
                    dropdown.classList.toggle('is-open');
                });
            });

            // Close on outside click
            document.addEventListener('click', function() {
                document.querySelectorAll('.tt-dropdown.is-open').forEach(function(dropdown) {
                    dropdown.classList.remove('is-open');
                });
            });
        },

        // Toast Notifications
        initToasts: function() {
            // Create toast container if not exists
            if (!document.getElementById('toast-container')) {
                var container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'tt-toast-container';
                document.body.appendChild(container);
            }
        },

        showToast: function(message, type) {
            type = type || 'info';
            var container = document.getElementById('toast-container');
            if (!container) {
                this.initToasts();
                container = document.getElementById('toast-container');
            }

            var toast = document.createElement('div');
            toast.className = 'tt-toast tt-toast-' + type;

            var icons = {
                success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                error: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            toast.innerHTML = '<span class="tt-toast-icon">' + (icons[type] || icons.info) + '</span>' +
                '<span class="tt-toast-message">' + message + '</span>' +
                '<button class="tt-toast-close">&times;</button>';

            container.appendChild(toast);

            // Animate in
            setTimeout(function() {
                toast.classList.add('is-visible');
            }, 10);

            // Close button
            toast.querySelector('.tt-toast-close').addEventListener('click', function() {
                toast.classList.remove('is-visible');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            });

            // Auto remove
            setTimeout(function() {
                if (toast.parentNode) {
                    toast.classList.remove('is-visible');
                    setTimeout(function() {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 5000);
        },

        // Utility: Format currency
        formatCurrency: function(amount, currency) {
            currency = currency || 'EUR';
            return new Intl.NumberFormat('en-EU', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },

        // Utility: Format date
        formatDate: function(dateString) {
            var date = new Date(dateString);
            return date.toLocaleDateString('en-EU', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        // Utility: Days remaining calculation
        daysRemaining: function(expiryDate) {
            var now = new Date();
            var expiry = new Date(expiryDate);
            var diff = expiry - now;
            return Math.ceil(diff / (1000 * 60 * 60 * 24));
        }
    };

})();
