@extends('layouts.app')
@section('title', 'Kategori Bahan')
@section('page_title', 'Kategori Bahan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-tags me-2"></i>Kategori Bahan</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Nama Kategori</th><th>Keterangan</th><th>Jumlah Bahan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($kategoris as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                    <td>{{ $k->keterangan ?? '-' }}</td>
                    <td><span class="badge bg-secondary">{{ $k->bahan_baku_count }} bahan</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-action" onclick="editKategori({{ $k->toJson() }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.kategori.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama Kategori *</label><input type="text" name="nama_kategori" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div></div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama Kategori *</label><input type="text" name="nama_kategori" id="edit_nama" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" id="edit_ket" class="form-control" rows="2"></textarea></div>
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
    <script src="/js/admin-crud.js"></script>
@endpush




