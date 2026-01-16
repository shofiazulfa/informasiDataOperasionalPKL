<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('periode_laporan', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->timestamps();
        });

        // 3. Tabel PEMBELIAN (Lap Pembelian)
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('periode_id')->constrained('periode_laporan')->cascadeOnDelete();
            $table->string('keterangan'); // Nama Barang
            $table->integer('pcs')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0); // Menggunakan decimal untuk uang
            $table->decimal('total_jumlah', 15, 2)->default(0); // Hasil kali pcs * harga
            $table->timestamps();
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('periode_id')->constrained('periode_laporan')->cascadeOnDelete();
            $table->string('keterangan'); // nama barang
            $table->integer('kredit')->default(0);
            $table->timestamps();
        });

        // 4. Tabel PENGGUNAAN BBM (Gabungan BBM Aizu & Umum)
        Schema::create('penggunaan_bbm', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('periode_id')->constrained('periode_laporan')->cascadeOnDelete();

            // Relasi ke tabel kapal. 
            // Jika NULL, berarti penggunaan BBM untuk kantor/umum (bukan kapal spesifik)
            $table->foreignId('kapal_id')->nullable()->constrained('kapal')->onDelete('set null');

            $table->string('keterangan');
            $table->decimal('jumlah_liter', 10, 2);
            $table->string('satuan')->default('Liter');
            $table->decimal('harga_per_liter', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });

        // 5. Tabel BIAYA OPERASIONAL (Gabungan Ops Aizu & Keseluruhan)
        Schema::create('biaya_operasional', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('periode_id')->constrained('periode_laporan')->cascadeOnDelete();

            // Relasi ke tabel kapal.
            // Jika diisi ID Kapal = Operasional Kapal (Aizu).
            // Jika NULL = Operasional Keseluruhan/Kantor.
            $table->foreignId('kapal_id')->nullable()->constrained('kapal')->onDelete('set null');

            $table->string('keterangan');
            $table->decimal('jumlah_biaya', 15, 2);
            $table->timestamps();
        });

        // 6. Tabel PENGGAJIAN (Gaji)
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('periode_id')->constrained('periode_laporan')->cascadeOnDelete();

            // Relasi ke tabel karyawan (Mengambil NIK, Nama, Jabatan dari sini)
            $table->foreignId('kapal_id')->constrained('kapal')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');

            $table->decimal('total_gaji_diterima', 15, 2); // Disimpan untuk history
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periode_laporan');
        Schema::dropIfExists('rekap_operasional');
        Schema::dropIfExists('penggajian');
        Schema::dropIfExists('biaya_operasional');
        Schema::dropIfExists('penggunaan_bbm');
        Schema::dropIfExists('pembelian');
    }
};
