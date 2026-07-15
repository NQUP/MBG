@extends('layouts.app')
@section('title', 'Stock Opname Detail')
@section('page_title', 'Detail Stock Opname')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-clipboard-check me-2"></i>{{ $stockOpname->kode_opname }}</h4>
        <small class="text-muted">{{ $stockOpname->tanggal_opname->format('d F Y') }} &bull; {{ $stockOpname->user->name }}</small>
    </div>
    <div class="d-flex gap-2">
        @if($stockOpname->status === 'draft')
        <form method="POST" action="{{ route('gudang.stock-opname.selesaikan', $stockOpname) }}"
              data-confirm="Yakin selesaikan opname? Stok akan disesuaikan dengan hasil fisik.">
            @csrf
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Selesaikan & Update Stok</button>
        </form>
        @else
        <span class="badge bg-success fs-6 py-2">âœ“ Selesai</span>
        @endif
        <a href="{{ route('gudang.stock-opname.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

@if($stockOpname->status === 'draft')
<form method="POST" action="{{ route('gudang.stock-opname.update-detail', $stockOpname) }}">
    @csrf
    <div class="card">
        <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
            <span>Isi Stok Fisik</span>
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Simpan Stok Fisik</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Nama Bahan</th><th>Satuan</th><th>Stok Sistem</th><th>Stok Fisik *</th><th>Selisih</th></tr></thead>
                <tbody>
                    @foreach($stockOpname->detail as $i => $d)
                    <input type="hidden" name="details[{{ $i }}][id]" value="{{ $d->id }}">
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                        <td>{{ $d->bahanBaku->satuan->singkatan }}</td>
                        <td>{{ number_format($d->stok_sistem, 2) }}</td>
                        <td style="width:140px">
                            <input type="number" name="details[{{ $i }}][stok_fisik]" class="form-control stok-fisik"
                                   step="0.01" min="0" value="{{ $d->stok_fisik }}"
                                   data-sistem="{{ $d->stok_sistem }}"
                                   onchange="hitungSelisih(this, {{ $i }})" required>
                        </td>
                        <td>
                            <span id="selisih-{{ $i }}" class="{{ ($d->selisih ?? 0) < 0 ? 'text-danger' : (($d->selisih ?? 0) > 0 ? 'text-success' : 'text-muted') }}">
                                {{ $d->selisih !== null ? number_format($d->selisih, 2) : '-' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Nama Bahan</th><th>Satuan</th><th>Stok Sistem</th><th>Stok Fisik</th><th>Selisih</th></tr></thead>
            <tbody>
                @foreach($stockOpname->detail as $i => $d)
                <tr class="{{ ($d->selisih ?? 0) != 0 ? 'table-warning' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                    <td>{{ $d->bahanBaku->satuan->singkatan }}</td>
                    <td>{{ number_format($d->stok_sistem, 2) }}</td>
                    <td>{{ number_format($d->stok_fisik, 2) }}</td>
                    <td class="{{ ($d->selisih ?? 0) < 0 ? 'text-danger fw-bold' : (($d->selisih ?? 0) > 0 ? 'text-success fw-bold' : 'text-muted') }}">
                        {{ number_format($d->selisih, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function hitungSelisih(input, i) {
    const fisik = parseFloat(input.value) || 0;
    const sistem = parseFloat(input.dataset.sistem) || 0;
    const selisih = fisik - sistem;
    const el = document.getElementById('selisih-' + i);
    el.textContent = selisih.toFixed(2);
    el.className = selisih < 0 ? 'text-danger fw-bold' : selisih > 0 ? 'text-success fw-bold' : 'text-muted';
}
</script>
@endpush


