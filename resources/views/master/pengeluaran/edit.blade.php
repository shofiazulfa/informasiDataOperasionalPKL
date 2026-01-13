@extends('layouts.app', ['title' => 'Edit Operasional Lain - Admin'])

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
                    <h4 class="mt-4 mb-4">Edit Operasional Lain</h4>
                    <form action="{{ route('master.pengeluaran.update', $pengeluaran->id) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal"
                                placeholder="Masukkan tanggal" value="{{ $pengeluaran->tanggal }}">
                        </div>
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30"
                                placeholder="Masukkan keterangan">{{ $pengeluaran->keterangan }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="kredit_view" class="form-label">Kredit</label>
                            <input type="text" class="form-control" id="kredit_view" placeholder="Masukkan kredit" value="Rp {{ number_format($pengeluaran->kredit, 0, ',', '.') }}">

                            <input type="hidden" name="kredit" id="kredit" value="{{ $pengeluaran->kredit }}">
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
        const kreditView = document.getElementById('kredit_view');
        const kreditReal = document.getElementById('kredit');

        kreditView.addEventListener('keyup', function () {
            kreditView.value = formatRupiah(this.value);
            kreditReal.value = this.value.replace(/[^0-9]/g, '');
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