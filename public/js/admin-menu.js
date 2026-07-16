function toggleStokFields() {
    var jenis = document.getElementById('jenisMenu').value;
    document.getElementById('stokFields').style.display = (jenis === 'minuman' || jenis === 'buah') ? 'block' : 'none';
}
