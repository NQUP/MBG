document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('sliderSiswa');
    const previewSiswa = document.getElementById('previewSiswa');
    if (slider && previewSiswa) {
        slider.addEventListener('input', function() {
            previewSiswa.textContent = this.value;
            document.querySelectorAll('#previewTable [data-jumlah]').forEach(function(row) {
                const jumlah = parseFloat(row.dataset.jumlah) * parseInt(this.value);
                row.querySelector('.preview-total').textContent = jumlah.toFixed(2) + ' ' + row.querySelector('.preview-total').textContent.split(' ').pop();
            }.bind(this));
        });
    }
});
