<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'karyawan_id',
        'total_gaji_diterima'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeLaporan::class, 'periode_id');
    }
}
