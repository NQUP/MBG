var barisIndex = 1;

function getSatuanOpts(selectedId) {
    if (selectedId === undefined) selectedId = null;
    return window.satuanData.map(function(s) {
        return '<option value="' + s.id + '" ' + (selectedId == s.id ? 'selected' : '') + '>' + s.singkatan + '</option>';
    }).join('');
}

function tambahBaris(bahan_baku_id, jumlah, satuan_id, nama) {
    if (bahan_baku_id === undefined) bahan_baku_id = '';
    if (jumlah === undefined) jumlah = '';
    if (satuan_id === undefined) satuan_id = '';
    var i = barisIndex++;
    var bahanOpts = '<option value="">-- Pilih Bahan --</option>' + window.bahanOptions;
    var html = '<tr id="baris-' + i + '">' +
        '<td><select name="items[' + i + '][bahan_baku_id]" class="form-select bahan-select" required>' + bahanOpts + '</select></td>' +
        '<td><input type="number" name="items[' + i + '][jumlah]" class="form-control" step="0.01" min="0.01" value="' + jumlah + '" required></td>' +
        '<td><select name="items[' + i + '][satuan_id]" class="form-select" required>' + getSatuanOpts(satuan_id) + '</select></td>' +
        '<td><input type="number" name="items[' + i + '][harga_satuan]" class="form-control" step="0.01" min="0" value="0"></td>' +
        '<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(' + i + ')"><i class="bi bi-trash"></i></button></td>' +
        '</tr>';
    document.getElementById('detailBody').insertAdjacentHTML('beforeend', html);
    if (bahan_baku_id) {
        var row = document.getElementById('baris-' + i);
        var sel = row.querySelector('.bahan-select');
        if (sel) sel.value = bahan_baku_id;
    }
    updateTotal();
}

function hapusBaris(i) {
    var el = document.getElementById('baris-' + i);
    if (el) el.remove();
    updateTotal();
}

function loadPrDetail(id) {
    var prInfo = document.getElementById('prInfo');
    if (!id) {
        prInfo.classList.add('d-none');
        return;
    }
    fetch('/api/barang-masuk/pr-detail/' + id)
        .then(function(r) { return r.json(); })
        .then(function(items) {
            if (!items.length) return;
            prInfo.classList.remove('d-none');
            var body = document.getElementById('detailBody');
            body.innerHTML = '';
            barisIndex = 0;
            items.forEach(function(item) {
                tambahBaris(item.bahan_baku_id, item.jumlah, item.satuan_id, item.nama_bahan);
            });
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat detail PR.' });
        });
}

function updateTotal() {
    document.getElementById('totalItem').textContent = document.querySelectorAll('#detailBody tr').length;
}