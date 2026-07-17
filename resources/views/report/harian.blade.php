@extends('layouts.app')

@section('title', 'Report Harian')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Report Harian</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('report.harian') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()">
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
            <h4>Tidak ada transaksi pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</h4>
            <p>Pilih tanggal lain untuk melihat laporan.</p>
        </div>
    @endif
</div>
@endsection
