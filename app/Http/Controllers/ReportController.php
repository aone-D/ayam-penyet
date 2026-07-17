<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $penjualans = Penjualan::with('items.resep')
            ->where('tanggal', $tanggal)
            ->get();

        $totalPemasukan = $penjualans->sum('total_pemasukan');
        $totalHpp = $penjualans->sum('total_hpp');
        $totalKeuntungan = $penjualans->sum('total_keuntungan');
        $totalPorsi = $penjualans->sum(function ($penjualan) {
            return $penjualan->items->sum('qty');
        });

        // Breakdown per resep
        $resepBreakdown = [];
        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->items as $item) {
                $resepId = $item->resep_id;
                $resepNama = $item->resep->nama;

                if (!isset($resepBreakdown[$resepId])) {
                    $resepBreakdown[$resepId] = [
                        'nama' => $resepNama,
                        'qty' => 0,
                        'pemasukan' => 0,
                        'hpp' => 0,
                        'keuntungan' => 0,
                    ];
                }

                $resepBreakdown[$resepId]['qty'] += $item->qty;
                $resepBreakdown[$resepId]['pemasukan'] += $item->subtotal;
                $resepBreakdown[$resepId]['hpp'] += $item->qty * $item->hpp_saat_itu;
                $resepBreakdown[$resepId]['keuntungan'] += $item->subtotal - ($item->qty * $item->hpp_saat_itu);
            }
        }

        // Sort by keuntungan descending
        usort($resepBreakdown, function ($a, $b) {
            return $b['keuntungan'] <=> $a['keuntungan'];
        });

        return view('report.harian', compact(
            'tanggal',
            'totalPemasukan',
            'totalHpp',
            'totalKeuntungan',
            'totalPorsi',
            'resepBreakdown'
        ));
    }

    public function rentang(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', now()->subDays(7)->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());

        $penjualans = Penjualan::with('items.resep')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $penjualans->sum('total_pemasukan');
        $totalHpp = $penjualans->sum('total_hpp');
        $totalKeuntungan = $penjualans->sum('total_keuntungan');
        $totalPorsi = $penjualans->sum(function ($penjualan) {
            return $penjualan->items->sum('qty');
        });

        // Breakdown per resep
        $resepBreakdown = [];
        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->items as $item) {
                $resepId = $item->resep_id;
                $resepNama = $item->resep->nama;

                if (!isset($resepBreakdown[$resepId])) {
                    $resepBreakdown[$resepId] = [
                        'nama' => $resepNama,
                        'qty' => 0,
                        'pemasukan' => 0,
                        'hpp' => 0,
                        'keuntungan' => 0,
                    ];
                }

                $resepBreakdown[$resepId]['qty'] += $item->qty;
                $resepBreakdown[$resepId]['pemasukan'] += $item->subtotal;
                $resepBreakdown[$resepId]['hpp'] += $item->qty * $item->hpp_saat_itu;
                $resepBreakdown[$resepId]['keuntungan'] += $item->subtotal - ($item->qty * $item->hpp_saat_itu);
            }
        }

        // Sort by keuntungan descending
        usort($resepBreakdown, function ($a, $b) {
            return $b['keuntungan'] <=> $a['keuntungan'];
        });

        // Chart data - daily profit trend
        $dailyProfit = [];
        $period = new \DatePeriod(
            new \DateTime($tanggalMulai),
            new \DateInterval('P1D'),
            new \DateTime($tanggalSelesai)->modify('+1 day')
        );

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $profit = $penjualans
                ->where('tanggal', $dateStr)
                ->sum('total_keuntungan');
            
            $dailyProfit[] = [
                'tanggal' => $date->format('d/m/Y'),
                'keuntungan' => (float) $profit,
            ];
        }

        return view('report.rentang', compact(
            'tanggalMulai',
            'tanggalSelesai',
            'totalPemasukan',
            'totalHpp',
            'totalKeuntungan',
            'totalPorsi',
            'resepBreakdown',
            'dailyProfit'
        ));
    }
}
