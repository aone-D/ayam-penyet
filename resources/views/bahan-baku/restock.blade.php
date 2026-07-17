@extends('layouts.app')

@section('title', 'Restock Bahan Baku')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Restock Bahan Baku</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $bahanBaku->nama }}
                            </div>
                            <div class="col-md-6">
                                <strong>Stok Saat Ini:</strong> {{ number_format($bahanBaku->stok_saat_ini, 2) }} {{ $bahanBaku->satuan_pakai }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Harga Beli Saat Ini:</strong> @rupiah($bahanBaku->harga_beli) / {{ $bahanBaku->satuan_beli }}
                            </div>
                            <div class="col-md-6">
                                <strong>Konversi:</strong> 1 {{ $bahanBaku->satuan_beli }} = {{ number_format($bahanBaku->konversi, 2) }} {{ $bahanBaku->satuan_pakai }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('bahan-baku.processRestock', $bahanBaku->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="jumlah_beli" class="form-label">Jumlah Beli (dalam {{ $bahanBaku->satuan_beli }})</label>
                            <input type="number" step="0.01" class="form-control @error('jumlah_beli') is-invalid @enderror" id="jumlah_beli" name="jumlah_beli" required>
                            @error('jumlah_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: jika satuan_beli adalah kg, masukkan jumlah dalam kg</small>
                        </div>

                        <div class="mb-3">
                            <label for="harga_baru" class="form-label">Harga Baru (per {{ $bahanBaku->satuan_beli }})</label>
                            <input type="number" step="0.01" class="form-control @error('harga_baru') is-invalid @enderror" id="harga_baru" name="harga_baru" value="{{ old('harga_baru') }}">
                            @error('harga_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional. Kosongkan jika harga tidak berubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional. Contoh: Pembelian dari supplier X</small>
                        </div>

                        <div class="alert alert-secondary">
                            <strong>Estimasi:</strong>
                            <span id="estimasi_konversi">0</span> {{ $bahanBaku->satuan_pakai }} akan ditambahkan ke stok
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Restock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahBeli = document.getElementById('jumlah_beli');
    const estimasiKonversi = document.getElementById('estimasi_konversi');
    const konversi = {{ $bahanBaku->konversi }};

    function updateEstimasi() {
        const jumlah = parseFloat(jumlahBeli.value) || 0;
        const hasil = jumlah * konversi;
        estimasiKonversi.textContent = hasil.toFixed(2);
    }

    jumlahBeli.addEventListener('input', updateEstimasi);
});
</script>
@endpush
