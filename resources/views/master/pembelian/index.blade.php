@extends('layouts.app', ['title' => 'Pembelian - Admin'])

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
                    <h1 class="mt-4">Pembelian</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pembelian</li>
                    </ol>
                    <div class="m-2 d-flex justify-content-end">
                        <form action="{{ route('master.pembelian.create') }}" method="GET">
                            <button type="submit" class="btn btn-secondary me-2">+ Tambah Data</button>
                        </form>

                        <form id="importForm" action="{{ route('master.pembelian.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Input file disembunyikan -->
                            <input type="file" id="csvFileInput" name="file" accept=".csv" hidden>

                            <!-- Button Import -->
                            <button type="button" class="btn btn-success btn-secondary" onclick="openFileDialog()">
                                <i class="fas fa-file-upload"></i> Import CSV
                            </button>
                        </form>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Pembelian Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Satuan Barang</th>
                                        <th>Harga Satuan</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Satuan Barang</th>
                                        <th>Harga Satuan</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($buys as $buy)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $buy->tanggal }}
                                            </td>
                                            <td>{{ $buy->keterangan }}</td>
                                            <td>
                                                {{ $buy->pcs }}
                                            </td>
                                            <td>
                                                Rp. {{ number_format($buy->harga_satuan, 0) }}
                                            </td>
                                            <td>
                                                Rp {{ number_format($buy->total_jumlah, 0) }}
                                            </td>
                                            <td>
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="{{ route('master.pembelian.edit', $buy->id) }}"
                                                                class="btn btn-primary btn-sm">Edit</a>
                                                        </div>
                                                        <div class="col">
                                                            <button onclick="confirmDelete({{ $buy->id }})"
                                                                class="btn btn-danger btn-sm">Hapus</button>

                                                            <form id="delete-form-{{ $buy->id }}"
                                                                action="{{ route('master.pembelian.destroy', $buy->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/datatables-simple-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(Session::has('success'))
        <script>
            Swal.fire(
                'Success!',
                '{{ Session::get('success') }}',
                'success'
            )
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function openFileDialog() {
            document.getElementById('csvFileInput').click();
        }

        // auto submit setelah file dipilih
        document.getElementById('csvFileInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('importForm').submit();
            }
        });
    </script>
@endsection