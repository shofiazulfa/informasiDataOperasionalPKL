<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penggajian;
use App\Models\PenggunaanBBM;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKapal = Kapal::all()->count();
        $totalKaryawan = Karyawan::all()->count();

        // 🔹 ambil total pengeluaran per bulan
        $pengeluaranBulanan = Pengeluaran::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('SUM(kredit) as total')
        )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // 🔹 siapkan data untuk chart
        $labels = [];
        $data = [];

        foreach ($pengeluaranBulanan as $row) {
            $labels[] = date('M Y', mktime(0, 0, 0, $row->bulan, 1, $row->tahun));
            $data[] = (float) $row->total;
        }

        $totalPembelian = Pembelian::sum('total_jumlah');
        $totalBBM = PenggunaanBBM::sum('total_harga');
        $totalOperasional = BiayaOperasional::sum('jumlah_biaya');
        $totalGaji = Penggajian::sum('total_gaji_diterima');
        $totalPengeluaran = Pengeluaran::sum('kredit');

        $totalKeseluruhan = $totalPembelian + $totalBBM + $totalOperasional + $totalGaji + $totalPengeluaran;

        $klasifikasi = [
            'BBM' => $totalBBM,
            'Operasional Kapal' => $totalOperasional,
            'Logistik' => $totalPembelian,
            'SDM (Gaji)' => $totalGaji,
            'Kredit' => $totalPengeluaran
        ];

        // Tentukan prioritas tertinggi
        arsort($klasifikasi);
        $prioritasUtama = array_key_first($klasifikasi);

        return view('dashboard.index', compact('totalKapal', 'totalKaryawan', 'totalBBM', 'totalOperasional', 'totalGaji', 'totalPembelian', 'totalPengeluaran', 'labels', 'data', 'prioritasUtama', 'totalKeseluruhan'));
    }
}
