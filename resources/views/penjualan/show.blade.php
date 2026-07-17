@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Penjualan #{{ $penjualan->id }}</h4>
                    <div>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal:</strong> {{ $penjualan->tanggal->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Jumlah Item:</strong> {{ $penjualan->items->count() }}
                        </div>
                    </div>

                    @if($penjualan->catatan)
                        <div class="mb-3">
                            <strong>Catatan:</strong> {{ $penjualan->catatan }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Pemasukan</h6>
                                    <h4>@rupiah($penjualan->total_pemasukan)</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total HPP</h6>
                                    <h4>@rupiah($penjualan->total_hpp)</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Keuntungan</h6>
                                    <h4>@rupiah($penjualan->total_keuntungan)</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Item Penjualan</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Resep</th>
                                    <th>Qty</th>
                                    <th>Harga Jual Saat Itu</th>
                                    <th>HPP Saat Itu</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualan->items as $item)
                                    <tr>
                                        <td>{{ $item->resep->nama }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>@rupiah($item->harga_jual_saat_itu)</td>
                                        <td>@rupiah($item->hpp_saat_itu)</td>
                                        <td>@rupiah($item->subtotal)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="4" class="text-end">Total</th>
                                    <th>@rupiah($penjualan->total_pemasukan)</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
