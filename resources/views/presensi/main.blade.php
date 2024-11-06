@extends('layouts.main')

@section('title')
    Presensi
@endsection

@push('css')
@endpush

@section('content')
    <div id="main-page">
        <div class="card">
            <h5 class="card-header">Presensi</h5>
            <div class="row mx-4 mb-3">
                <label class="col-sm-1 col-form-label" for="basic-default-name">Tanggal</label>
                <div class="col-sm-3">
                    {{-- <input class="form-control" type="date" value="2021-06-18" id="html5-date-input" /> --}}
                    <input type="text" class="form-control" id="datepicker">
                </div>
            </div>
            <div class="table-responsive text-nowrap px-4 pb-4">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Kategori Pegawai</th>
                            <th>Masuk</th>
                            <th>Waktu Telat</th>
                            <th>Lokasi Presensi</th>
                            <th>Foto</th>
                            <th>Pulang</th>
                            <th>Pulang Cepat</th>
                            <th>Lokasi Presensi</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="other-page">

    </div>
    {{-- <a class="pan" data-big="http://localhost:8002/images/presensi/20220810221152cakiin3.jpg" href="#" id="pan">
        <img src="http://localhost:8002/images/presensi/20220810221152cakiin3.jpg" alt="">
    </a>
    <a class="pan" data-big="http://localhost:8002/images/presensi/20220810221152cakiin3.jpg" href="#" id="pan">
        <img src="http://localhost:8002/images/presensi/20220810221152cakiin3.jpg" alt="">
    </a>
    <a class="pan" data-big="http://localhost:8002/images/presensi/20220810221203cakiin5.jpg" href="#" id="pan">
        <img src="http://localhost:8002/images/presensi/20220810221203cakiin5.jpg" alt="">
    </a> --}}
@endsection

@push('js')
    <script>
        function detail(detail_user_id) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route("detail_presensi") }}',
                data: {
                    detail_user_id: detail_user_id,
                }
            }).done(function (data) {
                if (data.status) {
                    $('#other-page').html(data.content).fadeIn();
                } else {
                    
                }
            })
        }

        $(document).ready(function() {
            // DATEPICKER
            var tanggal = $('#datepicker');
            tanggal.datepicker({
                // format: "dd-mm-yyyy",
                format: "yyyy-mm-dd",
                todayBtn: "linked",
                language: "id",
                orientation: "bottom right",
                todayHighlight: true,
                autoclose: true
            });
            tanggal.datepicker('setDate', new Date());

            tanggal.change(function() {
                $('#datatables').DataTable().ajax.reload();
                $(".pan").pan();
            })

            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('presensi') }}",
                    type: "get",
                    data: {
                        'tanggal': function() {
                            // return '2022-12-01'
                            return $('#datepicker').val()
                        }
                    },
                    async: false,
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'detail_user',
                        name: 'detail_user.nama',
                        render: function(data, type, row) {
                            return `<a href="{{ route('pegawai') }}?redirect=detail_user&detail_user_id=${data.id}">${data.nama}</a>`
                        }
                    },
                    {
                        data: 'detail_user.pegawai_has_kategori',
                        name: 'detail_user.pegawai_has_kategori',
                        render: function (data, type, row) {
                            badges = '';
                            // console.log(data);
                            data.forEach(element => {
                                if (element.kategori_pegawai) {
                                    badges += `<span class="badge rounded-pill bg-label-primary mx-1 text-capitalize">${element.kategori_pegawai.nama}</span>`;
                                }
                            });
                            return badges;
                        },
                        orderable: false,
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
                        data: 'lokasi_presensi_masuk',
                        name: 'lokasi_presensi_masuk',
                        render: function (data, type, row) {
                            var text_danger = data != 'IN AREA' ? 'text-danger' : '';
                            return `<span class="${text_danger}">${data}</span>`
                        }
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
                        data: 'pulang',
                        name: 'pulang',
                    },
                    {
                        data: 'pulang_cepat',
                        name: 'pulang_cepat',
                        render: function(data, type, row) {
                            var text_danger = (data != '00:00' && data != null) ? 'bg-danger': '';
                            return `<span class='${text_danger}'>${data ?? '-'}</span>`;
                        },
                    },
                    {
                        data: 'lokasi_presensi_pulang',
                        name: 'lokasi_presensi_pulang',
                        render: function (data, type, row) {
                            var text_danger = (data != 'IN AREA' && data != null) ? 'text-danger' : '';
                            return `<span class="${text_danger}">${data ?? '-'}</span>`;
                        }
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ]
            });
            
            // IMAGE PAN
            $(".pan").pan();
        });
    </script>
@endpush
