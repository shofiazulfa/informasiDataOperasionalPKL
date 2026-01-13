<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kapal',
        'jenis_kapal'
    ];

    public function penggunaanBBM()
    {
        return $this->hasOne(PenggunaanBBM::class);
    }

    public function biayaOperasional()
    {
        return $this->hasOne(BiayaOperasional::class);
    }
}
