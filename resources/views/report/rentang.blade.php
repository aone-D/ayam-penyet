@extends('layouts.app')

@section('title', 'Report Rentang Tanggal')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Report Rentang Tanggal</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('report.rentang') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($penjualans->count() > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total Pemasukan</h6>
                        <h3>@rupiah($totalPemasukan)</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total HPP (Modal)</h6>
                        <h3>@rupiah($totalHpp)</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total Keuntungan</h6>
                        <h3>@rupiah($totalKeuntungan)</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total Porsi Terjual</h6>
                        <h3>{{ $totalPorsi }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tren Keuntungan Harian</h5>
            </div>
            <div class="card-body">
                <canvas id="profitChart" height="100"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Breakdown per Resep</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
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
        <div class="alert alert-info text-center">
            <h4>Tidak ada transaksi pada rentang tanggal ini</h4>
            <p>Pilih rentang tanggal lain untuk melihat laporan.</p>
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
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
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
