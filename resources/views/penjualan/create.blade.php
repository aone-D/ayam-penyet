@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Penjualan Baru</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('penjualan.store') }}" id="penjualanForm">
                        @csrf

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Item Penjualan</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="tambahItem()">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="item-container">
                                    <!-- Item rows will be added here -->
                                </div>
                                <div class="alert alert-info mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Total Item:</strong> <span id="total-item">0</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Total:</strong> <span id="total-harga">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
const reseps = {{ $reseps->map(function($resep) {
    return [
        'id' => $resep->id,
        'nama' => $resep->nama,
        'harga_jual' => (float) $resep->harga_jual,
        'hpp' => (float) $resep->hpp
    ];
})->toJson() }};

let itemCounter = 0;

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function tambahItem(resepId = '', qty = '') {
    itemCounter++;
    const container = document.getElementById('item-container');
    
    let options = '<option value="">Pilih Resep</option>';
    reseps.forEach(resep => {
        options += `<option value="${resep.id}" data-harga="${resep.harga_jual}" ${resep.id == resepId ? 'selected' : ''}>${resep.nama} - @rupiah(${resep.harga_jual})</option>`;
    });

    const row = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.id = `item-row-${itemCounter}`;
    row.innerHTML = `
        <div class="col-md-5">
            <select class="form-select item-resep" name="items[${itemCounter}][resep_id]" required onchange="updateTotal()">
                ${options}
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control item-qty" name="items[${itemCounter}][qty]" placeholder="Qty" value="${qty}" min="1" required oninput="updateTotal()">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control item-subtotal" id="subtotal-${itemCounter}" value="Rp 0" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm w-100" onclick="hapusItem(${itemCounter})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    updateTotal();
}

function hapusItem(id) {
    const row = document.getElementById(`item-row-${id}`);
    if (row) {
        row.remove();
        updateTotal();
    }
}

function updateTotal() {
    let totalHarga = 0;
    let totalItem = 0;
    const rows = document.querySelectorAll('.item-row');
    
    rows.forEach(row => {
        const select = row.querySelector('.item-resep');
        const qtyInput = row.querySelector('.item-qty');
        const subtotalInput = row.querySelector('.item-subtotal');
        
        if (select.value && qtyInput.value) {
            const resepId = parseInt(select.value);
            const qty = parseInt(qtyInput.value);
            const resep = reseps.find(r => r.id === resepId);
            
            if (resep) {
                const subtotal = qty * resep.harga_jual;
                totalHarga += subtotal;
                totalItem += qty;
                subtotalInput.value = formatRupiah(subtotal);
            } else {
                subtotalInput.value = 'Rp 0';
            }
        } else {
            subtotalInput.value = 'Rp 0';
        }
    });
    
    document.getElementById('total-item').textContent = totalItem;
    document.getElementById('total-harga').textContent = formatRupiah(totalHarga);
}

document.getElementById('penjualanForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length === 0) {
        e.preventDefault();
        alert('Harap tambahkan minimal satu item');
        return false;
    }
    
    let hasValidItem = false;
    rows.forEach(row => {
        const select = row.querySelector('.item-resep');
        const qtyInput = row.querySelector('.item-qty');
        if (select.value && qtyInput.value) {
            hasValidItem = true;
        }
    });
    
    if (!hasValidItem) {
        e.preventDefault();
        alert('Harap lengkapi minimal satu item');
        return false;
    }
});

// Add one empty row on load
tambahItem();
</script>
@endpush
