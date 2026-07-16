
let resepData = [];

function loadResep() {
    const menuId = document.getElementById('menuMakanan').value;
    if (!menuId) {
        resepData = [];
        updatePreview();
        return;
    }

    fetch(`{{ route('dapur.produksi.resep') }}?menu_id=${menuId}`)
        .then(res => res.json())
        .then(data => {
            resepData = data;
            updatePreview();
        });
}

function getSelectedOption(selectId) {
    const sel = document.getElementById(selectId);
    return sel.options[sel.selectedIndex];
}

function updatePreview() {
    const siswa = parseInt(document.getElementById('jumlahSiswa').value) || 0;
    const preview = document.getElementById('previewResep');
    const peringatan = document.getElementById('peringatanStok');
    let adaKurang = false;
    let html = '';

    // Bagian Makanan (resep)
    let htmlMakanan = '';
    if (resepData.length > 0) {
        htmlMakanan += '<div class="fw-semibold small text-success mb-1"><i class="bi bi-egg-fried me-1"></i>Bahan Makanan</div>';
        resepData.forEach(item => {
            const kebutuhan = item.jumlah_per_porsi * siswa;
            const kurang = item.stok < kebutuhan;
            if (kurang) adaKurang = true;
            htmlMakanan += `<div class="list-group-item px-0 d-flex justify-content-between align-items-start ${kurang ? 'text-danger' : ''}" style="border-bottom:1px solid #f0f0f0;">
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;">${item.nama_bahan}</div>
                    <small class="text-muted">${item.jumlah_per_porsi} ${item.satuan}/porsi * ${siswa}</small>
                    ${kurang ? '<small class="d-block text-danger"><i class="bi bi-exclamation-triangle"></i> Stok tidak cukup</small>' : ''}
                </div>
                <div class="text-end">
                    <strong>${kebutuhan.toFixed(2)} ${item.satuan}</strong><br>
                    <small class="text-muted">Stok: ${parseFloat(item.stok).toFixed(2)}</small>
                </div>
            </div>`;
        });
    }

    // Bagian Minuman
    let htmlMinuman = '';
    const optMinum = getSelectedOption('menuMinuman');
    if (optMinum.value && siswa > 0) {
        const stok = parseFloat(optMinum.dataset.stok) || 0;
        const satuan = optMinum.dataset.satuan || '';
        const nama = optMinum.dataset.nama || 'Minuman';
        const kurang = stok < siswa;
        if (kurang) adaKurang = true;
        htmlMinuman += '<div class="fw-semibold small text-warning mt-2 mb-1"><i class="bi bi-cup-straw me-1"></i>Minuman</div>';
        htmlMinuman += `<div class="list-group-item px-0 d-flex justify-content-between align-items-start ${kurang ? 'text-danger' : ''}" style="border-bottom:1px solid #f0f0f0;">
            <div>
                <div class="fw-semibold" style="font-size:0.9rem;">${nama}</div>
                <small class="text-muted">1 ${satuan}/siswa * ${siswa}</small>
                ${kurang ? '<small class="d-block text-danger"><i class="bi bi-exclamation-triangle"></i> Stok tidak cukup</small>' : ''}
            </div>
            <div class="text-end">
                <strong>${siswa} ${satuan}</strong><br>
                <small class="text-muted">Stok: ${stok.toFixed(2)}</small>
            </div>
        </div>`;
    }

    // Bagian Buah
    let htmlBuah = '';
    const optBuah = getSelectedOption('menuBuah');
    if (optBuah.value && siswa > 0) {
        const stok = parseFloat(optBuah.dataset.stok) || 0;
        const satuan = optBuah.dataset.satuan || '';
        const nama = optBuah.dataset.nama || 'Buah';
        const kurang = stok < siswa;
        if (kurang) adaKurang = true;
        htmlBuah += '<div class="fw-semibold small text-danger mt-2 mb-1"><i class="bi bi-apple me-1"></i>Buah</div>';
        htmlBuah += `<div class="list-group-item px-0 d-flex justify-content-between align-items-start ${kurang ? 'text-danger' : ''}" style="border-bottom:1px solid #f0f0f0;">
            <div>
                <div class="fw-semibold" style="font-size:0.9rem;">${nama}</div>
                <small class="text-muted">1 ${satuan}/siswa * ${siswa}</small>
                ${kurang ? '<small class="d-block text-danger"><i class="bi bi-exclamation-triangle"></i> Stok tidak cukup</small>' : ''}
            </div>
            <div class="text-end">
                <strong>${siswa} ${satuan}</strong><br>
                <small class="text-muted">Stok: ${stok.toFixed(2)}</small>
            </div>
        </div>`;
    }

    if (!resepData.length && !htmlMinuman && !htmlBuah) {
        preview.innerHTML = `<div class="text-center text-muted py-4"><i class="bi bi-journal-text fs-1 d-block mb-2"></i>Pilih menu makanan/minuman/buah dan isi jumlah siswa untuk melihat kebutuhan.</div>`;
        peringatan.classList.add('d-none');
        return;
    }

    html += `<div class="mb-2 text-muted small">Jumlah siswa: <strong>${siswa}</strong></div>`;
    html += htmlMakanan + htmlMinuman + htmlBuah;
    preview.innerHTML = html;

    if (adaKurang && siswa > 0) {
        peringatan.classList.remove('d-none');
    } else {
        peringatan.classList.add('d-none');
    }
}
