<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Total Keseluruhan</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
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

        .divider {
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #c62828;
            /* merah */
            color: white;
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
        }

        table td {
            padding: 8px;
            border: 1px solid #000;
        }

        .total-row {
            background-color: #e0e0e0;
            /* abu abu */
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: right;
            padding-top: 10px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>

    <!-- HEADER DENGAN LOGO -->
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('assets/img/logo.png') }}">
        </div>
        <div class="company-info">
            <div class="company-title">PT ZIDNI DAYA NUSANTARA</div>
            <div class="report-title">Laporan Rekap Total Keseluruhan</div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Tabel Rekap -->
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Keterangan</th>
                <th width="30%">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td>Pembelian</td>
                <td align="right">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Penggunaan BBM</td>
                <td align="right">Rp {{ number_format($totalBBM, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>Biaya Operasional Kapal</td>
                <td align="right">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Gaji Karyawan</td>
                <td align="right">Rp {{ number_format($totalGajiKaryawan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Pengeluaran (Kredit)</td>
                <td align="right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>

            <!-- Total Keseluruhan -->
            <tr class="total-row">
                <td align="center">-</td>
                <td>Total Keseluruhan</td>
                <td align="right">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ now()->locale('INDONESIA')->format('d-m-Y H:i:s') }}
    </div>

</body>

</html>