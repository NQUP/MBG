function getBahanOpts() {
    return '<option value="">-- Pilih Bahan --</option>' +
        window.bahanData.map(function(b) { 
            return '<option value="' + b.id + '" data-stok="' + b.stok + '" data-min="' + b.min + '" data-satuan="' + b.satuan + '" data-satuan-id="' + b.satuan_id + '">' + b.nama + ' (' + b.satuan + ')</option>';
        }).join('');
}
function getSatuanOpts() {
    return window.satuanData.map(function(s) { 
        return '<option value="' + s.id + '">' + s.singkatan + '</option>';
    }).join('');
}

function tambahBaris() {
    var i = window.barisIndex++;
    var tr = document.createElement('tr');
    tr.id = 'baris-' + i;
    tr.innerHTML = 
        '<td>' +
            '<select name="items[' + i + '][bahan_baku_id]" class="form-select bahan-select" required onchange="pilihBahan(this, ' + i + ')">' +
                getBahanOpts() +
            '</select>' +
            '<div id="badge-' + i + '" class="mt-1"></div>' +
        '</td>' +
        '<td><span id="stok-' + i + '" class="fw-semibold text-muted">-</span></td>' +
        '<td><span id="min-' + i + '" class="fw-semibold text-muted">-</span></td>' +
        '<td><input type="number" name="items[' + i + '][jumlah]" class="form-control" step="0.01" min="0.01" required></td>' +
        '<td><select name="items[' + i + '][satuan_id]" id="satuan-' + i + '" class="form-select satuan-select" required>' + getSatuanOpts() + '</select></td>' +
        '<td><input type="text" name="items[' + i + '][catatan]" class="form-control" placeholder="opsional"></td>' +
        '<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(' + i + ')"><i class="bi bi-trash"></i></button></td>';
    document.getElementById('detailBody').appendChild(tr);
}

function hapusBaris(i) {
    var el = document.getElementById('baris-' + i);
    if (el) el.remove();
}

function pilihBahan(select, i) {
    var opt = select.options[select.selectedIndex];
    var stokEl  = document.getElementById('stok-' + i);
    var minEl   = document.getElementById('min-' + i);
    var badgeEl = document.getElementById('badge-' + i);
    var satuanEl = document.getElementById('satuan-' + i);

    if (!opt || !opt.value) {
        stokEl.textContent = '-';
        minEl.textContent  = '-';
        badgeEl.innerHTML  = '';
        return;
    }
    var stok   = parseFloat(opt.dataset.stok) || 0;
    var min    = parseFloat(opt.dataset.min)  || 0;
    var satuan = opt.dataset.satuan || '';
    var satId  = opt.dataset.satuanId || '';

    stokEl.textContent = stok.toFixed(2) + ' ' + satuan;
    minEl.textContent  = min.toFixed(2)  + ' ' + satuan;
    stokEl.className   = stok <= min ? 'fw-semibold text-danger' : 'fw-semibold text-success';

    if (stok <= min) {
        badgeEl.innerHTML = '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Perlu Pembelian</span>';
    } else {
        badgeEl.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Stok Aman</span>';
    }

    if (satuanEl && satId) satuanEl.value = satId;
}
