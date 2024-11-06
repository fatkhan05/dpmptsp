@extends('layouts.main')

@section('title')
    Data Pekerjaan
@endsection

@push('css')
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Data Pekerjaan</h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah
                        Pekerjaan</button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal">
                        Import Excel </button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pekerjaan</th>
                            <th>Formasi</th>
                            {{-- <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th> --}}
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
    @include('pekerjaan.modal.import_excel')
@endsection

@push('js')
    <script>
        function form_page(id = 0) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route('form_pekerjaan') }}',
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
                    $.post("{{ route('delete_pekerjaan') }}", {
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
            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pekerjaan') }}",
                    type: "get",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        render: function(data, type, row) {
                            return `<div style="white-space:normal;width:90%">${data}</div>`;
                        }
                    },
                    {
                        data: 'kategori_has_pekerjaan',
                        name: 'kategori_has_pekerjaan',
                        render: function(data, type, row) {
                            badges = '';
                            // console.log(data);
                            data.forEach(element => {
                                if (element.kategori_pegawai) {
                                    badges +=
                                        `<span class="badge rounded-pill bg-label-primary mx-1 text-capitalize">${element.kategori_pegawai.nama}</span>`;
                                }
                            });
                            return badges;
                        },
                        orderable: false,
                    },
                    // {
                    //     data: 'mulai',
                    //     name: 'mulai',
                    //     render: function(data, type, row) {
                    //         return data ?? '-';
                    //     }
                    // },
                    // {
                    //     data: 'selesai',
                    //     name: 'selesai',
                    //     render: function(data, type, row) {
                    //         return data ?? '-';
                    //     }
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
