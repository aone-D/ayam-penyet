@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Buat Resep Baru</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('menu.store') }}" method="POST">
                @csrf
                
                <!-- Field di luar template -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama_resep" class="form-label">Nama Resep</label>
                        <input type="text" class="form-control" id="nama_resep" name="nama_resep" required>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-primary" id="btnTambahBahan">
                            + Tambah Bahan
                        </button>
                    </div>
                </div>

                <!-- Template row untuk bahan -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelBahan">
                        <thead>
                            <tr>
                                <th>Bahan</th>
                                <th>Qty (gr)</th>
                                <th>Harga</th>
                                <th>Jumlah Porsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyBahan">
                            <!-- Row akan ditambahkan di sini -->
                        </tbody>
                    </table>
                </div>

                <!-- Template row (hidden) -->
                <template id="templateRow">
                    <tr class="bahan-row">
                        <td>
                            <input type="text" class="form-control" name="bahan[]" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="qty[]" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="harga[]" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="jumlah_porsi[]" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </template>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        Simpan Resep
                    </button>
                    <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnTambahBahan = document.getElementById('btnTambahBahan');
    const tbodyBahan = document.getElementById('tbodyBahan');
    const templateRow = document.getElementById('templateRow');
    let rowCount = 0;

    // Tambah row baru
    btnTambahBahan.addEventListener('click', function() {
        const clone = templateRow.content.cloneNode(true);
        const row = clone.querySelector('tr');
        
        // Add unique class for identification
        row.classList.add('row-' + rowCount);
        
        tbodyBahan.appendChild(clone);
        rowCount++;
    });

    // Hapus row
    tbodyBahan.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-hapus')) {
            const row = e.target.closest('tr');
            row.remove();
            rowCount--;
        }
    });
});
</script>
@endsection