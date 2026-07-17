@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-header">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-0 text-white"><i class="fas fa-cash-register me-2"></i>Daftar Penjualan</h1>
                            <p class="mb-0 text-white-50">Catat dan kelola transaksi penjualan harian</p>
                        </div>
                        <a href="{{ route('penjualan.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>Tambah Penjualan
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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('penjualan.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-primary">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                    </div>
                    <h6 class="card-title text-white">Total Pemasukan</h6>
                    <h3 class="text-white fw-bold">@rupiah($totalPemasukan)</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-warning">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-calculator fa-2x text-white"></i>
                    </div>
                    <h6 class="card-title text-white">Total HPP</h6>
                    <h3 class="text-white fw-bold">@rupiah($totalHpp)</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient-success">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <h6 class="card-title text-white">Total Keuntungan</h6>
                    <h3 class="text-white fw-bold">@rupiah($totalKeuntungan)</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah Item</th>
                            <th>Total Pemasukan</th>
                            <th>Keuntungan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $penjualan)
                            <tr>
                                <td>{{ $penjualan->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $penjualan->items->count() }}</td>
                                <td>@rupiah($penjualan->total_pemasukan)</td>
                                <td>@rupiah($penjualan->total_keuntungan)</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan.');">
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
                                        <i class="fas fa-cash-register fa-3x mb-3"></i>
                                        <p class="mb-0">Tidak ada data penjualan</p>
                                        <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Tambah Penjualan
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
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
</style>
