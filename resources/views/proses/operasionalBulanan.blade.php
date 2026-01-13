@extends('layouts.app', ['title' => 'Proses Rekap - Admin'])

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
                    <h1 class="mt-4">Operasional Bulanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Rekap</li>
                        <li class="breadcrumb-item active">Operasional Bulanan</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Pembelian Bulanan Table
                        </div>
                        <div class="card-body">
                            <table class="dataTable" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($pembelianBulanan as $pb)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pb->tahun }}</td>
                                            <td>{{ \Carbon\Carbon::create(null, $pb->bulan)->translatedFormat('F') }}</td>
                                            <td>
                                                Rp {{ number_format($pb->total, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Penggunaan BBM Bulanan Table
                        </div>
                        <div class="card-body">
                            <table class="dataTable" id="table2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($bbmBulanan as $bb)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bb->tahun }}</td>
                                            <td>{{ \Carbon\Carbon::create(null, $bb->bulan)->translatedFormat('F') }}</td>
                                            <td>
                                                Rp {{ number_format($bb->total, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Biaya Operasional Kapal Bulanan Table
                        </div>
                        <div class="card-body">
                            <table class="dataTable" id="table3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($operasionalBulanan as $op)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $op->tahun }}</td>
                                            <td>{{ \Carbon\Carbon::create(null, $op->bulan)->translatedFormat('F') }}</td>
                                            <td>
                                                Rp {{ number_format($op->total, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Gaji Karyawan Bulanan Table
                        </div>
                        <div class="card-body">
                            <table class="dataTable" id="table4">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($gajiBulanan as $gb)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $gb->tahun }}</td>
                                            <td>{{ \Carbon\Carbon::create(null, $gb->bulan)->translatedFormat('F') }}</td>
                                            <td>
                                                Rp {{ number_format($gb->total, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Operasional Lain Bulanan Table
                        </div>
                        <div class="card-body">
                            <table class="dataTable" id="table5">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($pengeluaranBulanan as $pb)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pb->tahun }}</td>
                                            <td>{{ \Carbon\Carbon::create(null, $pb->bulan)->translatedFormat('F') }}</td>
                                            <td>
                                                Rp {{ number_format($pb->total, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/datatables-simple-demo.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataTable1 = new simpleDatatables.DataTable("#table1");
            const dataTable2 = new simpleDatatables.DataTable("#table2");
            const dataTable3 = new simpleDatatables.DataTable("#table3");
            const dataTable4 = new simpleDatatables.DataTable("#table4");
            const dataTable5 = new simpleDatatables.DataTable("#table5");
        });

        document.getElementById('formExportPdf').addEventListener('submit', function (e) {
            e.preventDefault();

            const periodeId = document.getElementById('periodeSelect').value;

            if (!periodeId) {
                return;
            }

            let url = "{{ route('proses.operasionalBulanan.pdf', ['periode' => '__ID__']) }}";
            url = url.replace('__ID__', periodeId);

            window.location.href = url;
        });
    </script>
@endsection