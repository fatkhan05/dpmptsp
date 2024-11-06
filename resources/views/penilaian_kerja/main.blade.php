@extends('layouts.main')

@section('title')
    Penilaian Kerja
@endsection

{{-- @push('css')
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #9BC523;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #a5d421;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #7fa023;
        }

    </style>
@endpush --}}

@push('css')
    <style>
        /* RATING CSS */
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
        }

        .rating>input {
            display: none;
        }

        .rating>label {
            position: relative;
            width: 1em;
            font-size: 1.5em;
            color: #9BC523;
            cursor: pointer;
        }

        .rating>label::before {
            content: "\2605";
            position: absolute;
            opacity: 0;
        }

        .rating>label:hover:before,
        .rating>label:hover~label:before {
            opacity: 1 !important;
        }

        .rating>input:checked~label:before {
            opacity: 1;
        }

        .rating:hover>input:checked~label:before {
            opacity: 0.4;
        }
    </style>
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card">
            <h5 class="card-header">Penilaian Kerja</h5>
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
                            {{-- <th style="width: 500px">Pekerjaan</th> --}}
                            {{-- <th>Lokasi</th> --}}
                            <th>Nama Pegawai</th>
                            <th>Point / Target</th>
                            {{-- <th>Sebelum</th> --}}
                            {{-- <th>Sesudah</th> --}}
                            {{-- <th>Nilai</th> --}}
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
    {{-- MODAL --}}
    @include('penilaian_kerja.modal.detail_pekerjaan')
@endsection

