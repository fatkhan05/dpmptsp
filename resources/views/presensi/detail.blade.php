<style>

</style>

<div class="card  px-4 pb-4">
    <h5 class="card-header">Penilaian Kerja</h5>
    <div class="row mb-4">
        <div class="col-md-3 align-self-center text-center">
            <div class="p-profile">
                <a href="#" class="pan" data-big="{{ asset('images') . '/' . $detail_user->photo }}">
                    <img src="{{ asset('images') . '/' . $detail_user->photo }}" alt="Photo Profile" class="rounded-circle">
                </a>
            </div>
        </div>
        <div class="col-md">
            <div class="row">
                <div class="col">
                    <h3>{{ $detail_user->nama }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Bidang / Bagian:</p>
                    <ul>
                        @foreach ($detail_user->pegawai_has_kategori as $pegawai_has_kategori)
                            <li>{{ $pegawai_has_kategori->kategori_pegawai->nama }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <p>{{ $detail_user->alamat }}</p>
                    <p>{{ $detail_user->telepon }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-4 mb-3">
        <label class="col-sm-1 col-form-label" for="basic-default-name">Tanggal</label>
        <div class="col-sm-3">
            {{-- <input class="form-control" type="date" value="2021-06-18" id="html5-date-input" /> --}}
            <input type="text" class="form-control" id="bulan_datepicker">
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-bordered" id="detail_datatables">
            <thead>
                <tr>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="5">Datang</th>
                    <th colspan="4">Pulang</th>
                    <th>Total</th>
                    <th id="total">%</th>
                </tr>
                <tr>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Waktu</th>
                    <th>Telat</th>
                    <th>Foto</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Waktu</th>
                    <th>Lebih Cepat</th>
                    <th>Foto</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">

            </tbody>
        </table>
    </div>

    <div class="row my-4">
        <div class="col-12">
            <button type="button" id="btn-back" class="btn btn-secondary btn-block"> Kembali </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // DATEPICKER
        var tanggal = $('#bulan_datepicker');
        tanggal.datepicker({
            // format: "dd-mm-yyyy",
            format: "mm-yyyy",
            language: "id",
            orientation: "bottom right",
            todayHighlight: true,
            startView: "months",
            minViewMode: "months",
            autoclose: true
        });
        tanggal.datepicker('setDate', new Date());

        tanggal.change(function() {
            $('#detail_datatables').DataTable().ajax.reload();
            $(".pan").pan();
        })

        $('#btn-back').click(function () {
            $('#other-page').fadeOut(function() {
                $('#other-page').empty();
                $('#main-page').fadeIn();
            });
        })

        // DATATABLES
        var dTable = $('#detail_datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dt_detail_presensi') }}",
                    type: "post",
                    data: {
                        'detail_user_id': {{ $detail_user->id }},
                        'tanggal': function() {
                            // return '2022-12-01'
                            return $('#bulan_datepicker').val()
                        }
                    },
                    dataSrc: (json) => {
                        $('#total').text(json.total);
                        return json.data;
                    },
                    async: false,
                },
                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'latitude_masuk',
                        name: 'latitude_masuk',
                    },
                    {
                        data: 'longitude_masuk',
                        name: 'longitude_masuk',
                    },
                    {
                        data: 'masuk',
                        name: 'masuk',
                    },
                    {
                        data: 'telat',
                        name: 'telat',
                        render: function(data, type, row) {
                            var text_danger = data != '00:00' ? 'text-danger': '';
                            return `<span class='${text_danger}'>${data}</span>`;
                        },
                    },
                    {
                        data: 'foto_masuk',
                        name: 'foto_masuk',
                        render: function (data, type, row) {
                            return `
                            <a class="pan" data-big="${data}" href="#">
                                <img src="${data}" alt="foto masuk" style="width: 50" class='d-block img-fluid'>
                            </a>`;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'latitude_pulang',
                        name: 'latitude_pulang',
                    },
                    {
                        data: 'longitude_pulang',
                        name: 'longitude_pulang',
                    },
                    {
                        data: 'pulang',
                        name: 'pulang',
                    },
                    {
                        data: 'pulang_cepat',
                        name: 'pulang_cepat',
                        render: function(data, type, row) {
                            var text_danger = (data != '00:00' && data != null) ? 'text-danger': '';
                            return `<span class='${text_danger}'>${data ?? '-'}</span>`;
                        },
                    },
                    {
                        data: 'foto_pulang',
                        name: 'foto_pulang',
                        render: function (data, type, row) {
                            var img = '-';
                            if (data) {
                                img = `
                                <a class="pan" data-big="${data}" href="#">
                                    <img src="${data}" alt="foto masuk" style="width: 50" class='d-block img-fluid'>
                                </a>`;
                            }
                            return img;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'percent',
                        name: 'percent',
                        render: function (data) {
                            return `${data * 100}%`;
                        },
                        searchable: false,
                    },
                ]
            });

        // IMAGE PAN
        $(".pan").pan();
    })
</script>