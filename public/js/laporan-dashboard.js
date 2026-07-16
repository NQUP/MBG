document.addEventListener('DOMContentLoaded', function() {
    const colors = { leaf: '#16382b', leafLight: 'rgba(22,56,43,0.14)', orange: '#d9822b', orangeLight: 'rgba(217,130,43,0.18)', sky: '#4a7a8c', skyLight: 'rgba(74,122,140,0.14)', red: '#c0503c', green: '#5fa876', yellow: '#e2a83f', gray: '#9ba893', purple: '#7c6a9c' };

    // Trend chart
    const trendCtx = document.getElementById('chartTrend');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: {!! $bulanLabels->toJson() !!},
                datasets: [
                    { label: 'Jumlah Permintaan Bahan Baku', data: {!! $permintaanData->toJson() !!}, backgroundColor: colors.leafLight, borderColor: colors.leaf, borderWidth: 1, order: 2 },
                    { label: 'Jumlah Permintaan Pembelian', data: {!! $pembelianData->toJson() !!}, backgroundColor: colors.orangeLight, borderColor: colors.orange, borderWidth: 1, type: 'line', order: 1 }
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
                datasets: [{ data: {!! collect(array_values($pr_stats))->toJson() !!}, backgroundColor: [colors.gray, colors.yellow, colors.leaf, colors.sky, colors.green, colors.red] }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
