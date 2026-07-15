@extends('layouts.app')
@section('title', 'Satuan')
@section('page_title', 'Kelola Satuan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-rulers me-2"></i>Kelola Satuan</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Satuan
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Nama Satuan</th><th>Singkatan</th><th>Digunakan Bahan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($satuans as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $s->nama_satuan }}</td>
                    <td><span class="badge bg-secondary">{{ $s->singkatan }}</span></td>
                    <td>{{ $s->bahan_baku_count }} bahan</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-action" onclick="editSatuan({{ $s->toJson() }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.satuan.destroy', $s) }}" class="d-inline"
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada satuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($satuans->hasPages())<div class="card-footer">{{ $satuans->links() }}</div>@endif
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.satuan.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Satuan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama Satuan *</label><input type="text" name="nama_satuan" class="form-control" placeholder="cth: Gram, Kilogram, Butir" required></div>
                <div class="mb-3"><label class="form-label">Singkatan *</label><input type="text" name="singkatan" class="form-control" placeholder="cth: gr, kg, btr" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div></div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit Satuan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama Satuan *</label><input type="text" name="nama_satuan" id="edit_nama" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Singkatan *</label><input type="text" name="singkatan" id="edit_sing" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div></div>
</div>
@endsection

@push('scripts')
<script>
function editSatuan(s) {
    document.getElementById('formEdit').action = '/admin/satuan/' + s.id;
    document.getElementById('edit_nama').value = s.nama_satuan;
    document.getElementById('edit_sing').value = s.singkatan;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush



