@extends('layouts.app', ['title' => 'Update Pembelian - Admin'])

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
                    <h4 class="mt-4 mb-4">Update Pembelian</h4>
                    <form action="{{ route('master.pembelian.update', $pembelian->id) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal"
                                placeholder="Masukkan tanggal" value="{{ $pembelian->tanggal }}">
                        </div>
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30"
                                placeholder="Masukkan keterangan">{{ $pembelian->keterangan }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="pcs_view" class="form-label">Satuan Barang</label>
                            <input type="text" class="form-control" id="pcs_view" placeholder="Masukkan satuan barang" value="{{ number_format($pembelian->pcs, 0) }} pcs">

                            <input type="hidden" name="pcs" id="pcs" value="{{ $pembelian->pcs }}">
                        </div>
                        <div class="col-md-12">
                            <label for="harga_satuan_view" class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control" id="harga_satuan_view"
                                placeholder="Masukkan harga satuan barang" value="Rp {{ number_format($pembelian->harga_satuan, 0, ',', '.') }}">

                            <input type="hidden" name="harga_satuan" id="harga_satuan" value="{{ $pembelian->harga_satuan }}">
                        </div>
                        <div class="col-md-12">
                            <label for="total_jumlah_view" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" disabled id="total_jumlah_view"
                                placeholder="Data akan terinput otomatis bila form satuan barang dan harga satuan diisi" value="Rp {{ number_format($pembelian->total_jumlah, 0, ',', '.') }}">

                            <input type="hidden" name="total_jumlah" id="total_jumlah" value="{{ $pembelian->total_jumlah }}">
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
        const pcsView = document.getElementById('pcs_view');
        const pcsReal = document.getElementById('pcs');
        const hargaSatuanView = document.getElementById('harga_satuan_view');
        const hargaSatuanReal = document.getElementById('harga_satuan');
        const totalJumlahView = document.getElementById('total_jumlah_view');
        const totalJumlahReal = document.getElementById('total_jumlah');

        pcsView.addEventListener('input', function () {
            // hanya angka
            let value = this.value.replace(/[^0-9]/g, '');

            // tampilkan dengan angka + liter
            pcsView.value = value ? value + ' pcs' : '';

            // kirim ke controller angka murni
            pcsReal.value = value;

            hitungTotalHarga();
        });

        hargaSatuanView.addEventListener('keyup', function () {
            hargaSatuanView.value = formatRupiah(this.value);
            hargaSatuanReal.value = this.value.replace(/[^0-9]/g, '');

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
            const jumlah = parseInt(pcsView.value) || 0;
            const harga = parseInt(hargaSatuanReal.value) || 0;

            const total = jumlah * harga;

            if (total > 0) {
                totalJumlahView.value = formatRupiah(total.toString());
                totalJumlahReal.value = total;
            } else {
                totalJumlahView.value = '';
                totalJumlahReal.value = '';
            }
        }
    </script>
@endsection