@push('js')
    <script>
        function detail(id, date) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: "{{ route('detail_penilaian_kerja') }}",
                data: {
                    id: id,
                    date: String(date)
                }
            }).done(function(data) {
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
                format: "yyyy-mm-dd",
                todayBtn: "linked",
                clearBtn: true,
                language: "id",
                orientation: "bottom right",
                todayHighlight: true,
                autoclose: true
            });
            tanggal.datepicker('setDate', new Date());

            tanggal.change(function() {
                $('#datatables').DataTable().ajax.reload();
            })

            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('penilaian_kerja') }}",
                    type: "get",
                    data: {
                        'tanggal': function() {
                            // return '2022-12-01'
                            return $('#datepicker').val()
                        }
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'pekerjaan.nama',
                    //     name: 'pekerjaan.nama',
                    //     render: function(data, type, row) {
                    //         return `<div style="white-space:normal;width:100%">${data}</div>`;
                    //     }
                    // },
                    // {
                    //     data: 'pekerjaan.alamat',
                    //     name: 'pekerjaan.alamat',
                    // },
                    {
                        data: 'nama',
                        name: 'nama',
                        // render: function(data, type, row) {
                        //     return `<a href="{{ route('pegawai') }}?redirect=detail_user&detail_user_id=${data.id}">${data.nama}</a>`
                        // }
                    },
                    {
                        data: 'pegawai_has_pekerjaan',
                        name: 'pegawai_has_pekerjaan',
                        render: function(data, type, row) {
                            var total = 0;
                            data.forEach(element => {
                                total += element.nilai * 20;
                            });
                            // return `<a href="{{ route('pegawai') }}?redirect=detail_user&detail_user_id=${data.id}">${data.nama}</a>`
                            return `<span>${total} / ${row.point_target.nilai}</span>`;
                        }
                    },
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    //     render: function(data, type, row) {
                    //         return `<a href="#" onclick='detail_sebelum(${data})'>Lihat detail</a>`
                    //     },
                    //     orderable: false,
                    //     searchable: false,
                    // },
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    //     render: function(data, type, row) {
                    //         return `<a href="#" onclick='detail_sesudah(${data})'>Lihat detail</a>`
                    //     },
                    //     orderable: false,
                    //     searchable: false,
                    // },
                    // {
                    //     data: 'nilai',
                    //     name: 'nilai.nilai',
                    //     render: function(data, type, row) {

                    //     return `
                //     <div class="rate">
                //         <input ${data.nilai == 5? 'checked': ''} onclick="rate(${data.id}, 5, ${data.nilai})" type="radio" id="star5${data.id}" name="rate${data.id}" value="5" />
                //         <label for="star5${data.id}" title="text">5 stars</label>
                //         <input ${data.nilai == 4? 'checked': ''} onclick="rate(${data.id}, 4, ${data.nilai})" type="radio" id="star4${data.id}" name="rate${data.id}" value="4" />
                //         <label for="star4${data.id}" title="text">4 stars</label>
                //         <input ${data.nilai == 3? 'checked': ''} onclick="rate(${data.id}, 3, ${data.nilai})" type="radio" id="star3${data.id}" name="rate${data.id}" value="3" />
                //         <label for="star3${data.id}" title="text">3 stars</label>
                //         <input ${data.nilai == 2? 'checked': ''} onclick="rate(${data.id}, 2, ${data.nilai})" type="radio" id="star2${data.id}" name="rate${data.id}" value="2" />
                //         <label for="star2${data.id}" title="text">2 stars</label>
                //         <input ${data.nilai == 1? 'checked': ''} onclick="rate(${data.id}, 1, ${data.nilai})" type="radio" id="star1${data.id}" name="rate${data.id}" value="1" />
                //         <label for="star1${data.id}" title="text">1 star</label>
                //     </div>
                // `;

                    //         return `
                //         <div class="rating">

                //             <input ${data.nilai == 5? 'checked': ''} onclick="rate(${data.id}, 5, ${data.nilai})" type="radio" name="rating${data.id}" value="5" id="star5${data.id}"><label for="star5${data.id}">☆</label>
                //             <input ${data.nilai == 4? 'checked': ''} onclick="rate(${data.id}, 4, ${data.nilai})" type="radio" name="rating${data.id}" value="4" id="star4${data.id}"><label for="star4${data.id}">☆</label>
                //             <input ${data.nilai == 3? 'checked': ''} onclick="rate(${data.id}, 3, ${data.nilai})" type="radio" name="rating${data.id}" value="3" id="star3${data.id}"><label for="star3${data.id}">☆</label>
                //             <input ${data.nilai == 2? 'checked': ''} onclick="rate(${data.id}, 2, ${data.nilai})" type="radio" name="rating${data.id}" value="2" id="star2${data.id}"><label for="star2${data.id}">☆</label>
                //             <input ${data.nilai == 1? 'checked': ''} onclick="rate(${data.id}, 1, ${data.nilai})" type="radio" name="rating${data.id}" value="1" id="star1${data.id}"><label for="star1${data.id}">☆</label>
                //             </div>
                //         `;
                    //     },
                    //     orderable: false,
                    //     searchable: false,
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ]
            });
        });

        async function rate(id, val, cur_val) {
            const {
                value: text
            } = await Swal.fire({
                input: 'textarea',
                inputLabel: 'Komentar',
                inputPlaceholder: 'Ketik komentar disini...',
                inputAttributes: {
                    'aria-label': 'Ketik komentar disini'
                },
                showCancelButton: true,
                confirmButtonText: 'Simpan',
            })
            if (text || text == '') { // jika ditekan tombil SImpan
                $.ajax({
                    type: "POST",
                    url: "{{ route('rate_pekerjaan') }}",
                    data: {
                        'id': id,
                        'rate': val,
                        'komentar': text,
                    }
                }).done(function(data) {
                    if (data.status == 'success') {
                        Swal.fire({
                            text: data.message,
                            icon: 'success'
                        })
                    } else {
                        Swal.fire("Maaf!", "Terjadi kesalahan!", "error")
                    }
                })
                $('#datatables').DataTable().ajax.reload();
            } else { // jika tekan cancel
                // Swal.fire('text')
                $(`#star${val}${id}`).prop('checked', false)
                $(`#star${cur_val}${id}`).prop('checked', true)
            }
        }

        function change_status(id, status) {
            var text = status == 'belum' ? 'Ulangi' : 'Selesaikan';
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: `${text} pekerjaan.`,
                type: "warning",
                showCancelButton: true,
                showCloseButton: true,
                icon: 'warning',
                cancelButtonText: 'Batal',
                confirmButtonText: text,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('change_status_pekerjaan') }}",
                        data: {
                            'id': id,
                            'status': status,
                        }
                    }).done(function(data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                text: data.message,
                                icon: 'success'
                            })
                        } else {
                            Swal.fire("Maaf!", "Terjadi kesalahan!", "error")
                        }
                    })
                    $('#datatables').DataTable().ajax.reload();
                }
            });
        }

        function detail_sebelum(id) {
            $('#photo_pekerjaan').attr('src', ' ');
            $('#pan').attr('data-big', ' ');

            $('#modalCenter').modal('show');
            $.ajax({
                type: "POST",
                url: "{{ route('detail_pekerjaan') }}",
                data: {
                    id: id
                }
            }).done(function(data) {
                if (data.status == 'success') {
                    var detail_pekerjaan = data.data;
                    $('#pan').attr('data-big', detail_pekerjaan.foto_sebelum);
                    $('#photo_pekerjaan').attr('src', detail_pekerjaan.foto_sebelum);
                    $('#nama_pegawai').text(detail_pekerjaan.detail_user.nama);
                    $('#latitude').text(detail_pekerjaan.latitude_sebelum);
                    $('#longitude').text(detail_pekerjaan.longitude_sebelum);
                    $('#tanggal').text(detail_pekerjaan.time_take_sebelum.split(' ')[0]);
                    $('#time_take').text(detail_pekerjaan.time_take_sebelum.split(' ')[1]);
                } else {
                    Swal.fire('Maaf!', 'Terjadi kesalahan', 'error')
                }
            })
        }

        function detail_sesudah(id) {
            $('#photo_pekerjaan').attr('src', ' ');
            $('#pan').attr('data-big', ' ');

            $('#modalCenter').modal('show');
            $.ajax({
                type: "POST",
                url: "{{ route('detail_pekerjaan') }}",
                data: {
                    id: id
                }
            }).done(function(data) {
                if (data.status == 'success') {
                    var detail_pekerjaan = data.data;
                    $('#pan').attr('data-big', detail_pekerjaan.foto_sesudah);
                    $('#photo_pekerjaan').attr('src', detail_pekerjaan.foto_sesudah);
                    $('#nama_pegawai').text(detail_pekerjaan.detail_user.nama);
                    $('#latitude').text(detail_pekerjaan.latitude_sesudah);
                    $('#longitude').text(detail_pekerjaan.longitude_sesudah);
                    $('#tanggal').text(detail_pekerjaan.time_take_sesudah.split(' ')[0]);
                    $('#time_take').text(detail_pekerjaan.time_take_sesudah.split(' ')[1]);
                } else {
                    Swal.fire('Maaf!', 'Terjadi kesalahan', 'error')
                }
            })
        }
    </script>
@endpush
