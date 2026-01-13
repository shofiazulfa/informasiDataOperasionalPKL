<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penggajian;
use App\Models\PenggunaanBBM;
use App\Models\PeriodeLaporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function operasionalBulanan()
    {
        // Rekap Pembelian per bulan
        $pembelianBulanan = DB::table('pembelian')
            ->select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("YEAR(tanggal) as tahun"),
                DB::raw("SUM(total_jumlah) as total")
            )
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Rekap BBM per bulan
        $bbmBulanan = DB::table('penggunaan_bbm')
            ->select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("YEAR(tanggal) as tahun"),
                DB::raw("SUM(total_harga) as total")
            )
            ->groupBy('bulan', 'tahun')
            ->get();

        // Rekap Operasional per bulan
        $operasionalBulanan = DB::table('biaya_operasional')
            ->select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("YEAR(tanggal) as tahun"),
                DB::raw("SUM(jumlah_biaya) as total")
            )
            ->groupBy('bulan', 'tahun')
            ->get();

        // Rekap Gaji
        $gajiBulanan = DB::table('penggajian')
            ->select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("YEAR(tanggal) as tahun"),
                DB::raw("SUM(total_gaji_diterima) as total")
            )
            ->groupBy('bulan', 'tahun')
            ->get();

        // Rekap Pengeluaran
        $pengeluaranBulanan = DB::table('pengeluaran')
            ->select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("YEAR(tanggal) as tahun"),
                DB::raw("SUM(kredit) as total")
            )
            ->groupBy('bulan', 'tahun')
            ->get();

        $periodeList = PeriodeLaporan::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('proses.operasionalBulanan', compact('pembelianBulanan', 'bbmBulanan', 'operasionalBulanan', 'gajiBulanan', 'pengeluaranBulanan', 'periodeList'));
    }

    public function totalKeseluruhan()
    {
        $totalPembelian = Pembelian::sum('total_jumlah');
        $totalBBM = PenggunaanBBM::sum('total_harga');
        $totalOperasional = BiayaOperasional::sum('jumlah_biaya');
        $totalGajiKaryawan = Penggajian::sum('total_gaji_diterima');
        $totalPengeluaran = Pengeluaran::sum('kredit');

        $totalKeseluruhan = $totalPembelian + $totalBBM + $totalOperasional + $totalGajiKaryawan + $totalPengeluaran;

        return view('proses.totalKeseluruhan', compact('totalKeseluruhan', 'totalPembelian', 'totalBBM', 'totalOperasional', 'totalGajiKaryawan', 'totalPengeluaran'));
    }

    public function exportTotalKeseluruhanPdf()
    {
        $totalPembelian = Pembelian::sum('total_jumlah');
        $totalBBM = PenggunaanBBM::sum('total_harga');
        $totalOperasional = BiayaOperasional::sum('jumlah_biaya');
        $totalGajiKaryawan = Penggajian::sum('total_gaji_diterima');
        $totalPengeluaran = Pengeluaran::sum('kredit');

        $totalKeseluruhan =
            $totalPembelian +
            $totalBBM +
            $totalOperasional +
            $totalGajiKaryawan +
            $totalPengeluaran;

        $pdf = Pdf::loadView('export.pdf.rekap-total-keseluruhan-pdf', compact(
            'totalPembelian',
            'totalBBM',
            'totalOperasional',
            'totalGajiKaryawan',
            'totalPengeluaran',
            'totalKeseluruhan'
        ));

        return $pdf->download('total_keseluruhan_PT_ZIDNI.pdf');
    }

    public function exportOperasionalBulananPdf($periode_id)
    {
        $periode = PeriodeLaporan::findOrFail($periode_id);

        // Pembelian
        $pembelian = Pembelian::where('periode_id', $periode_id)->get();

        // Pengeluaran
        $pengeluaran = Pengeluaran::where('periode_id', $periode_id)->get();

        // BBM + Kapal
        $bbm = PenggunaanBBM::leftJoin('kapal', 'kapal.id', '=', 'penggunaan_bbm.kapal_id')
            ->select('penggunaan_bbm.*', 'kapal.nama_kapal')
            ->where('penggunaan_bbm.periode_id', $periode_id)
            ->get();

        // Operasional
        $operasional = BiayaOperasional::where('periode_id', $periode_id)->get();

        $data = [
            'pembelian' => $pembelian,
            'pengeluaran' => $pengeluaran,
            'bbm' => $bbm,
            'operasional' => $operasional,
            'totalPembelian' => $pembelian->sum('total_jumlah'),
            'totalPengeluaran' => $pengeluaran->sum('kredit'),
            'totalBBM' => $bbm->sum('total_harga'),
            'totalOperasional' => $operasional->sum('jumlah_biaya'),
            'namaBulan' => Carbon::create()->month($periode->bulan)->translatedFormat('F'),
            'tahun' => $periode->tahun
        ];

        $pdf = PDF::loadView('export.pdf.rekap-bulanan-pdf', $data)->setPaper('A4', 'portrait');

        return $pdf->download("laporan-operasional-pt-zidni-daya-nusantara-{$periode->bulan}-{$periode->tahun}.pdf");
    }
}
