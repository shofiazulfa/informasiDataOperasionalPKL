<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Main Menu</div>
            <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Master Data</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOperasional"
                aria-expanded="false" aria-controls="collapseOperasional">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Operasional Data
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseOperasional" aria-labelledby="headingOne"
                data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('master.pembelian.index') }}">Pembelian</a>
                    <a class="nav-link" href="{{ route('master.pengeluaran.index') }}">Operasional Lain</a>
                    <a class="nav-link" href="{{ route('master.kapal.index')  }}">Kapal</a>
                    <a class="nav-link" href="{{ route('master.karyawan.index') }}">Karyawan</a>
                    <a class="nav-link" href="{{ route('master.bbm.index') }}">Penggunaan BBM</a>
                </nav>
            </div>

            <div class="sb-sidenav-menu-heading">Proses Data</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRekap"
                aria-expanded="false" aria-controls="collapseRekap">
                <div class="sb-nav-link-icon"><i class="fas fa-archive"></i></div>
                Rekap
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseRekap" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('proses.operasionalBulanan') }}">Operasional Bulanan</a>
                    <a class="nav-link" href="{{ route('proses.totalKeseluruhan')  }}">Total Keseluruhan</a>
                </nav>
            </div>
            <a class="nav-link {{ Route::is('master.kirim.index') ? 'active' : '' }}" href="{{ route('master.kirim.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                Kirim Laporan
            </a>

            <div class="sb-sidenav-menu-heading">Authentication</div>
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                <div class="sb-nav-link-icon"><i class="fas fa-sign-out"></i></div>
                Logout
            </a>
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</nav>