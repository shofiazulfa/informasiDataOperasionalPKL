<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::all();
        return view('master.pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        return view('master.pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'kredit' => 'required|numeric'
        ]);

        // cari atau buat periode otomatis
        $periode = PeriodeLaporan::firstOrCreate([
            'bulan' => date('m', strtotime($request->tanggal)),
            'tahun' => date('Y', strtotime($request->tanggal))
        ]);

        Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'periode_id' => $periode->id,
            'keterangan' => $request->keterangan,
            'kredit' => $request->kredit
        ]);

        return redirect()->route('master.pengeluaran.index')
            ->with('success', 'Data berhasil dibuat!');
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('master.pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'kredit' => 'required|numeric'
        ]);
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'kredit' => $request->kredit
        ]);
        return redirect()->route('master.pengeluaran.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();
        return redirect()->route('master.pengeluaran.index')
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
                $kredit = $this->cleanNumber($row[3]);

                // ambil periode
                $periode_id = $this->getPeriodeId($tanggal);

                DB::table(table: 'pengeluaran')->insert([
                    'periode_id' => $periode_id,
                    'tanggal' => $tanggal,
                    'keterangan' => $keterangan,
                    'kredit' => $kredit,
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
