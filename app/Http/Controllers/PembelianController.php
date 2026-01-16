<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $buys = Pembelian::all();
        return view('master.pembelian.index', compact('buys'));
    }

    public function create()
    {
        return view('master.pembelian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'pcs' => 'required|numeric',
            'harga_satuan' => 'required|numeric',
            'total_jumlah' => 'required|numeric'
        ]);

        // cari atau buat periode otomatis
        $periode = PeriodeLaporan::firstOrCreate([
            'bulan' => date('m', strtotime($request->tanggal)),
            'tahun' => date('Y', strtotime($request->tanggal))
        ]);

        Pembelian::create([
            'tanggal' => $request->tanggal,
            'periode_id' => $periode->id,
            'keterangan' => $request->keterangan,
            'pcs' => $request->pcs,
            'harga_satuan' => $request->harga_satuan,
            'total_jumlah' => $request->total_jumlah
        ]);

        return redirect()->route('master.pembelian.index')
            ->with('success', 'Data berhasil dibuat!');
    }

    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        return view('master.pembelian.edit', compact('pembelian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'pcs' => 'required|numeric',
            'harga_satuan' => 'required|numeric',
            'total_jumlah' => 'required|numeric'
        ]);
        $pembelian = Pembelian::findOrFail($id);
        $periode = PeriodeLaporan::updateOrCreate([
            'bulan' => date('m', strtotime($request->tanggal)),
            'tahun' => date('Y', strtotime($request->tanggal))
        ]);
        $pembelian->update([
            'tanggal' => $request->tanggal,
            'periode_id' => $periode->id,
            'keterangan' => $request->keterangan,
            'pcs' => $request->pcs,
            'harga_satuan' => $request->harga_satuan,
            'total_jumlah' => $request->total_jumlah
        ]);
        return redirect()->route('master.pembelian.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $periode = PeriodeLaporan::findOrFail($id);
        $pembelian->delete();
        $periode->delete();
        return redirect()->route('master.pembelian.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $rows = array_map(fn($row) => str_getcsv($row, ';'), file($path));

        // buang header
        unset($rows[0]);

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                $tanggal = $this->convertTanggal($row[1]);
                $keterangan = trim($row[2]);
                $pcs = (int) $row[3];
                $harga_satuan = $this->cleanNumber($row[4]);

                // ambil periode
                $periode_id = $this->getPeriodeId($tanggal);

                DB::table('pembelian')->insert([
                    'periode_id' => $periode_id,
                    'tanggal' => $tanggal,
                    'keterangan' => $keterangan,
                    'pcs' => $pcs,
                    'harga_satuan' => $harga_satuan,
                    'total_jumlah' => ($harga_satuan * $pcs),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Import CSV berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

    }

    private function convertTanggal($tanggal)
    {
        $tanggal = trim(strtolower($tanggal));

        // Format: 06/01/2023
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $tanggal)) {
            return Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
        }

        // Format: 06-Jan-23
        if (preg_match('/^\d{2}-[A-Za-z]{3}-\d{2}$/', $tanggal)) {
            return Carbon::createFromFormat('d-M-y', $tanggal)->format('Y-m-d');
        }

        // Format: 06 januari 2026
        $bulanMap = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];

        $lower = strtolower($tanggal);

        foreach ($bulanMap as $nama => $angka) {
            if (str_contains($lower, $nama)) {
                [$tgl, $bln, $thn] = explode(' ', $lower);
                return "$thn-$angka-$tgl";
            }
        }

        return null; // jika format tidak dikenali
    }

    private function cleanNumber($value)
    {
        // Rp 1.250.000 -> 1250000
        return (float) preg_replace('/[^0-9]/', '', $value);
    }

    private function getPeriodeId($tanggal)
    {
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        $periode = PeriodeLaporan::firstOrCreate([
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        return $periode->id;
    }
}
