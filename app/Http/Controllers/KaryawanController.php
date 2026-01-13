<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Penggajian;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $employees = Karyawan::with('penggajian')->get();
        return view('master.karyawan.index', compact('employees'));
    }

    public function create()
    {
        return view('master.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique|numeric|min_digits:16',
            'nama' => 'required',
            'jabatan' => 'required',
            'status_ptkp' => 'required',
            'tanggal' => 'required',
            'total_gaji_diterima' => 'required|numeric'
        ], [
            'nik.required' => 'NIK perlu diisi!',
            'nama.required' => 'Nama harus diisi!',
            'jabatan.required' => 'Jabatan harus diisi!',
            'status_ptkp.required' => 'Status PTKP harus diisi!',
            'tanggal.required' => 'Tanggal harus diisi!',
            'total_gaji_diterima.required' => 'Total gaji harus diisi!',
            'nik.unique' => 'NIK sudah diisi!',
            'nik.numeric' => 'NIK tidak boleh berisi huruf!',
            'total_gaji_diterima.numeric' => 'Total gaji tidak boleh berisi huruf!',
            'nik.min_digits' => 'NIK harus berisi minimal 16 digit!'
        ]);

        DB::transaction(function () use ($request) {
            // cari atau buat periode otomatis
            $periode = PeriodeLaporan::firstOrCreate([
                'bulan' => date('m', strtotime($request->tanggal)),
                'tahun' => date('Y', strtotime($request->tanggal))
            ]);
            $karyawan = Karyawan::create([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'status_ptkp' => $request->status_ptkp
            ]);

            Penggajian::create([
                'tanggal' => $request->tanggal,
                'periode_id' => $periode->id,
                'karyawan_id' => $karyawan->id,
                'total_gaji_diterima' => $request->total_gaji_diterima
            ]);
        });

        return redirect()->route('master.karyawan.index')
            ->with('success', 'Data berhasil dibuat!');
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with('penggajian')->firstOrFail();
        return view('master.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|numeric|min_digits:16',
            'nama' => 'required',
            'jabatan' => 'required',
            'status_ptkp' => 'required',
            'tanggal' => 'required',
            'total_gaji_diterima' => 'required|numeric'
        ], [
            'nik.required' => 'NIK perlu diisi!',
            'nama.required' => 'Nama harus diisi!',
            'jabatan.required' => 'Jabatan harus diisi!',
            'status_ptkp.required' => 'Status PTKP harus diisi!',
            'total_gaji_diterima.required' => 'Total gaji harus diisi!',
            'tanggal.required' => 'Tanggal harus diisi!',
            'nik.numeric' => 'NIK tidak boleh berisi huruf!',
            'total_gaji_diterima.numeric' => 'Total gaji tidak boleh berisi huruf!',
            'nik.min_digits' => 'NIK harus berisi minimal 16 digit!'
        ]);

        DB::transaction(function () use ($request, $id) {
            $karyawan = Karyawan::findOrFail($id);
            $penggajian = Penggajian::where('karyawan_id', $karyawan->id)->firstOrFail();
            $karyawan->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'status_ptkp' => $request->status_ptkp
            ]);
            $penggajian->update([
                'tanggal' => $request->tanggal,
                'karyawan_id' => $karyawan->id,
                'total_gaji_diterima' => $request->total_gaji_diterima
            ]);
        });

        return redirect()->route('master.karyawan.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $penggajian = Penggajian::where('karyawan_id', $karyawan->id)->firstOrFail();
        $karyawan->delete();
        $penggajian->delete();
        return redirect()->route('master.karyawan.index')
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
                $nama = trim($row[2]);
                $nik = trim($row[3]);
                $jabatan = trim($row[4]);
                $status_ptkp = trim($row[5]);
                $gaji = $this->cleanNumber($row[7]);

                // ambil periode
                $periode_id = $this->getPeriodeId($tanggal);

                if ($status_ptkp == '') {
                    $status_ptkp = 'K/0';
                }

                if ($status_ptkp == 'K1') {
                    $status_ptkp = 'K/1';
                } else if ($status_ptkp == 'K2') {
                    $status_ptkp = 'K/2';
                } else if ($status_ptkp == 'K3') {
                    $status_ptkp = 'K/3';
                } else if ($status_ptkp == 'TK') {
                    $status_ptkp == 'TK/0';
                }

                if ($nik == '-' || $jabatan == '-' || $status_ptkp == 0) {
                    $nik = '0';
                    $jabatan = '';
                    $status_ptkp = 'K/0';
                }

                $karyawanId = DB::table('karyawan')->insertGetId([
                    'nama' => $nama,
                    'nik' => $nik,
                    'jabatan' => $jabatan,
                    'status_ptkp' => $status_ptkp,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // simpan penggajian
                DB::table('penggajian')->insert([
                    'periode_id' => $periode_id,
                    'tanggal' => $tanggal,
                    'karyawan_id' => $karyawanId,
                    'total_gaji_diterima' => $gaji,
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
