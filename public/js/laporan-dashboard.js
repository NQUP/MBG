document.addEventListener('DOMContentLoaded', function() {
    const colors = { leaf: '#16382b', leafLight: 'rgba(22,56,43,0.14)', orange: '#d9822b', blue: '#0f6cbf', purple: '#8b5cf6', red: '#c0503c', gray: '#6c757d' };

    // Trend chart
    const trendCtx = document.getElementById('chartTrend');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: {!! $bulanLabels->toJson() !!},
                datasets: [
                    { label: 'Jumlah Permintaan Bahan Baku', data: {!! $permintaanData->toJson() !!}, backgroundColor: colors.leafLight, borderColor: colors.leaf, borderWidth: 1, order: 2 },
                    { label: 'Jumlah Permintaan Pembelian', data: {!! $pembelianData->toJson() !!}, backgroundColor: 'rgba(217,130,43,0.18)', borderColor: colors.orange, borderWidth: 1, type: 'line', order: 1 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // PR Status chart
    const prCtx = document.getElementById('chartPrStatus');
    if (prCtx) {
        new Chart(prCtx, {
            type: 'doughnut',
            data: {
                labels: {!! collect(array_keys($pr_stats))->toJson() !!},
                datasets: [{ data: {!! collect(array_values($pr_stats))->toJson() !!}, backgroundColor: ['#6c757d', '#ffc107', '#198754', '#0d6efd', '#0dcaf0', '#dc3545'] }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
