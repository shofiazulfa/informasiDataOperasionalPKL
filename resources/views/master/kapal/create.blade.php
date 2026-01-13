@extends('layouts.app', ['title' => 'Create Kapal - Admin'])

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
                    <h4 class="mt-4 mb-4">Create Kapal</h4>
                    <form action="{{ route('master.kapal.store') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label for="nama_kapal" class="form-label">Nama Kapal</label>
                            <input type="text" name="nama_kapal" class="form-control" id="nama_kapal"
                                placeholder="Masukkan nama kapal">
                            @error('nama_kapal')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="jenis_kapal" class="form-label">Jenis Kapal</label>
                            <input type="text" name="jenis_kapal" class="form-control" id="jenis_kapal"
                                placeholder="Masukkan jenis kapal">
                            @error('jenis_kapal')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">Tanggal Operasional</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal"
                                placeholder="Masukkan tanggal">
                            @error('tanggal')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30"
                                placeholder="Masukkan keterangan"></textarea>
                            @error('keterangan')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="jumlah_biaya_view" class="form-label">Jumlah Biaya Operasional</label>
                            <input type="text" class="form-control" id="jumlah_biaya_view"
                                placeholder="Masukkan jumlah biaya">

                            @error('jumlah_biaya')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror

                            <input type="hidden" name="jumlah_biaya" id="jumlah_biaya">
                        </div>
                        <div class="col-12 mt-4 mb-4">
                            <button type="submit" class="btn btn-primary w-100">Buat</button>
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
        const biayaView = document.getElementById('jumlah_biaya_view');
        const biayaReal = document.getElementById('jumlah_biaya');

        biayaView.addEventListener('keyup', function () {
            // format tampilan
            biayaView.value = formatRupiah(this.value);

            // simpan nilai asli (hapus Rp dan titik)
            biayaReal.value = this.value.replace(/[^0-9]/g, '');
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