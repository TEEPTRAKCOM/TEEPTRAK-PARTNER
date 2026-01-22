/**
 * TeepTrak Partner Theme V3 - Charts Module
 *
 * Dashboard charts using Chart.js
 *
 * @package TeepTrak_Partner_Theme_V3
 */

(function() {
    'use strict';

    window.TeepTrak = window.TeepTrak || {};
    TeepTrak.Charts = {};

    // Chart.js default configuration
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1F2937',
                titleColor: '#FFFFFF',
                bodyColor: '#E5E7EB',
                cornerRadius: 8,
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: '600'
                },
                bodyFont: {
                    size: 13
                }
            }
        }
    };

    // Color palette
    TeepTrak.Charts.colors = {
        primary: '#E63946',
        secondary: '#1D3557',
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
        info: '#3B82F6',
        gray: '#6B7280',
        lightGray: '#E5E7EB',
        stages: {
            lead: '#3B82F6',
            qualified: '#8B5CF6',
            proposal: '#F59E0B',
            negotiation: '#F97316',
            won: '#10B981',
            lost: '#EF4444'
        }
    };

    /**
     * Initialize all dashboard charts
     */
    TeepTrak.Charts.init = function() {
        this.initPipelineChart();
        this.initCommissionChart();
        this.initPerformanceChart();
        this.initDealStagesChart();
        this.initMonthlyDealsChart();
        this.initConversionChart();
    };

    /**
     * Pipeline Value Chart (Doughnut)
     */
    TeepTrak.Charts.initPipelineChart = function() {
        const canvas = document.getElementById('pipeline-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const data = canvas.dataset.values ? JSON.parse(canvas.dataset.values) : null;

        if (!data) {
            // Load data via AJAX
            this.loadChartData('pipeline', function(result) {
                TeepTrak.Charts.createPipelineChart(ctx, result);
            });
        } else {
            this.createPipelineChart(ctx, data);
        }
    };

    TeepTrak.Charts.createPipelineChart = function(ctx, data) {
        const stages = Object.keys(data);
        const values = Object.values(data);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: stages.map(function(s) {
                    return teeptrak_i18n?.['stage_' + s] || s.charAt(0).toUpperCase() + s.slice(1);
                }),
                datasets: [{
                    data: values,
                    backgroundColor: stages.map(function(s) {
                        return TeepTrak.Charts.colors.stages[s] || TeepTrak.Charts.colors.gray;
                    }),
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                ...defaultOptions,
                cutout: '70%',
                plugins: {
                    ...defaultOptions.plugins,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return TeepTrak.formatCurrency(context.raw) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    };

    /**
     * Commission Trend Chart (Line)
     */
    TeepTrak.Charts.initCommissionChart = function() {
        const canvas = document.getElementById('commission-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        this.loadChartData('commissions', function(data) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: teeptrak_i18n?.earned || 'Earned',
                        data: data.earned,
                        borderColor: TeepTrak.Charts.colors.success,
                        backgroundColor: TeepTrak.Charts.colors.success + '20',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: TeepTrak.Charts.colors.success
                    }, {
                        label: teeptrak_i18n?.pending || 'Pending',
                        data: data.pending,
                        borderColor: TeepTrak.Charts.colors.warning,
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: TeepTrak.Charts.colors.warning
                    }]
                },
                options: {
                    ...defaultOptions,
                    plugins: {
                        ...defaultOptions.plugins,
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                padding: 16
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#E5E7EB'
                            },
                            ticks: {
                                callback: function(value) {
                                    return TeepTrak.formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        });
    };

    /**
     * Performance Chart (Bar)
     */
    TeepTrak.Charts.initPerformanceChart = function() {
        const canvas = document.getElementById('performance-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        this.loadChartData('performance', function(data) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: teeptrak_i18n?.deals_closed || 'Deals Closed',
                        data: data.deals,
                        backgroundColor: TeepTrak.Charts.colors.primary,
                        borderRadius: 4,
                        barThickness: 24
                    }, {
                        label: teeptrak_i18n?.revenue || 'Revenue',
                        data: data.revenue,
                        backgroundColor: TeepTrak.Charts.colors.secondary,
                        borderRadius: 4,
                        barThickness: 24,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    ...defaultOptions,
                    plugins: {
                        ...defaultOptions.plugins,
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return TeepTrak.formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        });
    };

    /**
     * Deal Stages Chart (Horizontal Bar)
     */
    TeepTrak.Charts.initDealStagesChart = function() {
        const canvas = document.getElementById('deal-stages-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        this.loadChartData('stages', function(data) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.counts,
                        backgroundColor: data.labels.map(function(label, i) {
                            const stage = label.toLowerCase().replace(' ', '_');
                            return TeepTrak.Charts.colors.stages[stage] || TeepTrak.Charts.colors.gray;
                        }),
                        borderRadius: 4
                    }]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    };

    /**
     * Monthly Deals Chart (Area)
     */
    TeepTrak.Charts.initMonthlyDealsChart = function() {
        const canvas = document.getElementById('monthly-deals-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        this.loadChartData('monthly', function(data) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, TeepTrak.Charts.colors.primary + '40');
            gradient.addColorStop(1, TeepTrak.Charts.colors.primary + '00');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: teeptrak_i18n?.deals || 'Deals',
                        data: data.values,
                        borderColor: TeepTrak.Charts.colors.primary,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: TeepTrak.Charts.colors.primary
                    }]
                },
                options: {
                    ...defaultOptions,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#E5E7EB'
                            }
                        }
                    }
                }
            });
        });
    };

    /**
     * Conversion Rate Chart (Gauge)
     */
    TeepTrak.Charts.initConversionChart = function() {
        const container = document.querySelector('.tt-conversion-gauge');
        if (!container) return;

        const rate = parseFloat(container.dataset.rate) || 0;
        const circumference = 2 * Math.PI * 70;
        const offset = circumference - (rate / 100) * circumference;

        container.innerHTML = `
            <svg viewBox="0 0 160 160" class="tt-gauge-svg">
                <circle cx="80" cy="80" r="70" fill="none" stroke="#E5E7EB" stroke-width="12"/>
                <circle cx="80" cy="80" r="70" fill="none"
                    stroke="${this.getConversionColor(rate)}"
                    stroke-width="12"
                    stroke-dasharray="${circumference}"
                    stroke-dashoffset="${offset}"
                    stroke-linecap="round"
                    transform="rotate(-90 80 80)"
                    class="tt-gauge-progress"/>
            </svg>
            <div class="tt-gauge-value">
                <span class="tt-gauge-number">${rate.toFixed(1)}%</span>
                <span class="tt-gauge-label">${teeptrak_i18n?.conversion_rate || 'Conversion Rate'}</span>
            </div>
        `;
    };

    TeepTrak.Charts.getConversionColor = function(rate) {
        if (rate >= 30) return this.colors.success;
        if (rate >= 20) return this.colors.warning;
        return this.colors.danger;
    };

    /**
     * Load chart data via AJAX
     */
    TeepTrak.Charts.loadChartData = function(type, callback) {
        if (typeof teeptrak_ajax === 'undefined') {
            console.warn('TeepTrak AJAX not available');
            return;
        }

        fetch(teeptrak_ajax.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'teeptrak_get_chart_data',
                type: type,
                nonce: teeptrak_ajax.nonce
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(result) {
            if (result.success && callback) {
                callback(result.data);
            }
        })
        .catch(function(error) {
            console.error('Chart data error:', error);
        });
    };

    /**
     * Update chart with new data
     */
    TeepTrak.Charts.updateChart = function(chartId, newData) {
        const chart = Chart.getChart(chartId);
        if (!chart) return;

        if (newData.labels) {
            chart.data.labels = newData.labels;
        }

        if (newData.datasets) {
            chart.data.datasets.forEach(function(dataset, i) {
                if (newData.datasets[i]) {
                    Object.assign(dataset, newData.datasets[i]);
                }
            });
        }

        chart.update('active');
    };

    /**
     * Create mini sparkline chart
     */
    TeepTrak.Charts.sparkline = function(canvas, data, color) {
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        color = color || this.colors.primary;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(function(_, i) { return i; }),
                datasets: [{
                    data: data,
                    borderColor: color,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    };

    /**
     * Initialize sparklines on the page
     */
    TeepTrak.Charts.initSparklines = function() {
        document.querySelectorAll('.tt-sparkline').forEach(function(canvas) {
            const data = JSON.parse(canvas.dataset.values || '[]');
            const color = canvas.dataset.color || TeepTrak.Charts.colors.primary;
            TeepTrak.Charts.sparkline(canvas, data, color);
        });
    };

    // Initialize charts when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart !== 'undefined') {
                TeepTrak.Charts.init();
                TeepTrak.Charts.initSparklines();
            }
        });
    } else {
        if (typeof Chart !== 'undefined') {
            TeepTrak.Charts.init();
            TeepTrak.Charts.initSparklines();
        }
    }

})();
