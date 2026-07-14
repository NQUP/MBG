@extends('layouts.app')
@section('title', 'Tambah User')
@section('page_title', 'Tambah User')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-person-plus me-2"></i>Tambah User</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Username <span class="text-danger">*</span></label>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username', $user->username ?? '') }}" required>
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="petugas_gudang" {{ old('role', $user->role ?? '') === 'petugas_gudang' ? 'selected' : '' }}>Petugas Gudang</option>
            <option value="petugas_dapur" {{ old('role', $user->role ?? '') === 'petugas_dapur' ? 'selected' : '' }}>Petugas Dapur</option>
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               {{ isset($user) ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Akun Aktif</label>
        </div>
    </div>
</div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection