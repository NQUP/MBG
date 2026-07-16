function editKategori(k) {
    document.getElementById('formEdit').action = '/admin/kategori/' + k.id;
    document.getElementById('edit_nama').value = k.nama_kategori;
    document.getElementById('edit_ket').value = k.keterangan || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function editSatuan(s) {
    document.getElementById('formEdit').action = '/admin/satuan/' + s.id;
    document.getElementById('edit_nama').value = s.nama_satuan;
    document.getElementById('edit_sing').value = s.singkatan;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function editSupplier(s) {
    document.getElementById('formEdit').action = '/admin/suppliers/' + s.id;
    document.getElementById('edit_nama').value = s.nama_supplier;
    document.getElementById('edit_kontak').value = s.kontak || '';
    document.getElementById('edit_alamat').value = s.alamat || '';
    document.getElementById('edit_keterangan').value = s.keterangan || '';
    document.getElementById('edit_active').checked = s.is_active == 1;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
