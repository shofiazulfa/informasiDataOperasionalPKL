<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeLaporan extends Model
{
    use HasFactory;

    protected $table = 'periode_laporan';
    protected $primaryKey = 'id';

    protected $fillable = ['bulan', 'tahun'];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'periode_id');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'periode_id');
    }

    public function operasional()
    {
        return $this->hasMany(BiayaOperasional::class, 'periode_id');
    }

    public function bbm()
    {
        return $this->hasMany(PenggunaanBBM::class, 'periode_id');
    }

    public function gaji()
    {
        return $this->hasMany(Penggajian::class, 'periode_id');
    }
}
