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
                    <h1 class="mt-4">Total Keseluruhan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Rekap</li>
                        <li class="breadcrumb-item active">Total Keseluruhan</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Total Keseluruhan Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Keterangan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Keterangan</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Pembelian</td>
                                        <td>
                                            Rp {{ number_format($totalPembelian, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Penggunaan BBM</td>
                                        <td>
                                            Rp {{ number_format($totalBBM, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Biaya Operasional Kapal</td>
                                        <td>
                                            Rp {{ number_format($totalOperasional, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Gaji Karyawan</td>
                                        <td>
                                            Rp {{ number_format($totalGajiKaryawan, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Pengeluaran (Kredit)</td>
                                        <td>
                                            Rp {{ number_format($totalPengeluaran, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>-</td>
                                        <td>Total Keseluruhan</td>
                                        <td>
                                            Rp {{ number_format($totalKeseluruhan, 0) }}
                                        </td>
                                    </tr>
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
@endsection