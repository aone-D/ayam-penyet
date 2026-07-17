@extends('layouts.app')

@section('title', 'Edit Resep')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Resep</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resep.update', $resep->id) }}" id="resepForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Resep</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $resep->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $resep->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <input type="number" step="0.01" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $resep->harga_jual) }}" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto (URL)</label>
                            <input type="text" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" value="{{ old('foto', $resep->foto) }}">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional. Masukkan URL gambar.</small>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Pilih Bahan Baku</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="tambahBahan()">
                                    <i class="fas fa-plus"></i> Tambah Bahan
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="bahan-container">
                                    <!-- Bahan rows will be added here -->
                                </div>
                                <div class="alert alert-info mt-3">
                                    <strong>Estimasi HPP:</strong> <span id="estimasi-hpp">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resep.index') }}" class="btn btn-secondary">Kembali</a>
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
const bahanBakus = {{ $bahanBakus->map(function($bahan) {
    return [
        'id' => $bahan->id,
        'nama' => $bahan->nama,
        'satuan_pakai' => $bahan->satuan_pakai,
        'harga_per_satuan_pakai' => (float) $bahan->harga_per_satuan_pakai
    ];
})->toJson() }};

const existingBahan = {{ $resep->bahanBakus->map(function($bahan) {
    return [
        'id' => $bahan->pivot->bahan_baku_id,
        'jumlah' => (float) $bahan->pivot->jumlah_dipakai
    ];
})->toJson() }};

let bahanCounter = 0;

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function tambahBahan(bahanId = '', jumlah = '') {
    bahanCounter++;
    const container = document.getElementById('bahan-container');
    
    let options = '<option value="">Pilih Bahan Baku</option>';
    bahanBakus.forEach(bahan => {
        options += `<option value="${bahan.id}" ${bahan.id == bahanId ? 'selected' : ''}>${bahan.nama} (${bahan.satuan_pakai})</option>`;
    });

    const row = document.createElement('div');
    row.className = 'row mb-2 bahan-row';
    row.id = `bahan-row-${bahanCounter}`;
    row.innerHTML = `
        <div class="col-md-5">
            <select class="form-select bahan-select" name="bahan_baku[${bahanCounter}][id]" required onchange="updateEstimasiHpp()">
                ${options}
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" step="0.01" class="form-control bahan-jumlah" name="bahan_baku[${bahanCounter}][jumlah]" placeholder="Jumlah" value="${jumlah}" required oninput="updateEstimasiHpp()">
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger btn-sm w-100" onclick="hapusBahan(${bahanCounter})">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
    `;
    
    container.appendChild(row);
}

function hapusBahan(id) {
    const row = document.getElementById(`bahan-row-${id}`);
    if (row) {
        row.remove();
        updateEstimasiHpp();
    }
}

function updateEstimasiHpp() {
    let totalHpp = 0;
    const rows = document.querySelectorAll('.bahan-row');
    
    rows.forEach(row => {
        const select = row.querySelector('.bahan-select');
        const jumlahInput = row.querySelector('.bahan-jumlah');
        
        if (select.value && jumlahInput.value) {
            const bahanId = parseInt(select.value);
            const jumlah = parseFloat(jumlahInput.value);
            const bahan = bahanBakus.find(b => b.id === bahanId);
            
            if (bahan) {
                totalHpp += jumlah * bahan.harga_per_satuan_pakai;
            }
        }
    });
    
    document.getElementById('estimasi-hpp').textContent = formatRupiah(totalHpp);
}

document.getElementById('resepForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.bahan-row');
    if (rows.length === 0) {
        e.preventDefault();
        alert('Harap pilih minimal satu bahan baku');
        return false;
    }
});

// Pre-fill existing ingredients
existingBahan.forEach(bahan => {
    tambahBahan(bahan.id, bahan.jumlah);
});

// If no existing ingredients, add one empty row
if (existingBahan.length === 0) {
    tambahBahan();
}

// Update HPP after pre-filling
updateEstimasiHpp();
</script>
@endpush
