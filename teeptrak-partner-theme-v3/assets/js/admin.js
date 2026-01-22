/**
 * TeepTrak Partner Theme V3 - Admin Module
 *
 * Admin dashboard and management functionality
 *
 * @package TeepTrak_Partner_Theme_V3
 */

(function() {
    'use strict';

    window.TeepTrakAdmin = window.TeepTrakAdmin || {};

    /**
     * Initialize admin features
     */
    TeepTrakAdmin.init = function() {
        this.initDataTables();
        this.initPartnerManagement();
        this.initDealManagement();
        this.initCommissionManagement();
        this.initBulkActions();
        this.initExports();
        this.initSettings();
        this.initOdooSync();
    };

    /**
     * Initialize DataTables
     */
    TeepTrakAdmin.initDataTables = function() {
        const tables = document.querySelectorAll('.tt-admin-table');

        tables.forEach(function(table) {
            const searchInput = document.querySelector('.tt-table-search');
            const perPageSelect = document.querySelector('.tt-per-page');

            // Simple client-side filtering
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    table.querySelectorAll('tbody tr').forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(query) ? '' : 'none';
                    });
                });
            }

            // Per page
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    // Reload with new per_page parameter
                    const url = new URL(window.location);
                    url.searchParams.set('per_page', this.value);
                    window.location.href = url.toString();
                });
            }
        });
    };

    /**
     * Partner Management
     */
    TeepTrakAdmin.initPartnerManagement = function() {
        const self = this;

        // Edit partner button
        document.querySelectorAll('.tt-edit-partner').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const partnerId = this.dataset.partnerId;
                self.openPartnerModal(partnerId);
            });
        });

        // View partner
        document.querySelectorAll('.tt-view-partner').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const partnerId = this.dataset.partnerId;
                window.location.href = `?page=teeptrak-partners&action=view&partner=${partnerId}`;
            });
        });

        // Change partner tier
        document.querySelectorAll('.tt-change-tier').forEach(function(select) {
            select.addEventListener('change', function() {
                const partnerId = this.dataset.partnerId;
                const newTier = this.value;
                self.updatePartnerTier(partnerId, newTier);
            });
        });

        // Resend welcome email
        document.querySelectorAll('.tt-resend-welcome').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const partnerId = this.dataset.partnerId;
                self.resendWelcomeEmail(partnerId);
            });
        });
    };

    TeepTrakAdmin.openPartnerModal = function(partnerId) {
        // Fetch partner data and open modal
        TeepTrak.ajax('teeptrak_admin_get_partner', {
            partner_id: partnerId
        }, {
            onSuccess: function(partner) {
                // Create and show modal
                const modal = document.createElement('div');
                modal.className = 'tt-modal tt-show';
                modal.innerHTML = `
                    <div class="tt-modal-content">
                        <div class="tt-modal-header">
                            <h2>Edit Partner</h2>
                            <button class="tt-modal-close">&times;</button>
                        </div>
                        <div class="tt-modal-body">
                            <form id="edit-partner-form">
                                <input type="hidden" name="partner_id" value="${partner.id}">
                                <div class="tt-form-group">
                                    <label>Company</label>
                                    <input type="text" name="company" class="tt-input" value="${partner.company || ''}">
                                </div>
                                <div class="tt-form-group">
                                    <label>Tier</label>
                                    <select name="tier" class="tt-select">
                                        <option value="registered" ${partner.tier === 'registered' ? 'selected' : ''}>Registered</option>
                                        <option value="certified" ${partner.tier === 'certified' ? 'selected' : ''}>Certified</option>
                                        <option value="premium" ${partner.tier === 'premium' ? 'selected' : ''}>Premium</option>
                                        <option value="elite" ${partner.tier === 'elite' ? 'selected' : ''}>Elite</option>
                                    </select>
                                </div>
                                <div class="tt-form-group">
                                    <label>Commission Rate (%)</label>
                                    <input type="number" name="commission_rate" class="tt-input" value="${partner.commission_rate || ''}" step="0.1">
                                </div>
                                <div class="tt-form-group">
                                    <label>Notes</label>
                                    <textarea name="admin_notes" class="tt-textarea">${partner.admin_notes || ''}</textarea>
                                </div>
                            </form>
                        </div>
                        <div class="tt-modal-footer">
                            <button class="tt-btn tt-btn-secondary" data-close>Cancel</button>
                            <button class="tt-btn tt-btn-primary" id="save-partner">Save</button>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                modal.querySelector('.tt-modal-close').onclick = function() { modal.remove(); };
                modal.querySelector('[data-close]').onclick = function() { modal.remove(); };
                modal.querySelector('#save-partner').onclick = function() {
                    TeepTrakAdmin.savePartner(modal);
                };
            }
        });
    };

    TeepTrakAdmin.savePartner = function(modal) {
        const form = modal.querySelector('#edit-partner-form');
        const formData = new FormData(form);
        const data = {};
        formData.forEach(function(v, k) { data[k] = v; });

        TeepTrak.ajax('teeptrak_admin_update_partner', data, {
            onSuccess: function() {
                modal.remove();
                TeepTrak.showToast('Partner updated', 'success');
                window.location.reload();
            },
            onError: function(error) {
                TeepTrak.showToast(error || 'Error updating partner', 'error');
            }
        });
    };

    TeepTrakAdmin.updatePartnerTier = function(partnerId, tier) {
        TeepTrak.ajax('teeptrak_admin_update_partner_tier', {
            partner_id: partnerId,
            tier: tier
        }, {
            onSuccess: function() {
                TeepTrak.showToast('Tier updated', 'success');
            },
            onError: function() {
                TeepTrak.showToast('Error updating tier', 'error');
            }
        });
    };

    TeepTrakAdmin.resendWelcomeEmail = function(partnerId) {
        TeepTrak.ajax('teeptrak_admin_resend_welcome', {
            partner_id: partnerId
        }, {
            onSuccess: function() {
                TeepTrak.showToast('Welcome email sent', 'success');
            },
            onError: function() {
                TeepTrak.showToast('Error sending email', 'error');
            }
        });
    };

    /**
     * Deal Management
     */
    TeepTrakAdmin.initDealManagement = function() {
        const self = this;

        // Approve deal
        document.querySelectorAll('.tt-approve-deal').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const dealId = this.dataset.dealId;
                self.approveDeal(dealId);
            });
        });

        // Reject deal
        document.querySelectorAll('.tt-reject-deal').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const dealId = this.dataset.dealId;
                self.rejectDeal(dealId);
            });
        });

        // Change deal stage
        document.querySelectorAll('.tt-admin-stage-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const dealId = this.dataset.dealId;
                const stage = this.value;
                self.updateDealStage(dealId, stage);
            });
        });
    };

    TeepTrakAdmin.approveDeal = function(dealId) {
        TeepTrak.confirm('Approve this deal registration?', {
            confirmText: 'Approve',
            type: 'success'
        }).then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_approve_deal', {
                deal_id: dealId
            }, {
                onSuccess: function() {
                    TeepTrak.showToast('Deal approved', 'success');
                    window.location.reload();
                }
            });
        });
    };

    TeepTrakAdmin.rejectDeal = function(dealId) {
        TeepTrak.confirm('Reject this deal registration?', {
            confirmText: 'Reject',
            type: 'danger'
        }).then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_reject_deal', {
                deal_id: dealId
            }, {
                onSuccess: function() {
                    TeepTrak.showToast('Deal rejected', 'success');
                    window.location.reload();
                }
            });
        });
    };

    TeepTrakAdmin.updateDealStage = function(dealId, stage) {
        TeepTrak.ajax('teeptrak_admin_update_deal_stage', {
            deal_id: dealId,
            stage: stage
        }, {
            onSuccess: function() {
                TeepTrak.showToast('Stage updated', 'success');
            },
            onError: function() {
                TeepTrak.showToast('Error updating stage', 'error');
            }
        });
    };

    /**
     * Commission Management
     */
    TeepTrakAdmin.initCommissionManagement = function() {
        const self = this;

        // Approve payout
        document.querySelectorAll('.tt-approve-payout').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const payoutId = this.dataset.payoutId;
                self.approvePayout(payoutId);
            });
        });

        // Reject payout
        document.querySelectorAll('.tt-reject-payout').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const payoutId = this.dataset.payoutId;
                self.rejectPayout(payoutId);
            });
        });

        // Mark as paid
        document.querySelectorAll('.tt-mark-paid').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const payoutId = this.dataset.payoutId;
                self.markPaid(payoutId);
            });
        });
    };

    TeepTrakAdmin.approvePayout = function(payoutId) {
        TeepTrak.confirm('Approve this payout request?', {
            confirmText: 'Approve'
        }).then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_approve_payout', {
                payout_id: payoutId
            }, {
                onSuccess: function() {
                    TeepTrak.showToast('Payout approved', 'success');
                    window.location.reload();
                }
            });
        });
    };

    TeepTrakAdmin.rejectPayout = function(payoutId) {
        const reason = prompt('Reason for rejection (optional):');

        TeepTrak.ajax('teeptrak_admin_reject_payout', {
            payout_id: payoutId,
            reason: reason || ''
        }, {
            onSuccess: function() {
                TeepTrak.showToast('Payout rejected', 'success');
                window.location.reload();
            }
        });
    };

    TeepTrakAdmin.markPaid = function(payoutId) {
        TeepTrak.confirm('Mark this payout as paid?', {
            confirmText: 'Mark Paid'
        }).then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_mark_paid', {
                payout_id: payoutId
            }, {
                onSuccess: function() {
                    TeepTrak.showToast('Marked as paid', 'success');
                    window.location.reload();
                }
            });
        });
    };

    /**
     * Bulk Actions
     */
    TeepTrakAdmin.initBulkActions = function() {
        const self = this;
        const selectAll = document.querySelector('.tt-select-all');
        const bulkActionBtn = document.querySelector('.tt-bulk-action-btn');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.tt-row-checkbox').forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
                self.updateBulkActionBtn();
            });
        }

        document.querySelectorAll('.tt-row-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function() {
                self.updateBulkActionBtn();
            });
        });

        if (bulkActionBtn) {
            bulkActionBtn.addEventListener('click', function() {
                self.executeBulkAction();
            });
        }
    };

    TeepTrakAdmin.updateBulkActionBtn = function() {
        const checked = document.querySelectorAll('.tt-row-checkbox:checked').length;
        const btn = document.querySelector('.tt-bulk-action-btn');
        const countSpan = document.querySelector('.tt-selected-count');

        if (btn) {
            btn.disabled = checked === 0;
        }
        if (countSpan) {
            countSpan.textContent = checked;
        }
    };

    TeepTrakAdmin.executeBulkAction = function() {
        const action = document.querySelector('.tt-bulk-action-select')?.value;
        const ids = Array.from(document.querySelectorAll('.tt-row-checkbox:checked')).map(function(cb) {
            return cb.value;
        });

        if (!action || ids.length === 0) return;

        TeepTrak.confirm(`Execute "${action}" on ${ids.length} items?`).then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_bulk_action', {
                action: action,
                ids: JSON.stringify(ids)
            }, {
                onSuccess: function(result) {
                    TeepTrak.showToast(`${result.processed} items processed`, 'success');
                    window.location.reload();
                },
                onError: function(error) {
                    TeepTrak.showToast(error || 'Bulk action failed', 'error');
                }
            });
        });
    };

    /**
     * Export functionality
     */
    TeepTrakAdmin.initExports = function() {
        document.querySelectorAll('.tt-export-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = this.dataset.type;
                const format = this.dataset.format || 'csv';
                TeepTrakAdmin.exportData(type, format);
            });
        });
    };

    TeepTrakAdmin.exportData = function(type, format) {
        const params = new URLSearchParams({
            action: 'teeptrak_admin_export',
            type: type,
            format: format,
            nonce: teeptrak_ajax.nonce
        });

        // Get current filters
        const filters = document.querySelector('.tt-filters-form');
        if (filters) {
            new FormData(filters).forEach(function(value, key) {
                params.append(key, value);
            });
        }

        window.location.href = teeptrak_ajax.url + '?' + params.toString();
    };

    /**
     * Settings page
     */
    TeepTrakAdmin.initSettings = function() {
        const form = document.querySelector('#teeptrak-settings-form');
        if (!form) return;

        // Test Odoo connection
        document.querySelector('#test-odoo-connection')?.addEventListener('click', function() {
            TeepTrakAdmin.testOdooConnection();
        });

        // Clear cache
        document.querySelector('#clear-cache')?.addEventListener('click', function() {
            TeepTrakAdmin.clearCache();
        });

        // View webhook log
        document.querySelector('#view-webhook-log')?.addEventListener('click', function() {
            TeepTrakAdmin.viewWebhookLog();
        });
    };

    TeepTrakAdmin.testOdooConnection = function() {
        const btn = document.querySelector('#test-odoo-connection');
        const result = document.querySelector('#odoo-connection-result');

        btn.disabled = true;
        btn.textContent = 'Testing...';

        TeepTrak.ajax('teeptrak_test_odoo_connection', {}, {
            onSuccess: function(data) {
                btn.disabled = false;
                btn.textContent = 'Test Connection';
                result.innerHTML = '<span class="tt-text-success">Connected successfully! Version: ' + data.version + '</span>';
            },
            onError: function(error) {
                btn.disabled = false;
                btn.textContent = 'Test Connection';
                result.innerHTML = '<span class="tt-text-danger">Connection failed: ' + error + '</span>';
            }
        });
    };

    TeepTrakAdmin.clearCache = function() {
        TeepTrak.confirm('Clear all cached data?').then(function(confirmed) {
            if (!confirmed) return;

            TeepTrak.ajax('teeptrak_admin_clear_cache', {}, {
                onSuccess: function() {
                    TeepTrak.showToast('Cache cleared', 'success');
                }
            });
        });
    };

    TeepTrakAdmin.viewWebhookLog = function() {
        TeepTrak.ajax('teeptrak_admin_get_webhook_log', {}, {
            onSuccess: function(log) {
                const modal = document.createElement('div');
                modal.className = 'tt-modal tt-show';
                modal.innerHTML = `
                    <div class="tt-modal-content tt-modal-lg">
                        <div class="tt-modal-header">
                            <h2>Webhook Log</h2>
                            <button class="tt-modal-close">&times;</button>
                        </div>
                        <div class="tt-modal-body">
                            <pre class="tt-code-block">${log || 'No log entries'}</pre>
                        </div>
                        <div class="tt-modal-footer">
                            <button class="tt-btn tt-btn-secondary" data-close>Close</button>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);
                modal.querySelector('.tt-modal-close').onclick = function() { modal.remove(); };
                modal.querySelector('[data-close]').onclick = function() { modal.remove(); };
            }
        });
    };

    /**
     * Odoo Sync
     */
    TeepTrakAdmin.initOdooSync = function() {
        document.querySelector('#sync-all-deals')?.addEventListener('click', function() {
            TeepTrakAdmin.syncAllDeals();
        });

        document.querySelector('#sync-all-partners')?.addEventListener('click', function() {
            TeepTrakAdmin.syncAllPartners();
        });
    };

    TeepTrakAdmin.syncAllDeals = function() {
        const btn = document.querySelector('#sync-all-deals');
        btn.disabled = true;
        btn.textContent = 'Syncing...';

        TeepTrak.ajax('teeptrak_sync_all_deals', {}, {
            onSuccess: function(result) {
                btn.disabled = false;
                btn.textContent = 'Sync All Deals';
                TeepTrak.showToast(`Synced ${result.synced} deals`, 'success');
            },
            onError: function(error) {
                btn.disabled = false;
                btn.textContent = 'Sync All Deals';
                TeepTrak.showToast('Sync failed: ' + error, 'error');
            }
        });
    };

    TeepTrakAdmin.syncAllPartners = function() {
        const btn = document.querySelector('#sync-all-partners');
        btn.disabled = true;
        btn.textContent = 'Syncing...';

        TeepTrak.ajax('teeptrak_sync_all_partners', {}, {
            onSuccess: function(result) {
                btn.disabled = false;
                btn.textContent = 'Sync All Partners';
                TeepTrak.showToast(`Synced ${result.synced} partners`, 'success');
            },
            onError: function(error) {
                btn.disabled = false;
                btn.textContent = 'Sync All Partners';
                TeepTrak.showToast('Sync failed: ' + error, 'error');
            }
        });
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', TeepTrakAdmin.init.bind(TeepTrakAdmin));
    } else {
        TeepTrakAdmin.init();
    }

})();
