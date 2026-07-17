<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Resep;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('items.resep');

        $startDate = $request->get('start_date', now()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $query->whereBetween('tanggal', [$startDate, $endDate]);

        $penjualans = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        $totalPemasukan = $penjualans->sum('total_pemasukan');
        $totalHpp = $penjualans->sum('total_hpp');
        $totalKeuntungan = $penjualans->sum('total_keuntungan');

        return view('penjualan.index', compact('penjualans', 'totalPemasukan', 'totalHpp', 'totalKeuntungan', 'startDate', 'endDate'));
    }

    public function create()
    {
        $reseps = Resep::with('bahanBakus')->orderBy('nama', 'asc')->get();
        return view('penjualan.create', compact('reseps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.resep_id' => 'required|exists:reseps,id',
            'items.*.qty' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        // Check stock availability first
        $stockErrors = [];
        foreach ($validated['items'] as $item) {
            $resep = Resep::with('bahanBakus')->findOrFail($item['resep_id']);
            $qty = $item['qty'];

            foreach ($resep->bahanBakus as $bahan) {
                $kebutuhan = $qty * $bahan->pivot->jumlah_dipakai;
                if ($bahan->stok_saat_ini < $kebutuhan) {
                    $stockErrors[] = "Bahan {$bahan->nama} kurang. Butuh: {$kebutuhan} {$bahan->satuan_pakai}, Tersedia: {$bahan->stok_saat_ini} {$bahan->satuan_pakai}";
                }
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $stockErrors));
        }

        DB::transaction(function () use ($validated) {
            $penjualan = Penjualan::create([
                'tanggal' => $validated['tanggal'],
                'total_pemasukan' => 0,
                'total_hpp' => 0,
                'total_keuntungan' => 0,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $totalPemasukan = 0;
            $totalHpp = 0;

            foreach ($validated['items'] as $item) {
                $resep = Resep::with('bahanBakus')->findOrFail($item['resep_id']);
                $qty = $item['qty'];
                $hargaJualSaatItu = $resep->harga_jual;
                $hppSaatItu = $resep->hpp;
                $subtotal = $qty * $hargaJualSaatItu;

                PenjualanItem::create([
                    'penjualan_id' => $penjualan->id,
                    'resep_id' => $resep->id,
                    'qty' => $qty,
                    'harga_jual_saat_itu' => $hargaJualSaatItu,
                    'hpp_saat_itu' => $hppSaatItu,
                    'subtotal' => $subtotal,
                ]);

                $totalPemasukan += $subtotal;
                $totalHpp += $qty * $hppSaatItu;

                // Reduce stock
                foreach ($resep->bahanBakus as $bahan) {
                    $jumlahKeluar = $qty * $bahan->pivot->jumlah_dipakai;
                    $bahan->decrement('stok_saat_ini', $jumlahKeluar);

                    StokHistory::create([
                        'bahan_baku_id' => $bahan->id,
                        'tipe' => 'keluar',
                        'jumlah' => $jumlahKeluar,
                        'keterangan' => "Terpakai untuk penjualan #{$penjualan->id}",
                    ]);
                }
            }

            $totalKeuntungan = $totalPemasukan - $totalHpp;

            $penjualan->update([
                'total_pemasukan' => $totalPemasukan,
                'total_hpp' => $totalHpp,
                'total_keuntungan' => $totalKeuntungan,
            ]);
        });

        return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil dicatat');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with('items.resep')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::with('items.resep.bahanBakus')->findOrFail($id);

        DB::transaction(function () use ($penjualan) {
            // Restore stock
            foreach ($penjualan->items as $item) {
                $resep = $item->resep;
                $qty = $item->qty;

                foreach ($resep->bahanBakus as $bahan) {
                    $jumlahMasuk = $qty * $bahan->pivot->jumlah_dipakai;
                    $bahan->increment('stok_saat_ini', $jumlahMasuk);

                    StokHistory::create([
                        'bahan_baku_id' => $bahan->id,
                        'tipe' => 'masuk',
                        'jumlah' => $jumlahMasuk,
                        'keterangan' => "Pembatalan penjualan #{$penjualan->id}",
                    ]);
                }
            }

            $penjualan->delete();
        });

        return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil dihapus dan stok dikembalikan');
    }
}
