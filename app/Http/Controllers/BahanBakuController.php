<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort') && $request->sort === 'stok_asc') {
            $query->orderBy('stok_saat_ini', 'asc');
        } else {
            $query->orderBy('nama', 'asc');
        }

        $bahanBakus = $query->get();
        
        $bahanBakus->each(function ($bahan) {
            $bahan->status_stok = $bahan->status_stok;
        });

        return view('bahan-baku.index', compact('bahanBakus'));
    }

    public function create()
    {
        return view('bahan-baku.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'satuan_beli' => 'required|string',
            'satuan_pakai' => 'required|string',
            'konversi' => 'required|numeric|min:0.01',
            'harga_beli' => 'required|numeric|min:0',
            'stok_awal' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
        ]);

        $hargaPerSatuanPakai = $validated['harga_beli'] / $validated['konversi'];
        $stokSaatIni = $validated['stok_awal'] * $validated['konversi'];

        DB::transaction(function () use ($validated, $hargaPerSatuanPakai, $stokSaatIni) {
            $bahanBaku = BahanBaku::create([
                'nama' => $validated['nama'],
                'satuan_beli' => $validated['satuan_beli'],
                'satuan_pakai' => $validated['satuan_pakai'],
                'konversi' => $validated['konversi'],
                'harga_beli' => $validated['harga_beli'],
                'harga_per_satuan_pakai' => $hargaPerSatuanPakai,
                'stok_saat_ini' => $stokSaatIni,
                'stok_minimum' => $validated['stok_minimum'] ?? null,
            ]);

            StokHistory::create([
                'bahan_baku_id' => $bahanBaku->id,
                'tipe' => 'masuk',
                'jumlah' => $stokSaatIni,
                'keterangan' => 'Stok awal',
            ]);
        });

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        return view('bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'satuan_beli' => 'required|string',
            'satuan_pakai' => 'required|string',
            'konversi' => 'required|numeric|min:0.01',
            'harga_beli' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
        ]);

        $bahanBaku = BahanBaku::findOrFail($id);
        $hargaPerSatuanPakai = $validated['harga_beli'] / $validated['konversi'];

        $bahanBaku->update([
            'nama' => $validated['nama'],
            'satuan_beli' => $validated['satuan_beli'],
            'satuan_pakai' => $validated['satuan_pakai'],
            'konversi' => $validated['konversi'],
            'harga_beli' => $validated['harga_beli'],
            'harga_per_satuan_pakai' => $hargaPerSatuanPakai,
            'stok_minimum' => $validated['stok_minimum'] ?? null,
        ]);

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui');
    }

    public function restock($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        return view('bahan-baku.restock', compact('bahanBaku'));
    }

    public function processRestock(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_beli' => 'required|numeric|min:0.01',
            'harga_baru' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $bahanBaku = BahanBaku::findOrFail($id);
        $jumlahKonversi = $validated['jumlah_beli'] * $bahanBaku->konversi;

        DB::transaction(function () use ($bahanBaku, $validated, $jumlahKonversi) {
            $bahanBaku->increment('stok_saat_ini', $jumlahKonversi);

            if (isset($validated['harga_baru'])) {
                $hargaPerSatuanPakai = $validated['harga_baru'] / $bahanBaku->konversi;
                $bahanBaku->update([
                    'harga_beli' => $validated['harga_baru'],
                    'harga_per_satuan_pakai' => $hargaPerSatuanPakai,
                ]);
            }

            StokHistory::create([
                'bahan_baku_id' => $bahanBaku->id,
                'tipe' => 'masuk',
                'jumlah' => $jumlahKonversi,
                'keterangan' => $validated['keterangan'] ?? 'Restock',
            ]);
        });

        return redirect()->route('bahan-baku.index')->with('success', 'Stok berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        // Check if bahan baku is used in recipes
        // This assumes there's a recipe_bahan table or similar relationship
        // You may need to adjust this based on your actual recipe structure
        if ($bahanBaku->resep()->exists()) {
            return redirect()->route('bahan-baku.index')->with('error', 'Bahan baku tidak dapat dihapus karena sudah digunakan dalam resep');
        }

        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus');
    }
}
