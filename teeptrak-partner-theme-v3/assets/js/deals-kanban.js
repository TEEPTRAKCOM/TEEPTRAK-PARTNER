/**
 * TeepTrak Partner Theme V3 - Deals Kanban Module
 *
 * Kanban board with drag-and-drop using SortableJS
 *
 * @package TeepTrak_Partner_Theme_V3
 */

(function() {
    'use strict';

    window.TeepTrak = window.TeepTrak || {};
    TeepTrak.Kanban = {};

    // Deal stages configuration
    const stages = [
        { id: 'lead', label: 'Lead', color: '#3B82F6' },
        { id: 'qualified', label: 'Qualified', color: '#8B5CF6' },
        { id: 'proposal', label: 'Proposal', color: '#F59E0B' },
        { id: 'negotiation', label: 'Negotiation', color: '#F97316' },
        { id: 'won', label: 'Won', color: '#10B981' },
        { id: 'lost', label: 'Lost', color: '#EF4444' }
    ];

    let sortableInstances = [];

    /**
     * Initialize Kanban board
     */
    TeepTrak.Kanban.init = function() {
        const board = document.querySelector('.tt-kanban-board');
        if (!board) return;

        this.board = board;
        this.loadDeals();
        this.initFilters();
        this.initSearch();
        this.initViewToggle();
    };

    /**
     * Load deals from server
     */
    TeepTrak.Kanban.loadDeals = function() {
        const self = this;

        TeepTrak.ajax('teeptrak_get_deals', {
            view: 'kanban'
        }, {
            onSuccess: function(data) {
                self.renderBoard(data.deals);
                self.initDragDrop();
            },
            onError: function() {
                TeepTrak.showToast(teeptrak_i18n?.error_loading_deals || 'Error loading deals', 'error');
            }
        });
    };

    /**
     * Render Kanban board
     */
    TeepTrak.Kanban.renderBoard = function(deals) {
        let html = '<div class="tt-kanban-columns">';

        stages.forEach(function(stage) {
            const stageDeals = deals.filter(function(deal) {
                return deal.stage === stage.id;
            });

            const totalValue = stageDeals.reduce(function(sum, deal) {
                return sum + parseFloat(deal.value || 0);
            }, 0);

            html += `
                <div class="tt-kanban-column" data-stage="${stage.id}">
                    <div class="tt-kanban-column-header" style="--stage-color: ${stage.color}">
                        <h3 class="tt-kanban-column-title">
                            ${teeptrak_i18n?.['stage_' + stage.id] || stage.label}
                            <span class="tt-kanban-count">${stageDeals.length}</span>
                        </h3>
                        <div class="tt-kanban-column-value">${TeepTrak.formatCurrency(totalValue)}</div>
                    </div>
                    <div class="tt-kanban-cards" data-stage="${stage.id}">
                        ${stageDeals.map(function(deal) {
                            return TeepTrak.Kanban.renderCard(deal);
                        }).join('')}
                    </div>
                </div>
            `;
        });

        html += '</div>';
        this.board.innerHTML = html;
    };

    /**
     * Render deal card
     */
    TeepTrak.Kanban.renderCard = function(deal) {
        const daysProtected = deal.protection_end ?
            Math.ceil((new Date(deal.protection_end) - new Date()) / (1000 * 60 * 60 * 24)) : 0;
        const protectionPercent = Math.min(100, Math.max(0, (daysProtected / 90) * 100));

        return `
            <div class="tt-kanban-card" data-deal-id="${deal.id}" draggable="true">
                <div class="tt-kanban-card-header">
                    <span class="tt-kanban-card-company">${this.escapeHtml(deal.company)}</span>
                    ${deal.priority === 'high' ? '<span class="tt-badge tt-badge-danger">High</span>' : ''}
                </div>
                <h4 class="tt-kanban-card-title">${this.escapeHtml(deal.contact_name || 'No contact')}</h4>
                <div class="tt-kanban-card-value">${TeepTrak.formatCurrency(deal.value)}</div>
                <div class="tt-kanban-card-meta">
                    <span class="tt-kanban-card-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        ${deal.expected_close || '-'}
                    </span>
                </div>
                ${daysProtected > 0 ? `
                    <div class="tt-kanban-card-protection">
                        <div class="tt-protection-bar">
                            <div class="tt-protection-fill" style="width: ${protectionPercent}%"></div>
                        </div>
                        <span class="tt-protection-text">${daysProtected}d</span>
                    </div>
                ` : ''}
                <div class="tt-kanban-card-actions">
                    <button class="tt-btn-icon" data-action="view" data-deal-id="${deal.id}" title="${teeptrak_i18n?.view || 'View'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                    <button class="tt-btn-icon" data-action="edit" data-deal-id="${deal.id}" title="${teeptrak_i18n?.edit || 'Edit'}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    };

    /**
     * Initialize drag and drop
     */
    TeepTrak.Kanban.initDragDrop = function() {
        const self = this;

        // Destroy existing instances
        sortableInstances.forEach(function(instance) {
            instance.destroy();
        });
        sortableInstances = [];

        // Create new sortable instances for each column
        document.querySelectorAll('.tt-kanban-cards').forEach(function(column) {
            const sortable = new Sortable(column, {
                group: 'deals',
                animation: 150,
                ghostClass: 'tt-kanban-card-ghost',
                chosenClass: 'tt-kanban-card-chosen',
                dragClass: 'tt-kanban-card-drag',
                filter: '.tt-kanban-card-actions',
                onEnd: function(evt) {
                    const dealId = evt.item.dataset.dealId;
                    const newStage = evt.to.dataset.stage;
                    const oldStage = evt.from.dataset.stage;

                    if (newStage !== oldStage) {
                        self.updateDealStage(dealId, newStage, oldStage, evt.item);
                    }

                    // Update column counts
                    self.updateColumnCounts();
                }
            });

            sortableInstances.push(sortable);
        });

        // Card action buttons
        document.querySelectorAll('.tt-kanban-card-actions button').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.dataset.action;
                const dealId = this.dataset.dealId;

                if (action === 'view') {
                    self.viewDeal(dealId);
                } else if (action === 'edit') {
                    self.editDeal(dealId);
                }
            });
        });
    };

    /**
     * Update deal stage via AJAX
     */
    TeepTrak.Kanban.updateDealStage = function(dealId, newStage, oldStage, cardElement) {
        const self = this;

        cardElement.classList.add('tt-loading');

        TeepTrak.ajax('teeptrak_update_deal_stage', {
            deal_id: dealId,
            stage: newStage
        }, {
            onSuccess: function(data) {
                cardElement.classList.remove('tt-loading');
                TeepTrak.showToast(teeptrak_i18n?.deal_updated || 'Deal updated', 'success');

                // If moved to won, show commission notification
                if (newStage === 'won' && data.commission) {
                    TeepTrak.showToast(
                        (teeptrak_i18n?.commission_earned || 'Commission earned') + ': ' +
                        TeepTrak.formatCurrency(data.commission),
                        'success',
                        5000
                    );
                }

                // Update column totals
                self.updateColumnCounts();
            },
            onError: function() {
                cardElement.classList.remove('tt-loading');

                // Revert the card to original column
                const originalColumn = document.querySelector(`.tt-kanban-cards[data-stage="${oldStage}"]`);
                if (originalColumn) {
                    originalColumn.appendChild(cardElement);
                }

                TeepTrak.showToast(teeptrak_i18n?.error_updating_deal || 'Error updating deal', 'error');
            }
        });
    };

    /**
     * Update column counts and totals
     */
    TeepTrak.Kanban.updateColumnCounts = function() {
        document.querySelectorAll('.tt-kanban-column').forEach(function(column) {
            const cards = column.querySelectorAll('.tt-kanban-card');
            const count = column.querySelector('.tt-kanban-count');
            const valueEl = column.querySelector('.tt-kanban-column-value');

            if (count) {
                count.textContent = cards.length;
            }

            if (valueEl) {
                let total = 0;
                cards.forEach(function(card) {
                    const valueText = card.querySelector('.tt-kanban-card-value')?.textContent || '0';
                    total += parseFloat(valueText.replace(/[^0-9.-]/g, '')) || 0;
                });
                valueEl.textContent = TeepTrak.formatCurrency(total);
            }
        });
    };

    /**
     * View deal details
     */
    TeepTrak.Kanban.viewDeal = function(dealId) {
        const self = this;

        TeepTrak.ajax('teeptrak_get_deal', {
            deal_id: dealId
        }, {
            onSuccess: function(deal) {
                self.showDealModal(deal, 'view');
            }
        });
    };

    /**
     * Edit deal
     */
    TeepTrak.Kanban.editDeal = function(dealId) {
        const self = this;

        TeepTrak.ajax('teeptrak_get_deal', {
            deal_id: dealId
        }, {
            onSuccess: function(deal) {
                self.showDealModal(deal, 'edit');
            }
        });
    };

    /**
     * Show deal modal
     */
    TeepTrak.Kanban.showDealModal = function(deal, mode) {
        const self = this;
        const isEdit = mode === 'edit';
        const stageOptions = stages.map(function(s) {
            return `<option value="${s.id}" ${deal.stage === s.id ? 'selected' : ''}>
                ${teeptrak_i18n?.['stage_' + s.id] || s.label}
            </option>`;
        }).join('');

        const modal = document.createElement('div');
        modal.className = 'tt-modal tt-deal-modal tt-show';
        modal.innerHTML = `
            <div class="tt-modal-content tt-modal-lg">
                <div class="tt-modal-header">
                    <h2 class="tt-modal-title">${isEdit ? (teeptrak_i18n?.edit_deal || 'Edit Deal') : (teeptrak_i18n?.deal_details || 'Deal Details')}</h2>
                    <button class="tt-modal-close" data-modal-close>&times;</button>
                </div>
                <div class="tt-modal-body">
                    <form id="deal-form" ${isEdit ? 'data-validate' : ''}>
                        <input type="hidden" name="deal_id" value="${deal.id}">

                        <div class="tt-form-grid">
                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.company || 'Company'}</label>
                                <input type="text" name="company" class="tt-input" value="${this.escapeHtml(deal.company)}" ${isEdit ? '' : 'disabled'} required>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.contact_name || 'Contact Name'}</label>
                                <input type="text" name="contact_name" class="tt-input" value="${this.escapeHtml(deal.contact_name || '')}" ${isEdit ? '' : 'disabled'}>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.contact_email || 'Contact Email'}</label>
                                <input type="email" name="contact_email" class="tt-input" value="${this.escapeHtml(deal.contact_email || '')}" ${isEdit ? '' : 'disabled'}>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.contact_phone || 'Contact Phone'}</label>
                                <input type="tel" name="contact_phone" class="tt-input" value="${this.escapeHtml(deal.contact_phone || '')}" ${isEdit ? '' : 'disabled'}>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.deal_value || 'Deal Value'}</label>
                                <input type="number" name="value" class="tt-input" value="${deal.value || ''}" ${isEdit ? '' : 'disabled'} step="0.01">
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.stage || 'Stage'}</label>
                                <select name="stage" class="tt-select" ${isEdit ? '' : 'disabled'}>
                                    ${stageOptions}
                                </select>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.expected_close || 'Expected Close'}</label>
                                <input type="date" name="expected_close" class="tt-input" value="${deal.expected_close || ''}" ${isEdit ? '' : 'disabled'}>
                            </div>

                            <div class="tt-form-group">
                                <label class="tt-label">${teeptrak_i18n?.protection_end || 'Protection Ends'}</label>
                                <input type="text" class="tt-input" value="${deal.protection_end || '-'}" disabled>
                            </div>
                        </div>

                        <div class="tt-form-group tt-form-group-full">
                            <label class="tt-label">${teeptrak_i18n?.notes || 'Notes'}</label>
                            <textarea name="notes" class="tt-textarea" rows="3" ${isEdit ? '' : 'disabled'}>${this.escapeHtml(deal.notes || '')}</textarea>
                        </div>

                        ${!isEdit ? `
                            <div class="tt-deal-timeline">
                                <h4>${teeptrak_i18n?.activity || 'Activity'}</h4>
                                <div class="tt-timeline">
                                    ${(deal.timeline || []).map(function(event) {
                                        return `
                                            <div class="tt-timeline-item">
                                                <div class="tt-timeline-date">${TeepTrak.formatDate(event.date, 'relative')}</div>
                                                <div class="tt-timeline-content">${event.description}</div>
                                            </div>
                                        `;
                                    }).join('') || '<p class="tt-text-muted">' + (teeptrak_i18n?.no_activity || 'No activity yet') + '</p>'}
                                </div>
                            </div>
                        ` : ''}
                    </form>
                </div>
                <div class="tt-modal-footer">
                    <button class="tt-btn tt-btn-secondary" data-modal-close>${teeptrak_i18n?.close || 'Close'}</button>
                    ${isEdit ? `<button class="tt-btn tt-btn-primary" id="save-deal-btn">${teeptrak_i18n?.save || 'Save'}</button>` : ''}
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.classList.add('tt-modal-open');

        // Close handlers
        modal.querySelectorAll('[data-modal-close]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                modal.remove();
                document.body.classList.remove('tt-modal-open');
            });
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
                document.body.classList.remove('tt-modal-open');
            }
        });

        // Save handler
        if (isEdit) {
            document.getElementById('save-deal-btn').addEventListener('click', function() {
                self.saveDeal(modal);
            });
        }
    };

    /**
     * Save deal
     */
    TeepTrak.Kanban.saveDeal = function(modal) {
        const self = this;
        const form = document.getElementById('deal-form');
        const formData = new FormData(form);
        const data = {};

        formData.forEach(function(value, key) {
            data[key] = value;
        });

        const saveBtn = document.getElementById('save-deal-btn');
        TeepTrak.setLoading(saveBtn, true);

        TeepTrak.ajax('teeptrak_update_deal', data, {
            onSuccess: function() {
                TeepTrak.setLoading(saveBtn, false);
                modal.remove();
                document.body.classList.remove('tt-modal-open');
                TeepTrak.showToast(teeptrak_i18n?.deal_saved || 'Deal saved', 'success');
                self.loadDeals(); // Refresh board
            },
            onError: function() {
                TeepTrak.setLoading(saveBtn, false);
                TeepTrak.showToast(teeptrak_i18n?.error_saving_deal || 'Error saving deal', 'error');
            }
        });
    };

    /**
     * Initialize filters
     */
    TeepTrak.Kanban.initFilters = function() {
        const self = this;

        // Stage filter
        document.querySelectorAll('.tt-filter-stage').forEach(function(filter) {
            filter.addEventListener('change', function() {
                self.applyFilters();
            });
        });

        // Date range filter
        document.querySelectorAll('.tt-filter-date').forEach(function(filter) {
            filter.addEventListener('change', function() {
                self.applyFilters();
            });
        });

        // Value range filter
        document.querySelectorAll('.tt-filter-value').forEach(function(filter) {
            filter.addEventListener('change', function() {
                self.applyFilters();
            });
        });
    };

    /**
     * Apply filters
     */
    TeepTrak.Kanban.applyFilters = function() {
        const filters = {
            stage: document.querySelector('.tt-filter-stage')?.value,
            dateFrom: document.querySelector('.tt-filter-date-from')?.value,
            dateTo: document.querySelector('.tt-filter-date-to')?.value,
            valueMin: document.querySelector('.tt-filter-value-min')?.value,
            valueMax: document.querySelector('.tt-filter-value-max')?.value
        };

        document.querySelectorAll('.tt-kanban-card').forEach(function(card) {
            let show = true;

            // Value filter
            if (filters.valueMin || filters.valueMax) {
                const cardValue = parseFloat(card.querySelector('.tt-kanban-card-value')?.textContent.replace(/[^0-9.-]/g, '')) || 0;
                if (filters.valueMin && cardValue < parseFloat(filters.valueMin)) show = false;
                if (filters.valueMax && cardValue > parseFloat(filters.valueMax)) show = false;
            }

            card.style.display = show ? '' : 'none';
        });

        // Update counts
        this.updateColumnCounts();
    };

    /**
     * Initialize search
     */
    TeepTrak.Kanban.initSearch = function() {
        const self = this;
        const searchInput = document.querySelector('.tt-kanban-search');
        if (!searchInput) return;

        let debounce;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounce);
            debounce = setTimeout(function() {
                self.searchDeals(searchInput.value.trim().toLowerCase());
            }, 300);
        });
    };

    TeepTrak.Kanban.searchDeals = function(query) {
        document.querySelectorAll('.tt-kanban-card').forEach(function(card) {
            const company = card.querySelector('.tt-kanban-card-company')?.textContent.toLowerCase() || '';
            const title = card.querySelector('.tt-kanban-card-title')?.textContent.toLowerCase() || '';

            if (!query || company.includes(query) || title.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        this.updateColumnCounts();
    };

    /**
     * Initialize view toggle (Kanban/List)
     */
    TeepTrak.Kanban.initViewToggle = function() {
        const self = this;
        const toggleBtns = document.querySelectorAll('.tt-view-toggle button');

        toggleBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;

                toggleBtns.forEach(function(b) { b.classList.remove('tt-active'); });
                this.classList.add('tt-active');

                if (view === 'list') {
                    self.switchToListView();
                } else {
                    self.switchToKanbanView();
                }
            });
        });
    };

    TeepTrak.Kanban.switchToListView = function() {
        window.location.href = window.location.pathname + '?view=list';
    };

    TeepTrak.Kanban.switchToKanbanView = function() {
        window.location.href = window.location.pathname + '?view=kanban';
    };

    /**
     * Escape HTML helper
     */
    TeepTrak.Kanban.escapeHtml = function(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Sortable !== 'undefined') {
                TeepTrak.Kanban.init();
            }
        });
    } else {
        if (typeof Sortable !== 'undefined') {
            TeepTrak.Kanban.init();
        }
    }

})();
