@extends('layouts.main')

@section('title')
    Pekerjaan Pegawai
@endsection

@push('css')
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Pekerjaan Pegawai</h5>
            <div class="row mx-4 mb-3">
                <label class="col-sm-1 col-form-label" for="basic-default-name">Tanggal</label>
                <div class="col-sm-3">
                    {{-- <input class="form-control" type="date" value="2021-06-18" id="html5-date-input" /> --}}
                    <input type="text" class="form-control" id="datepicker">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah
                        Pekerjaan Untuk Pegawai</button>
                    {{-- <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal"> Import Excel </button> --}}
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Formasi</th>
                            <th>Nama Pekerjaan</th>
                            {{-- <th>Pekerjaan Diberikan</th> --}}
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

    {{-- IMPORT PEGAWAI MODAL --}}
    {{-- @include('pekerjaan.modal.import_excel') --}}
@endsection

@push('js')
    <script>
        function form_page(id = 0) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route('form_pekerjaan_pegawai') }}',
                data: {
                    id: id,
                }
            }).done(function(data) {
                if (data.status) {
                    $('#other-page').html(data.content).fadeIn();
                } else {

                }
            })
        }

        function delete_data(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan lagi.",
                type: "warning",
                showCancelButton: true,
                showCloseButton: true,
                icon: 'warning',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus',
            }).then((result) => {
                if (result.value) {
                    Swal.fire("", "Data berhasil dihapus!", "success");
                    $.post("{{ route('delete_pekerjaan_pegawai') }}", {
                        id: id
                    }).done(function(data) {
                        if (data == 'true') {
                            Swal.fire(data.status, "Data berhasil dihapus!", "success");
                        }
                        $('#datatables').DataTable().ajax.reload();
                    }).fail(function() {
                        Swal.fire("Sorry!", "Gagal menghapus data!", "error");
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {}
            });
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
                    url: "{{ route('pekerjaan_pegawai') }}",
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
                    {
                        data: 'detail_user.nama',
                        name: 'detail_user.nama',
                        render: function(data, type, row) {
                            return `<div style="white-space:normal;width:90%">${data}</div>`;
                        }
                    },
                    {
                        data: 'detail_user.pegawai_has_kategori',
                        name: 'detail_user.pegawai_has_kategori',
                        render: function(data, type, row) {
                            badges = '';
                            // console.log(data);
                            data.forEach(element => {
                                if (element.kategori_pegawai) {
                                    badges +=
                                        `<span  class="badge rounded-pill bg-label-primary mx-1 text-capitalize">${element.kategori_pegawai.nama}</span>`;
                                }
                            });
                            return badges;
                        },
                        orderable: false,
                    },
                    {
                        data: 'pekerjaan.nama',
                        name: 'pekerjaan.nama',
                        render: function(data, type, row) {
                            return `<div style="white-space:normal;width:500px">${data}</div>`;
                        }
                    },
                    // {
                    //     data: 'pegawai_has_pekerjaans.created_at',
                    //     name: 'pegawai_has_pekerjaans.created_at',
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
    </script>
@endpush
