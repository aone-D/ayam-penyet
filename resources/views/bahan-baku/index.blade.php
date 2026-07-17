@extends('layouts.app')

@section('title', 'Daftar Bahan Baku')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-header">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-0 text-white"><i class="fas fa-boxes me-2"></i>Daftar Bahan Baku</h1>
                            <p class="mb-0 text-white-50">Kelola inventaris bahan mentah restoran</p>
                        </div>
                        <a href="{{ route('bahan-baku.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>Tambah Bahan Baku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('bahan-baku.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari bahan baku..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('bahan-baku.index', ['sort' => 'stok_asc']) }}" class="btn btn-outline-primary">
                                <i class="fas fa-sort-amount-down-alt me-2"></i>Urut Stok Terendah
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Stok Saat Ini</th>
                            <th>Harga per Satuan Pakai</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanBakus as $bahan)
                            <tr>
                                <td>{{ $bahan->nama }}</td>
                                <td>{{ number_format($bahan->stok_saat_ini, 2) }} {{ $bahan->satuan_pakai }}</td>
                                <td>@rupiah($bahan->harga_per_satuan_pakai)</td>
                                <td>
                                    @if($bahan->status_stok === 'menipis')
                                        <span class="badge bg-warning text-dark">Menipis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('bahan-baku.edit', $bahan->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('bahan-baku.restock', $bahan->id) }}" class="btn btn-sm btn-success" title="Restock">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                        <form action="{{ route('bahan-baku.destroy', $bahan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan baku ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-boxes fa-3x mb-3"></i>
                                        <p class="mb-0">Tidak ada data bahan baku</p>
                                        <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Tambah Bahan Baku
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.bg-gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
