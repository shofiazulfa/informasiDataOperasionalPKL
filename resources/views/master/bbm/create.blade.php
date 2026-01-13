@extends('layouts.app', ['title' => 'Create BBM - Admin'])

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
                    <h4 class="mt-4 mb-4">Create BBM</h4>
                    <form action="{{ route('master.bbm.store') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">Tanggal Penggunaan</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal">
                        </div>
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30"
                                placeholder="Masukkan keterangan"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="jumlah_liter_view" class="form-label">Jumlah Penggunaan</label>
                            <input type="text" class="form-control" id="jumlah_liter_view"
                                placeholder="Masukkan jumlah penggunaan per liter">

                            <input type="hidden" name="jumlah_liter" id="jumlah_liter">
                        </div>
                        <div class="col-md-12">
                            <label for="harga_per_liter_view" class="form-label">Harga Penggunaan</label>
                            <input type="text" class="form-control" id="harga_per_liter_view"
                                placeholder="Masukkan harga penggunaan bbm">

                            <input type="hidden" name="harga_per_liter" id="harga_per_liter">
                        </div>
                        <div class="col-md-12">
                            <label for="total_harga_view" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" disabled id="total_harga_view"
                                placeholder="Data akan terinput otomatis bila form jumlah penggunaan dan harga penggunaan diisi">

                            <input type="hidden" name="total_harga" id="total_harga">
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