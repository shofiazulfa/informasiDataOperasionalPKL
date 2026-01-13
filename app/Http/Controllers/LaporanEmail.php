<?php

namespace App\Http\Controllers;

use App\Mail\KirimLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class LaporanEmail extends Controller
{
    public function index()
    {
        return view('kirim.index');
    }

    public function kirimLaporan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'judul' => 'required',
            'keterangan' => 'required',
            'file_pdf' => 'required|mimes:pdf|max:5120'
        ]);

        // Upload file sementara
        $file = $request->file('file_pdf');
        $path = $file->store('temp');

        // Kirim email
        Mail::to($request->email)->send(
            new KirimLaporan(
                $request->judul,
                $request->keterangan,
                storage_path('app/' . $path)
            )
        );

        // Hapus file setelah dikirim
        Storage::delete($path);

        return back()->with('success', 'Laporan berhasil dikirim ke email');
    }
}
