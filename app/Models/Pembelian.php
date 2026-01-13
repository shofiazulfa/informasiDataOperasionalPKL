<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'keterangan',
        'pcs',
        'harga_satuan',
        'total_jumlah'
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeLaporan::class, 'periode_id');
    }
}
