@extends('layouts.app', ['title' => 'Kirim data ke pimpinan - Admin'])

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
                    <div class="container mt-4">
                        <h1 class="mt-4">Kirim Laporan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kirim Laporan</li>
                        </ol>
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                Kirim Laporan PDF via Email
                            </div>

                            <div class="card-body">

                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('master.kirim.kirimLaporan') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Email Tujuan</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Judul Email</label>
                                        <input type="text" name="judul" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="4" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Upload File PDF</label>
                                        <input type="file" name="file_pdf" class="form-control" accept="application/pdf" required>
                                    </div>

                                    <button class="btn btn-danger">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim Email
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
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
        const hargaPerLitertView = document.getElementById('harga_per_liter_view');
        const hargaPerLitertReal = document.getElementById('harga_per_liter');
        const jumlahLiterView = document.getElementById('jumlah_liter_view');
        const jumlahLiterReal = document.getElementById('jumlah_liter');
        const totalHargaView = document.getElementById('total_harga_view');
        const totalHargaReal = document.getElementById('total_harga');

        hargaPerLitertView.addEventListener('keyup', function () {
            hargaPerLitertView.value = formatRupiah(this.value);
            hargaPerLitertReal.value = this.value.replace(/[^0-9]/g, '');
            hitungTotalHarga();
        });

        jumlahLiterView.addEventListener('input', function () {
            // hanya angka
            let value = this.value.replace(/[^0-9]/g, '');

            jumlahLiterView.value = value;

            // kirim ke controller angka murni
            jumlahLiterReal.value = value;

            hitungTotalHarga();
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

        function hitungTotalHarga() {
            const jumlah = parseInt(jumlahLiterReal.value) || 0;
            const harga = parseInt(hargaPerLitertReal.value) || 0;

            const total = jumlah * harga;

            if (total > 0) {
                totalHargaView.value = formatRupiah(total.toString());
                totalHargaReal.value = total;
            } else {
                totalHargaView.value = '';
                totalHargaReal.value = '';
            }
        }
    </script>
@endsection