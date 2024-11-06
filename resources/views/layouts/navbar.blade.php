{{-- <div class="row">
    <div class="col bg-success">
        coba
    </div>
</div> --}}
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                    aria-label="Search..." />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notification. -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar {{ !empty($notification) ? 'avatar-away' : '' }} avatar-xs pull-up">
                        <i class="bx bx-bell bx-sm"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item disabled" href="#" aria-disabled="true">
                            {{-- <i class="bx bx-user me-2"></i> --}}
                            <span class="align-middle">Tidak ada notifikasi</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-xs pull-up">
                        @if (auth()->user()->detail_user->photo != null)
                            @php
                                $photo = auth()->user()->detail_user->photo;
                            @endphp
                            <img src="{{ asset("images/$photo") }}" alt class="w-px-30 h-auto rounded-circle" />
                        @else
                            <i class="bx bx-user bx-sm"></i>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (auth()->user()->detail_user->photo != null)
                                            @php
                                                $photo = auth()->user()->detail_user->photo;
                                            @endphp
                                            <img src="{{ asset("images/$photo") }}" alt="&#xf007;"
                                                class="w-px-40 h-auto rounded-circle" />
                                        @else
                                            <i class="bx bx-user bx-md"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->detail_user->nama }}</span>
                                    @foreach (auth()->user()->detail_user->pegawai_has_kategori as $pegawai_has_kategori)
                                        <small
                                            class="text-muted">{{ $pegawai_has_kategori->kategori_pegawai->nama }}</small>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                        {{-- <a class="dropdown-item" href="auth-login-basic.html">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a> --}}
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
