@extends('layouts.app')
@section('title', 'Supplier')
@section('page_title', 'Kelola Supplier')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-truck me-2"></i>Kelola Supplier</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Supplier
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Nama Supplier</th><th>Kontak</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->kontak ?? '-' }}</td>
                    <td>{{ Str::limit($supplier->alamat, 40) ?? '-' }}</td>
                    <td><span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $supplier->is_active ? 'Aktif' : 'Non-aktif' }}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-action" onclick="editSupplier({{ $supplier->toJson() }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada supplier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())<div class="card-footer">{{ $suppliers->links() }}</div>@endif
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.suppliers.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Supplier *</label><input type="text" name="nama_supplier" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kontak</label><input type="text" name="kontak" class="form-control" placeholder="No. HP / Telp"></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked><label class="form-check-label">Aktif</label></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Supplier *</label><input type="text" name="nama_supplier" id="edit_nama" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kontak</label><input type="text" name="kontak" id="edit_kontak" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" id="edit_alamat" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" id="edit_active" value="1"><label class="form-check-label">Aktif</label></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/js/admin-crud.js"></script>
@endpush




