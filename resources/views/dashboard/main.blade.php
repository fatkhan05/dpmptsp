@extends('layouts.main')

@section('title')
    Dashboard
@endsection

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-lg mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-group bx-lg'></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="{{ route('pegawai') }}">Lihat Selangkapnya</a>
                            </div>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pegawai</span>
                    <h3 class="card-title mb-2">{{ $jumlah_pegawai }}</h3>
                    {{-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
                </div>
            </div>
        </div>
        <div class="col-lg mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-task bx-lg'></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="{{ route('pekerjaan') }}">Lihat Selangkapnya</a>
                            </div>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pekerjaan</span>
                    <h3 class="card-title mb-2">{{ $jumlah_pekerjaan }}</h3>
                    {{-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
                </div>
            </div>
        </div>
        <div class="col-lg mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-map-alt bx-lg'></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="{{ route('sebaran_pegawai') }}">Lihat Selangkapnya</a>
                            </div>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Jumlah Pegawai Di luar area</span>
                    <h3 class="card-title mb-2">{{ $jumlah_pegawai_diluar }}</h3>
                    {{-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
