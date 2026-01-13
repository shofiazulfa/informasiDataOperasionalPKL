<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tabel KAPAL (Master Aset)
        Schema::create('kapal', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nama_kapal'); // Contoh: Aizu
            $table->string('jenis_kapal')->nullable(); // Contoh: Tugboat, Tongkang
            $table->timestamps();
        });

        // 2. Tabel KARYAWAN (Master Pegawai)
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nik'); // NIK tidak boleh sama
            $table->string('nama');
            $table->string('jabatan');
            $table->string('status_ptkp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('kapal');
    }
};