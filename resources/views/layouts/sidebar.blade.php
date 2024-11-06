<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('images/natusi.png') }}" alt="logo" height="50px">
            </span>
            {{-- <span class="demo menu-text fw-bolder ms-2">{{ env('APP_NAME') }}</span> --}}
            {{-- <span class="app-brand-text demo menu-text fw-bolder ms-2"> --}}
            <span class="demo menu-text fw-bolder ms-2">
                <span style="font-size: 30px">
                    {{ env('APP_SHORT_NAME') }}
                </span>
                <br>
                <span style="font-size: 10px; color: #E8ED00">
                    Kota Mojokerto - Jawa Timur
                </span>
            </span>
            {{-- <span class="demo menu-text fw-bolder ms-2">Kota Mojokerto - Jawa Timur</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
        $jenis = request()->jenis;
    @endphp

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ $segment1 == '' ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @if (Auth::user()->level == 1)
            <li
                class="menu-item {{ $segment1 == 'pegawai' || $segment1 == 'penjadwalan_shift' || $segment1 == 'kategori_pegawai' || $segment1 == 'shift' || $segment1 == 'pekerjaan' || $segment1 == 'sebaran_pegawai' || $segment1 == 'lokasi_kantor' || $segment1 == 'point_target' || $segment1 == 'pekerjaan_pegawai' || $segment1 == 'bidang' ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Data Master">Data Master</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ $segment1 == 'pegawai' ? 'active' : '' }}">
                        <a href="{{ route('pegawai') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div data-i18n="Boxicons">Data Pegawai</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'penjadwalan_shift' ? 'active' : '' }}">
                        <a href="{{ route('penjadwalan_shift') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                            <div data-i18n="Boxicons">Penjadwalan Shift</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'kategori_pegawai' ? 'active' : '' }}">
                        <a href="{{ route('kategori_pegawai') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-category"></i>
                            <div data-i18n="Boxicons">Kategori Pegawai</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'bidang' ? 'active' : '' }}">
                        <a href="{{ route('bidang') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-category"></i>
                            <div data-i18n="Boxicons">Kategori Bidang</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'shift' ? 'active' : '' }}">
                        <a href="{{ route('shift') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                            <div data-i18n="Boxicons">Master Data Shift</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'pekerjaan' ? 'active' : '' }}">
                        <a href="{{ route('pekerjaan') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-task"></i>
                            <div data-i18n="Boxicons">Data Pekerjaan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'pekerjaan_pegawai' ? 'active' : '' }}">
                        <a href="{{ route('pekerjaan_pegawai') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user-check"></i>
                            <div data-i18n="Boxicons">Pekerjaan Pegawai</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'point_target' ? 'active' : '' }}">
                        <a href="{{ route('point_target') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-target-lock"></i>
                            <div data-i18n="Boxicons">Point Target</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'sebaran_pegawai' ? 'active' : '' }}">
                        <a href="{{ route('sebaran_pegawai') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-map-alt"></i>
                            <div data-i18n="Boxicons">Sebaran Pegawai</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'lokasi_kantor' ? 'active' : '' }}">
                        <a href="{{ route('lokasi_kantor') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-map"></i>
                            <div data-i18n="Boxicons">Lokasi Kantor</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        <li
            class="menu-item {{ $segment1 == 'presensi' || $segment1 == 'penilaian_kerja' || ($segment1 == 'perizinan' && $segment2 == 'riwayat') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Kinerja Pegawai">Kinerja Pegawai</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ $segment1 == 'presensi' ? 'active' : '' }}">
                    <a href="{{ route('presensi') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-time-five"></i>
                        <div data-i18n="Boxicons">Presensi</div>
                    </a>
                </li>
                <li class="menu-item {{ $segment1 == 'penilaian_kerja' ? 'active' : '' }}">
                    <a href="{{ route('penilaian_kerja') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Boxicons">Penilaian Kerja</div>
                    </a>
                </li>
                <li class="menu-item {{ $segment1 == 'perizinan' && $segment2 == 'riwayat' ? 'active' : '' }}">
                    <a href="{{ route('riwayat_perizinan') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Boxicons">Riwayat Perizinan</div>
                    </a>
                </li>
            </ul>
        </li>
        @if (Auth::user()->level == 1)
            <li
                class="menu-item {{ $segment1 == 'kategori_izin' || ($segment1 == 'perizinan' && $segment2 != 'riwayat') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div data-i18n="Perizinan">Perizinan</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ $segment1 == 'kategori_izin' ? 'active' : '' }}">
                        <a href="{{ route('kategori_izin') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Boxicons">Kategori Perizinan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $segment1 == 'perizinan' && $segment2 != 'riwayat' ? 'active' : '' }}">
                        <a href="{{ route('perizinan') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Boxicons">Data Perizinan</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data Master</span>
        </li> --}}

        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Perizinan</span>
        </li> --}}
        {{--
        <li class="menu-item {{ $segment1 == 'kategori_izin' ? 'active' : '' }}">
            <a href="{{ route('kategori_izin') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Boxicons">Kategori Perizinan</div>
            </a>
        </li>
        <li class="menu-item {{ $segment1 == 'perizinan' && $segment2 != 'riwayat' ? 'active' : '' }}">
            <a href="{{ route('perizinan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Boxicons">Data Perizinan</div>
            </a>
        </li>
        <li class="menu-item {{ $segment1 == 'perizinan' && $segment2 == 'riwayat' ? 'active' : '' }}">
            <a href="{{ route('riwayat_perizinan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Boxicons">Riwayat Perizinan</div>
            </a>
        </li> --}}

        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pekerjaan</span>
        </li> --}}
        {{-- <li class="menu-item {{ $segment1 == 'presensi' ? 'active' : '' }}">
            <a href="{{ route('presensi') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-time-five"></i>
                <div data-i18n="Boxicons">Presensi</div>
            </a>
        </li>
        <li class="menu-item {{ $segment1 == 'penilaian_kerja' ? 'active' : '' }}">
            <a href="{{ route('penilaian_kerja') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Boxicons">Penilaian Kerja</div>
            </a>
        </li> --}}

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Lain-lain</span>
        </li>
        <li class="menu-item {{ $segment1 == 'pengaturan' ? 'active' : '' }}">
            <a href="{{ route('pengaturan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wrench"></i>
                <div data-i18n="Boxicons">Pengaturan</div>
            </a>
        </li>
        <li class="menu-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn menu-link">
                    <i class="menu-icon tf-icons bx bx-log-out"></i>
                    <div data-i18n="Boxicons">Log Out</div>
                </button>
            </form>
        </li>

    </ul>
</aside>
