<!-- Navbar Brand-->
<img src="{{ asset('assets/img/logo.png') }}" alt="" class="img-fluid m-2 p-0" width="40">
<a class="navbar-brand ps-2 me-4" href="{{ route('dashboard') }}">PT Zidni Daya Nusantara</a>
<!-- Sidebar Toggle-->
<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>

@if(Route::is('proses.totalKeseluruhan'))
        <div class="ms-auto d-flex align-items-center me-3">
                <!-- Button Export PDF -->
                <a href="{{ route('proses.totalKeseluruhan.pdf') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Export PDF
                </a>
        </div>
@elseif(Route::is('proses.operasionalBulanan'))
        <div class="ms-auto d-flex align-items-center me-3">
                <a class="btn btn-danger btn-sm" data-bs-toggle="collapse" href="#exportPdf"
                role="button" aria-expanded="false" aria-controls="exportPdf">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                        <i class="fas fa-angle-down ms-2"></i>
                </a>

                <div class="collapse position-absolute mt-2 bg-white shadow rounded p-3"
                id="exportPdf"
                style="right: 20px; top: 60px; width: 280px; z-index: 999;">

                <form action="{{ route('proses.operasionalBulanan.pdf', ['periode' => '__ID__']) }}"
                        method="GET"
                        id="formExportPdf">

                        <!-- Pilih Periode -->
                        <div class="mb-3">
                        <label class="form-label">Periode Laporan</label>
                        <select class="form-select form-select-sm" id="periodeSelect" required>
                                <option value="">-- Pilih Periode --</option>
                                @foreach($periodeList as $periode)
                                <option value="{{ $periode->id }}">
                                        {{ \Carbon\Carbon::create()->month($periode->bulan)->translatedFormat('F') }}
                                        {{ $periode->tahun }}
                                </option>
                                @endforeach
                        </select>
                        </div>

                        <!-- Tombol Export -->
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-download me-1"></i> Export Sekarang
                        </button>
                </form>
                </div>
        </div>
@endif