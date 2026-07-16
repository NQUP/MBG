function hitungSelisih(input, i) {
    const fisik = parseFloat(input.value) || 0;
    const sistem = parseFloat(input.dataset.sistem) || 0;
    const selisih = fisik - sistem;
    const el = document.getElementById('selisih-' + i);
    el.textContent = selisih.toFixed(2);
    el.className = selisih < 0 ? 'text-danger fw-bold' : selisih > 0 ? 'text-success fw-bold' : 'text-muted';
}
