<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Operasional Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #dc3545;
            color: white;
            padding: 6px;
            border: 1px solid #000;
        }

        td {
            padding: 6px;
            border: 1px solid #000;
        }

        tfoot td {
            background: #eee;
            font-weight: bold;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo img {
            width: 70px;
        }

        .company-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }

        .company-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <img src="{{ public_path('assets/img/logo.png') }}">
        </div>
        <div class="company-info">
            <div class="company-title">PT ZIDNI DAYA NUSANTARA</div>
            <div class="report-title">Laporan Rekap Total Keseluruhan</div>
        </div>
    </div>

    {{-- ===================== PEMBELIAN ===================== --}}
    <h1>Data Pembelian</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>PCS</th>
                <th>Harga Satuan</th>
                <th>Total Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->pcs }}</td>
                    <td>Rp {{ number_format($row->harga_satuan, 0) }}</td>
                    <td>Rp {{ number_format($row->total_jumlah, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Data tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total Pembelian Bulan Ini</td>
                <td>Rp {{ number_format($totalPembelian, 0) }}</td>
            </tr>
        </tfoot>
    </table>


    {{-- ===================== PENGELUARAN ===================== --}}
    <h1>Data Pengeluaran</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>Rp {{ number_format($row->kredit, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Data tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total Pengeluaran Bulan Ini</td>
                <td>Rp {{ number_format($totalPengeluaran, 0) }}</td>
            </tr>
        </tfoot>
    </table>


    {{-- ===================== BIAYA OPERASIONAL + GAJI ===================== --}}
    <h1>Data Biaya Operasional & Gaji Karyawan</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Karyawan</th>
                <th>Keterangan</th>
                <th>Jumlah Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($operasional as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->nama_karyawan ?? '-' }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>Rp {{ number_format($row->jumlah_biaya, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Data tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total Operasional Bulan Ini</td>
                <td>Rp {{ number_format($totalOperasional, 0) }}</td>
            </tr>
        </tfoot>
    </table>


    {{-- ===================== BBM + KAPAL ===================== --}}
    <h1>Data Penggunaan BBM & Kapal</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Liter</th>
                <th>Harga/Liter</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bbm as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->jumlah_liter }} Liter</td>
                    <td>Rp {{ number_format($row->harga_per_liter, 0) }}</td>
                    <td>Rp {{ number_format($row->total_harga, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total BBM Bulan Ini</td>
                <td>Rp {{ number_format($totalBBM, 0) }}</td>
            </tr>
        </tfoot>
    </table>


    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>

</body>

</html>