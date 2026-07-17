@extends('layouts.app')

@section('title', 'Edit Bahan Baku')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Bahan Baku</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bahan-baku.update', $bahanBaku->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Bahan Baku</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $bahanBaku->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="satuan_beli" class="form-label">Satuan Beli</label>
                                    <select class="form-select @error('satuan_beli') is-invalid @enderror" id="satuan_beli" name="satuan_beli" required>
                                        <option value="">Pilih Satuan Beli</option>
                                        <option value="kg" {{ old('satuan_beli', $bahanBaku->satuan_beli) == 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="liter" {{ old('satuan_beli', $bahanBaku->satuan_beli) == 'liter' ? 'selected' : '' }}>liter</option>
                                        <option value="pcs" {{ old('satuan_beli', $bahanBaku->satuan_beli) == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    </select>
                                    @error('satuan_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="penggunaan" class="form-label">Penggunaan (jumlah potong/porsi)</label>
                                    <input type="number" step="0.01" class="form-control @error('penggunaan') is-invalid @enderror" id="penggunaan" name="penggunaan" value="{{ old('penggunaan', $bahanBaku->penggunaan) }}" required>
                                    @error('penggunaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Contoh: 1kg ayam untuk 4 potong → isi 4</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="satuan_pakai" class="form-label">Satuan Pakai</label>
                            <select class="form-select @error('satuan_pakai') is-invalid @enderror" id="satuan_pakai" name="satuan_pakai" required>
                                <option value="">Pilih Satuan Pakai</option>
                                <option value="potong" {{ old('satuan_pakai', $bahanBaku->satuan_pakai) == 'potong' ? 'selected' : '' }}>potong</option>
                                <option value="gram" {{ old('satuan_pakai', $bahanBaku->satuan_pakai) == 'gram' ? 'selected' : '' }}>gram</option>
                                <option value="ml" {{ old('satuan_pakai', $bahanBaku->satuan_pakai) == 'ml' ? 'selected' : '' }}>ml</option>
                                <option value="pcs" {{ old('satuan_pakai', $bahanBaku->satuan_pakai) == 'pcs' ? 'selected' : '' }}>pcs</option>
                            </select>
                            @error('satuan_pakai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="konversi" class="form-label">Konversi (jumlah satuan_pakai dalam 1 satuan_beli)</label>
                            <input type="number" step="0.01" class="form-control @error('konversi') is-invalid @enderror" id="konversi" name="konversi" value="{{ old('konversi', $bahanBaku->konversi) }}" required>
                            @error('konversi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: 1 kg = 1000 gram → isi 1000</small>
                        </div>

                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli (per satuan_beli)</label>
                            <input type="number" step="0.01" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $bahanBaku->harga_beli) }}" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stok_minimum" class="form-label">Stok Minimum (dalam satuan_pakai)</label>
                            <input type="number" step="0.01" class="form-control @error('stok_minimum') is-invalid @enderror" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $bahanBaku->stok_minimum) }}">
                            @error('stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional. Untuk alert stok menipis.</small>
                        </div>

                        <div class="alert alert-info">
                            <strong>Stok Saat Ini:</strong> {{ number_format($bahanBaku->stok_saat_ini, 2) }} {{ $bahanBaku->satuan_pakai }}
                            <br>
                            <small class="text-muted">Stok saat ini tidak dapat diubah langsung dari halaman ini. Gunakan menu Restock untuk menambah stok.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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
    const satuanBeli = document.getElementById('satuan_beli');
    const satuanPakai = document.getElementById('satuan_pakai');
    const konversi = document.getElementById('konversi');

    const konversiMap = {
        'kg': { satuan: 'gram', nilai: 1000 },
        'liter': { satuan: 'ml', nilai: 1000 },
        'pcs': { satuan: 'pcs', nilai: 1 }
    };

    satuanBeli.addEventListener('change', function() {
        const selected = konversiMap[this.value];
        if (selected) {
            satuanPakai.value = selected.satuan;
            konversi.value = selected.nilai;
        }
    });
});
</script>
@endpush
