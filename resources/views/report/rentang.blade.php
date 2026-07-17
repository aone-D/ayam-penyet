@extends('layouts.app')

@section('title', 'Report Rentang Tanggal')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-header">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-0 text-white"><i class="fas fa-chart-line me-2"></i>Report Rentang Tanggal</h1>
                            <p class="mb-0 text-white-50">Laporan penjualan dan tren keuntungan periode tertentu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('report.rentang') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($penjualans->count() > 0)
        <div class="row mb-4">
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-warning">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-calculator fa-2x text-white"></i>
                        </div>
                        <h6 class="card-title text-white">Total HPP (Modal)</h6>
                        <h3 class="text-white fw-bold">@rupiah($totalHpp)</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-info">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-utensils fa-2x text-white"></i>
                        </div>
                        <h6 class="card-title text-white">Total Porsi Terjual</h6>
                        <h3 class="text-white fw-bold">{{ $totalPorsi }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Tren Keuntungan Harian</h5>
            </div>
            <div class="card-body">
                <canvas id="profitChart" height="100"></canvas>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Breakdown per Resep</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Resep</th>
                                <th>Qty Terjual</th>
                                <th>Pemasukan</th>
                                <th>HPP</th>
                                <th>Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resepBreakdown as $resep)
                                <tr>
                                    <td>{{ $resep['nama'] }}</td>
                                    <td>{{ $resep['qty'] }}</td>
                                    <td>@rupiah($resep['pemasukan'])</td>
                                    <td>@rupiah($resep['hpp'])</td>
                                    <td>@rupiah($resep['keuntungan'])</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center border-0 shadow-sm">
            <div class="py-4">
                <i class="fas fa-calendar-times fa-3x mb-3 text-info"></i>
                <h4>Tidak ada transaksi pada rentang tanggal ini</h4>
                <p class="mb-0">Pilih rentang tanggal lain untuk melihat laporan.</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dailyProfit = {{ json_encode($dailyProfit) }};

const labels = dailyProfit.map(item => item.tanggal);
const data = dailyProfit.map(item => item.keuntungan);

const ctx = document.getElementById('profitChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Keuntungan (Rp)',
            data: data,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.2)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Keuntungan: Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush

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
.bg-gradient-info {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
</style>
