@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Hero Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="card bg-gradient-primary text-white border-0 shadow">
                <div class="card-body py-5">
                    <div class="text-center">
                        <h1 class="display-4 fw-bold mb-3">🍽️ Awan Penyet App</h1>
                        <p class="lead mb-4">Sistem Manajemen Inventaris Restoran Terpadu</p>
                        <p class="mb-0">Kelola bahan baku, resep, penjualan, dan laporan dalam satu aplikasi yang mudah digunakan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4">Menu Cepat</h3>
        </div>
        <div class="col-md-3 mb-4">
            <a href="{{ route('bahan-baku.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-boxes fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Bahan Baku</h5>
                        <p class="card-text text-muted">Kelola stok dan inventaris bahan mentah</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="{{ route('resep.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-utensils fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Resep</h5>
                        <p class="card-text text-muted">Atur resep dan hitung HPP otomatis</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="{{ route('penjualan.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-cash-register fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Penjualan</h5>
                        <p class="card-text text-muted">Catat transaksi penjualan harian</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="{{ route('report.harian') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-chart-line fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Report</h5>
                        <p class="card-text text-muted">Lihat laporan dan analisis keuntungan</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Fitur Utama</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Manajemen Stok Real-time</h6>
                                    <p class="text-muted mb-0">Pantau stok bahan baku secara otomatis dengan notifikasi stok menipis</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-calculator text-success fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Perhitungan HPP Otomatis</h6>
                                    <p class="text-muted mb-0">Hitung Harga Pokok Produksi berdasarkan resep dan harga bahan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-history text-success fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Riwayat Transaksi Lengkap</h6>
                                    <p class="text-muted mb-0">Semua perubahan stok tercatat untuk audit dan pelacakan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-chart-bar text-success fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Laporan & Analisis</h6>
                                    <p class="text-muted mb-0">Laporan harian dan rentang tanggal dengan grafik visual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endsection