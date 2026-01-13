@extends('layouts.app', ['title' => 'Update Karyawan - Admin'])

@section('body')
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        @include('layouts.navbar')
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('layouts.sidebar')
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h4 class="mt-4 mb-4">Update Karyawan</h4>
                    <form action="{{ route('master.karyawan.update', $karyawan->id) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="nik" class="form-label">Nomor Induk Kependudukan</label>
                            <input type="text" name="nik" class="form-control" id="nik" placeholder="Masukkan NIK"
                                value="{{ $karyawan->nik }}">
                            @error('nik')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="nama" class="form-label">Nama Karyawan</label>
                            <input type="text" name="nama" class="form-control" id="nama"
                                placeholder="Masukkan nama karyawan" value="{{ $karyawan->nama }}">
                            @error('nama')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select id="jabatan" name="jabatan" class="form-select">
                                <option value="" disabled selected>Pilih jabatan</option>
                                <option value="NAHKODA" @if($karyawan->jabatan == 'NAHKODA') selected @endif>NAHKODA</option>
                                <option value="NAHKODA 2" @if($karyawan->jabatan == 'NAHKODA 2') selected @endif>NAHKODA 2
                                </option>
                                <option value="ANAK BUAH KAPAL" @if($karyawan->jabatan == 'ANAK BUAH KAPAL') selected @endif>
                                    ANAK BUAH KAPAL</option>
                                <option value="DIREKTUR" @if($karyawan->jabatan == 'DIREKTUR') selected @endif>DIREKTUR
                                </option>
                                <option value="DIREKTUR UTAMA" @if($karyawan->jabatan == 'DIREKTUR UTAMA') selected @endif>
                                    DIREKTUR UTAMA</option>
                                <option value="ADMINISTRASI" @if($karyawan->jabatan == 'ADMINISTRASI') selected @endif>
                                    ADMINISTERASI</option>
                                <option value="KEPALA KAMAR MESIN" @if($karyawan->jabatan == 'KEPALA KAMAR MESIN') selected
                                @endif>KEPALA KAMAR MESIN</option>
                                <option value="SUPERVISOR" @if($karyawan->jabatan == 'SUPERVISOR') selected @endif>SUPERVISOR
                                </option>
                                <option value="TEKNISI" @if($karyawan->jabatan == 'TEKNISI') selected @endif>TEKNISI</option>
                            </select>
                            @error('jabatan')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="status_ptkp" class="form-label">Status PTKP</label>
                            <select id="status_ptkp" name="status_ptkp" class="form-select">
                                <option value="" disabled selected>Pilih Status PTKP</option>
                                <option value="K/0" @if($karyawan->status_ptkp == 'K/0') selected @endif>K/0</option>
                                <option value="K/1" @if($karyawan->status_ptkp == 'K/1') selected @endif>K/1</option>
                                <option value="K/2" @if($karyawan->status_ptkp == 'K/2') selected @endif>K/2</option>
                                <option value="K/3" @if($karyawan->status_ptkp == 'K/3') selected @endif>K/3</option>
                                <option value="TK/0" @if($karyawan->status_ptkp == 'TK/0') selected @endif>TK/0</option>
                                <option value="TK/1" @if($karyawan->status_ptkp == 'TK/1') selected @endif>TK/1</option>
                                <option value="TK/2" @if($karyawan->status_ptkp == 'TK/2') selected @endif>TK/2</option>
                                <option value="TK/3" @if($karyawan->status_ptkp == 'TK/3') selected @endif>TK/3</option>
                            </select>
                            @error('status_ptkp')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">Tanggal Gajian</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal"
                                placeholder="Masukkan tanggal" value="{{ $karyawan->penggajian->tanggal }}">
                            @error('tanggal')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="total_gaji_diterima_view" class="form-label">Total Gaji yang Diterima</label>
                            <input type="text" class="form-control" id="total_gaji_diterima_view"
                                placeholder="Masukkan total gaji karyawan"
                                value="Rp {{ number_format($karyawan->penggajian->total_gaji_diterima, 0, ',', '.') }}">

                            @error('total_gaji_diterima')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror

                            <input type="hidden" name="total_gaji_diterima" id="total_gaji_diterima"
                                value="{{ $karyawan->penggajian->total_gaji_diterima }}">
                        </div>
                        <div class="col-12 mt-4 mb-4">
                            <button type="submit" class="btn btn-primary w-100">Edit</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/datatables-simple-demo.js') }}"></script>

    <script>
        const totalGajiDiterimaView = document.getElementById('total_gaji_diterima_view');
        const totalGajiDiterimaReal = document.getElementById('total_gaji_diterima');

        totalGajiDiterimaView.addEventListener('keyup', function () {
            totalGajiDiterimaView.value = formatRupiah(this.value);
            totalGajiDiterimaReal.value = this.value.replace(/[^0-9]/g, '');
        });

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return rupiah ? 'Rp ' + rupiah : '';
        }
    </script>
@endsection