<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepController extends Controller
{
    public function index(Request $request)
    {
        $query = Resep::with('bahanBakus');

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $reseps = $query->orderBy('nama', 'asc')->get();

        $reseps->each(function ($resep) {
            $resep->hpp = $resep->hpp;
            $resep->margin = $resep->margin;
            $resep->margin_persen = $resep->margin_persen;
        });

        return view('resep.index', compact('reseps'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama', 'asc')->get();
        return view('resep.create', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'foto' => 'nullable|string',
            'bahan_baku' => 'required|array|min:1',
            'bahan_baku.*.id' => 'required|exists:bahan_bakus,id',
            'bahan_baku.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated) {
            $resep = Resep::create([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga_jual' => $validated['harga_jual'],
                'foto' => $validated['foto'] ?? null,
            ]);

            foreach ($validated['bahan_baku'] as $bahan) {
                $resep->bahanBakus()->attach($bahan['id'], [
                    'jumlah_dipakai' => $bahan['jumlah'],
                ]);
            }
        });

        return redirect()->route('resep.index')->with('success', 'Resep berhasil ditambahkan');
    }

    public function show($id)
    {
        $resep = Resep::with('bahanBakus')->findOrFail($id);
        
        $resep->hpp = $resep->hpp;
        $resep->margin = $resep->margin;
        $resep->margin_persen = $resep->margin_persen;

        return view('resep.show', compact('resep'));
    }

    public function edit($id)
    {
        $resep = Resep::with('bahanBakus')->findOrFail($id);
        $bahanBakus = BahanBaku::orderBy('nama', 'asc')->get();
        return view('resep.edit', compact('resep', 'bahanBakus'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'foto' => 'nullable|string',
            'bahan_baku' => 'required|array|min:1',
            'bahan_baku.*.id' => 'required|exists:bahan_bakus,id',
            'bahan_baku.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        $resep = Resep::findOrFail($id);

        DB::transaction(function () use ($resep, $validated) {
            $resep->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga_jual' => $validated['harga_jual'],
                'foto' => $validated['foto'] ?? null,
            ]);

            $pivotData = [];
            foreach ($validated['bahan_baku'] as $bahan) {
                $pivotData[$bahan['id']] = ['jumlah_dipakai' => $bahan['jumlah']];
            }

            $resep->bahanBakus()->sync($pivotData);
        });

        return redirect()->route('resep.index')->with('success', 'Resep berhasil diperbarui');
    }

    public function destroy($id)
    {
        $resep = Resep::findOrFail($id);

        // Check if recipe is used in sales transactions
        // This assumes there's a transactions or penjualan table
        // You may need to adjust this based on your actual sales structure
        if ($resep->transaksi()->exists()) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak dapat dihapus karena sudah digunakan dalam transaksi penjualan');
        }

        $resep->delete();

        return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus');
    }
}
