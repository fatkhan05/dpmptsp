@extends('layouts.main')

@section('title')
    Perizinan
@endsection

@push('css')
@endpush

@section('content')
    <div id="main-page">
        <div class="card">
            <h5 class="card-header">Riwayat Perizinan</h5>
            <div class="row mx-4 mb-3">
                <label class="col-sm-1 col-form-label" for="basic-default-name">Tanggal</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="tanggal_start">
                </div>
                <label class="col-sm-1 col-form-label" for="basic-default-name">s/d</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="tanggal_end">
                </div>
            </div>
            <div class="table-responsive text-nowrap px-4 pb-4">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Kategori Pegawai</th>
                            <th>Kategori Izin</th>
                            <th>Mulai Izin</th>
                            <th>Lama Izin</th>
                            <th>Selesai Izin</th>
                            <th>Keterngan</th>
                            <th>Status</th> 
                            {{-- // TODO otomatis status menjadi selesai ketika waktu selesai terlewati --}}
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
@endsection

@push('js')
    <script>
        // CHANGE STATUS
        function change_status(id, status) {
            var text = status.charAt(0).toUpperCase() + status.slice(1);
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: `${text} izin.`,
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
                        url: "{{ route('change_status_perizinan') }}",
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

        $(document).ready(function () {
            // DATEPICKER
            var tanggal = $('.datepicker');
            tanggal.datepicker({
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
            })

            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('riwayat_perizinan') }}",
                    type: "get",
                    data: {
                        'tanggal_start': function() {
                            return $('#tanggal_start').val()
                        },
                        'tanggal_end': function() {
                            return $('#tanggal_end').val()
                        }
                    },
                },
                columns: [
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
                        data: 'kategori_izin.nama',
                        name: 'kategori_izin.nama',
                    },
                    {
                        data: 'mulai',
                        name: 'mulai',
                    },
                    {
                        data: 'lama',
                        name: 'lama',
                        render: function (data, type, row) {
                            return `${data} Hari`
                        }
                    },
                    {
                        data: 'selesai',
                        name: 'selesai',
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ]
            });
        })
    </script>
@endpush