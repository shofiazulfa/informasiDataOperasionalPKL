<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use App\Models\Kapal;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KapalController extends Controller
{
    public function index()
    {
        $ships = BiayaOperasional::latest()->with('kapal')->get();
        return view('master.kapal.index', compact(var_name: 'ships'));
    }
    public function create()
    {
        return view('master.kapal.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_kapal' => 'required',
            'jenis_kapal' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jumlah_biaya' => 'required|numeric'
        ], [
            'nama_kapal.required' => 'Nama Kapal harus diisi!',
            'jenis_kapal.required' => 'Jenis Kapal harus diisi!',
            'tanggal.required' => 'Tanggal Operasional harus diisi!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'jumlah_biaya.required' => 'Jumlah Biaya Operasional harus diisi!',
            'jumlah_biaya.numeric' => 'Jumlah Biaya Operasional harus diisi dengan angka!'
        ]);

        DB::transaction(function () use ($request) {
            $kapal = Kapal::create([
                'nama_kapal' => $request->nama_kapal,
                'jenis_kapal' => $request->jenis_kapal
            ]);
                
            // cari atau buat periode otomatis
            $periode = PeriodeLaporan::firstOrCreate([
                'bulan' => date('m', strtotime($request->tanggal)),
                'tahun' => date('Y', strtotime($request->tanggal))
            ]);

            BiayaOperasional::create([
                'tanggal' => $request->tanggal,
                'periode_id' => $periode->id,
                'kapal_id' => $kapal->id,
                'keterangan' => $request->keterangan,
                'jumlah_biaya' => $request->jumlah_biaya
            ]);

        });

        return redirect()->route('master.kapal.index')
            ->with('success', 'Data berhasil dibuat!');
    }
    public function edit($id)
    {
        $kapal = Kapal::with('biayaOperasional')->findOrFail($id);
        return view('master.kapal.edit', compact('kapal'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kapal' => 'required',
            'jenis_kapal' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jumlah_biaya' => 'required|numeric'
        ], [
            'nama_kapal.required' => 'Nama Kapal harus diisi!',
            'jenis_kapal.required' => 'Jenis Kapal harus diisi!',
            'tanggal.required' => 'Tanggal Operasional harus diisi!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'jumlah_biaya.required' => 'Jumlah Biaya Operasional harus diisi!',
            'jumlah_biaya.numeric' => 'Jumlah Biaya Operasional harus diisi dengan angka!'
        ]);


        DB::transaction(function () use ($request, $id) {
            $kapal = Kapal::findOrFail($id);
            $biayaOperasional = BiayaOperasional::where('kapal_id', $kapal->id)->firstOrFail();
            
            // cari atau buat periode otomatis
            $periode = PeriodeLaporan::updateOrCreate([
                'bulan' => date('m', strtotime($request->tanggal)),
                'tahun' => date('Y', strtotime($request->tanggal))
            ]);

            $kapal->update([
                'nama_kapal' => $request->nama_kapal,
                'jenis_kapal' => $request->jenis_kapal,
            ]);

            $biayaOperasional->update([
                'tanggal' => $request->tanggal,
                'periode_id' => $periode->id,
                'keterangan' => $request->keterangan,
                'jumlah_biaya' => $request->jumlah_biaya
            ]);
        });

        return redirect()->route('master.kapal.index')
            ->with('success', 'Data berhasil diupdate.');
    }
    public function destroy($id)
    {
        $kapal = Kapal::findOrFail($id);
        $biayaOperasional = BiayaOperasional::where('kapal_id', $kapal->id)->firstOrFail();
        $periode = PeriodeLaporan::findOrFail($id);
        $kapal->delete();
        $biayaOperasional->delete();
        $periode->delete();
        return redirect()->route('master.kapal.index')
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

        $namaKapal = ['Tug Boat Aizu 03/Barge Zidni 01', 'Tug Boat Aizu 05/Barge Zidni 03', 'Tug Boat Aizu 07/Barge Zidni 06'];

        $i = 0;

        try {
            foreach ($rows as $row) {

                $tanggal = $this->convertTanggal($row[1]);
                $keterangan = '';
                $nama_kapal = $namaKapal[$i % count($namaKapal)];
                $jenis_kapal = 'Tugboat - Tongkang';
                $jumlah_biaya = $this->cleanNumber($row[3]);

                // ambil periode
                $periode_id = $this->getPeriodeId($tanggal);

                // cek kapal sudah ada atau belum
                $kapal = DB::table('kapal')
                    ->where('nama_kapal', $nama_kapal)
                    ->first();

                if ($kapal) {
                    $kapalId = $kapal->id;
                } else {
                    $kapalId = DB::table('kapal')->insertGetId([
                        'nama_kapal' => $nama_kapal,
                        'jenis_kapal' => $jenis_kapal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $i++;

                DB::table(table: 'biaya_operasional')->insert([
                    'periode_id' => $periode_id,
                    'tanggal' => $tanggal,
                    'kapal_id' => $kapalId,
                    'keterangan' => $keterangan,
                    'jumlah_biaya' => $jumlah_biaya,
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

    private function extractKapal($keterangan)
    {
        // hapus kata "Premi" atau "BBM" di depan
        $hasil = preg_replace('/^Premi\s*/i', '', $keterangan);

        return trim($hasil);
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
