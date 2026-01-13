<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanBBM extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_bbm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'kapal_id',
        'keterangan',
        'jumlah_liter',
        'satuan',
        'harga_per_liter',
        'total_harga'
    ];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }
}
