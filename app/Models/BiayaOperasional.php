<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    use HasFactory;

    protected $table = 'biaya_operasional';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'kapal_id',
        'keterangan',
        'jumlah_biaya'
    ];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'kapal_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeLaporan::class, 'periode_id');
    }
}
