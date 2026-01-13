@extends('layouts.app', ['title' => 'Dashboard - Admin'])

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
                <div class="alert alert-info">
                    Prioritas Operasional Utama: <strong>{{ $prioritasUtama }}</strong>
                </div>
                <div class="alert alert-info">
                    <p>Berdasarkan hasil analisis, prioritas utama perusahaan saat ini adalah <strong>{{ $prioritasUtama }}</strong> karena memiliki nilai pengeluaran tertinggi!</p>
                </div>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <!-- Total Data -->
                    <div class="row">
                        <div class="col-sm-6 col-xl-6">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fs-4 fw-semibold">Total Kapal <i class="fa fa-ship"></i></div>
                                        <div class="mt-4 mb-4">{{ $totalKapal }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-6">
                            <div class="card text-white bg-danger">
                                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fs-4 fw-semibold">Total Karyawan <i class="	fas fa-user-alt"></i>
                                        </div>
                                        <div class="mt-4 mb-4">{{ $totalKaryawan }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- grafik data gaji -->
                    <div class="row mt-4">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Jumlah Pengeluaran Per Bulan
                                </div>
                                <div class="card-body">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            Rp {{ number_format($totalGaji, 0) }}
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const labels = @json($labels);
            const dataValues = @json($data);

            const ctx = document.getElementById("myAreaChart");
            if (!ctx) return;

            // Number format (SB Admin style)
            function number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(',', '').replace(' ', '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Total Pengeluaran",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: dataValues,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'month'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function(value) {
                                    return 'Rp ' + number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Rp ' + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection