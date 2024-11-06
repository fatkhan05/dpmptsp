<style>
    .lampiran img {
        width: 100%;
        height: 250px;
        -o-object-fit: cover;
        object-fit: cover;
        -o-object-position: center;
        object-position: center;
    }
</style>

<div class="card  px-4 pb-4">
    <h5 class="card-header">Detail Perizinan</h5>
    <div class="row mb-4">
        <div class="col-md-3 align-self-center text-center">
            <div class="p-profile">
                <a href="#" class="pan" data-big="{{ asset('images') . '/' . $perizinan->detail_user->photo }}">
                    <img src="{{ asset('images') . '/' . $perizinan->detail_user->photo }}" alt="Photo Profile" class="rounded-circle">
                </a>
            </div>
        </div>
        <div class="col-md">
            <div class="row">
                <div class="col">
                    <h3>{{ $perizinan->detail_user->nama }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Bidang / Bagian:</p>
                    <ul>
                        @foreach ($perizinan->detail_user->pegawai_has_kategori as $pegawai_has_kategori)
                            <li>{{ $pegawai_has_kategori->kategori_pegawai->nama }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <p>{{ $perizinan->detail_user->alamat }}</p>
                    <p>{{ $perizinan->detail_user->telepon }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-5">
        <div class="row">
            <div class="col-sm-2">
                Kategori Izin
            </div>
            <div class="col-sm-auto">
                : {{ $perizinan->kategori_izin->nama }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                Tanggal Mulai izin
            </div>
            <div class="col-sm-auto">
                : {{ $perizinan->mulai }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                Tanggal Selesai izin
            </div>
            <div class="col-sm-auto">
                : {{ $perizinan->selesai }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                Tanggal Selesai izin
            </div>
            <div class="col-sm-auto">
                : {{ $perizinan->keterangan }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                Status
            </div>
            <div class="col-sm-auto text-capitalize">
                : {{ $perizinan->status }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                Lampiran
            </div>
            <div class="col-sm-auto">
                : 
            </div>
        </div>
        <div class="row my-4">
            @foreach ($perizinan->detail_perizinan as $detail_perizinan)
            <div class="col-md-6 lampiran">
                <a href="#" class="pan" data-big="{{ asset("images/$detail_perizinan->lampiran") }}">
                    <img src="{{ asset("images/$detail_perizinan->lampiran") }}" alt="" class="img-fluid">
                </a>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" id="btn-back" class="btn btn-secondary btn-block"> Kembali </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // IMAGE PAN
        $(".pan").pan();

        $('#btn-back').click(function () {
            $('#other-page').fadeOut(function() {
                $('#other-page').empty();
                $('#main-page').fadeIn();
            });
        })
    })
</script>