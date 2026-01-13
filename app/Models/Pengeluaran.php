<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'periode_id',
        'keterangan',
        'kredit'
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeLaporan::class, 'periode_id');
    }
}
