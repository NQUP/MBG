/**
 * Dashboard chart initialization helpers.
 * These functions are called from the blade template with server-rendered data.
 */

// Color palette used across all charts
const DASHBOARD_COLORS = {
    green: '#16382b',
    greenLight: 'rgba(22,56,43,0.14)',
    leaf: '#5fa876',
    leafLight: 'rgba(95,168,118,0.18)',
    orange: '#d9822b',
    blue: '#4a7a8c',
    red: '#c0503c',
    yellow: '#e2a83f',
};

function createBarChart(canvasId, labels, datasetConfigs, options) {
    var ctx = document.getElementById(canvasId);
    if (!ctx) return;
    var datasets = datasetConfigs.map(function(cfg) {
        return {
            label: cfg.label,
            data: cfg.data,
            backgroundColor: cfg.backgroundColor || DASHBOARD_COLORS.leafLight,
            borderColor: cfg.borderColor || DASHBOARD_COLORS.leaf,
            borderWidth: cfg.borderWidth || 1,
            borderRadius: cfg.borderRadius || 4,
            type: cfg.type || 'bar',
            order: cfg.order || 2,
            fill: cfg.fill !== undefined ? cfg.fill : undefined,
            tension: cfg.tension || undefined,
        };
    });
    new Chart(ctx, {
        type: datasets[0].type || 'bar',
        data: { labels: labels, datasets: datasets },
        options: Object.assign({
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }, options || {})
    });
}

function createDoughnutChart(canvasId, labels, data, colors) {
    var ctx = document.getElementById(canvasId);
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 10 }
                }
            }
        }
    });
}

function createLineChart(canvasId, labels, datasets, options) {
    var ctx = document.getElementById(canvasId);
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: Object.assign({
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }, options || {})
    });
}
