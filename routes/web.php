<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BBMController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanEmail;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', [AuthController::class, 'index'])->name(name: 'login');
Route::post('/login', [AuthController::class, 'login'])->name('actionlogin');

// dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name(name: 'dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/proses/operasionalBulanan', action: [RekapController::class, 'operasionalBulanan'])->name(name: 'proses.operasionalBulanan');
    Route::get('/admin/proses/totalKeseluruhan', action: [RekapController::class, 'totalKeseluruhan'])->name(name: 'proses.totalKeseluruhan');
    Route::get('/admin/proses/totalKeseluruhan/export', action: [RekapController::class, 'exportTotalKeseluruhanPdf'])->name(name: 'proses.totalKeseluruhan.pdf');
    Route::get('/admin/proses/operasionalBulanan/export/{periode}', [RekapController::class, 'exportOperasionalBulananPdf'])->name('proses.operasionalBulanan.pdf');

});

// kapal
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard/kapal', [KapalController::class, 'index'])->name(name: 'master.kapal.index');

    Route::get('/admin/dashboard/kapal/create', [KapalController::class, 'create'])->name('master.kapal.create');

    Route::post('/admin/dashboard/kapal/store', [KapalController::class, 'store'])->name('master.kapal.store');

    Route::get('/admin/dashboard/kapal/{id}/edit', [KapalController::class, 'edit'])->name('master.kapal.edit');

    Route::put('/admin/dashboard/kapal/{id}/edit', [KapalController::class, 'update'])->name('master.kapal.update');

    Route::delete('/admin/dashboard/kapal/{id}', [KapalController::class, 'destroy'])->name('master.kapal.destroy');

    Route::post('/admin/dashboard/kapal/import', action: [KapalController::class, 'import'])->name('master.kapal.import');
});

// karyawan
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard/karyawan', [KaryawanController::class, 'index'])->name(name: 'master.karyawan.index');

    Route::get('/admin/dashboard/karyawan/create', [KaryawanController::class, 'create'])->name('master.karyawan.create');

    Route::post('/admin/dashboard/karyawan/store', [KaryawanController::class, 'store'])->name('master.karyawan.store');

    Route::get('/admin/dashboard/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('master.karyawan.edit');

    Route::put('/admin/dashboard/karyawan/{id}/edit', [KaryawanController::class, 'update'])->name('master.karyawan.update');

    Route::delete('/admin/dashboard/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('master.karyawan.destroy');

    Route::post('/admin/dashboard/karyawan/import', action: [KaryawanController::class, 'import'])->name('master.karyawan.import');
});

// penggunaan bbm
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard/bbm', [BBMController::class, 'index'])->name(name: 'master.bbm.index');

    Route::get('/admin/dashboard/bbm/create', [BBMController::class, 'create'])->name('master.bbm.create');

    Route::post('/admin/dashboard/bbm/store', [BBMController::class, 'store'])->name('master.bbm.store');

    Route::get('/admin/dashboard/bbm/{id}/edit', [BBMController::class, 'edit'])->name('master.bbm.edit');

    Route::put('/admin/dashboard/bbm/{id}/edit', [BBMController::class, 'update'])->name('master.bbm.update');

    Route::delete('/admin/dashboard/bbm/{id}', [BBMController::class, 'destroy'])->name('master.bbm.destroy');

    Route::post('/admin/dashboard/bbm/import', action: [BBMController::class, 'import'])->name('master.bbm.import');
});

// pembelian
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard/pembelian', [PembelianController::class, 'index'])->name(name: 'master.pembelian.index');

    Route::get('/admin/dashboard/pembelian/create', [PembelianController::class, 'create'])->name('master.pembelian.create');

    Route::post('/admin/dashboard/pembelian/store', [PembelianController::class, 'store'])->name('master.pembelian.store');

    Route::get('/admin/dashboard/pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('master.pembelian.edit');

    Route::put('/admin/dashboard/pembelian/{id}/edit', [PembelianController::class, 'update'])->name('master.pembelian.update');

    Route::delete('/admin/dashboard/pembelian/{id}', [PembelianController::class, 'destroy'])->name('master.pembelian.destroy');

    Route::post('/admin/dashboard/pembelian/import', action: [PembelianController::class, 'import'])->name('master.pembelian.import');
});

// pengeluaran
Route::middleware(['auth'])->group(function (): void {
    Route::get('/admin/dashboard/pengeluaran', [PengeluaranController::class, 'index'])->name('master.pengeluaran.index');

    Route::get('/admin/dashboard/pengeluaran/create', [PengeluaranController::class, 'create'])->name('master.pengeluaran.create');

    Route::post('/admin/dashboard/pengeluaran/store', [PengeluaranController::class, 'store'])->name('master.pengeluaran.store');

    Route::get('/admin/dashboard/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('master.pengeluaran.edit');

    Route::put('/admin/dashboard/pengeluaran/{id}/edit', [PengeluaranController::class, 'update'])->name('master.pengeluaran.update');

    Route::delete('/admin/dashboard/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('master.pengeluaran.destroy');

    Route::post('/admin/dashboard/pengeluaran/import', action: [PengeluaranController::class, 'import'])->name('master.pengeluaran.import');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard/kirim', [LaporanEmail::class, 'index'])->name('master.kirim.index');
    Route::post('/admin/dashboard/kirim', [LaporanEmail::class, 'kirimLaporan'])->name('master.kirim.kirimLaporan');
});