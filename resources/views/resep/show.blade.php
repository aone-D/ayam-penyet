@extends('layouts.app')

@section('title', 'Detail Resep')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $resep->nama }}</h4>
                    <div>
                        <a href="{{ route('resep.edit', $resep->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('resep.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($resep->foto)
                        <div class="text-center mb-3">
                            <img src="{{ $resep->foto }}" alt="{{ $resep->nama }}" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    @endif

                    @if($resep->deskripsi)
                        <div class="mb-3">
                            <h5>Deskripsi</h5>
                            <p>{{ $resep->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">HPP</h6>
                                    <h4 class="text-primary">@rupiah($resep->hpp)</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Harga Jual</h6>
                                    <h4 class="text-success">@rupiah($resep->harga_jual)</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Margin</h6>
                                    <h4 class="text-info">@rupiah($resep->margin)</h4>
                                    <small class="text-muted">({{ number_format($resep->margin_persen, 2) }}%)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Breakdown Bahan</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Jumlah Dipakai</th>
                                    <th>Harga per Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resep->bahanBakus as $bahan)
                                    <tr>
                                        <td>{{ $bahan->nama }}</td>
                                        <td>{{ number_format($bahan->pivot->jumlah_dipakai, 2) }} {{ $bahan->satuan_pakai }}</td>
                                        <td>@rupiah($bahan->harga_per_satuan_pakai) / {{ $bahan->satuan_pakai }}</td>
                                        <td>@rupiah($bahan->pivot->jumlah_dipakai * $bahan->harga_per_satuan_pakai)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="3" class="text-end">Total HPP</th>
                                    <th>@rupiah($resep->hpp)</th>
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
