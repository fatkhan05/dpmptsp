@extends('layouts.main')

@section('title')
    Data Pegawai
@endsection

@push('css')
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Data Pegawai </h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah
                        Pegawai Baru</button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal">
                        Import Excel </button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>NIK</th>
                            <th>Nomor Pegawai</th>
                            <th>Bidang</th>
                            <th>Formasi</th>
                            <th>Shift</th>
                            <th>Mulai Kerja</th>
                            <th>Alamat</th>
                            <th>No. Hp</th>
                            <th>Status</th>
                            <th>Aksi</th>
                            <th>Created at</th>
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
    @include('detail_user.modal.import_excel')
@endsection

@push('js')
    <script>
        // FORM PAGE
        function form_page(id = 0, mode = 'w') { // mode w = write, r = read
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route('form_pegawai') }}',
                data: {
                    id: id,
                    mode: mode,
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
                    $.post("{{ route('delete_pegawai') }}", {
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
            @if (request()->redirect == 'detail_user')
                form_page({{ request()->detail_user_id }}, 'r');
            @endif

            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pegawai') }}",
                    type: "get",
                },
                order: [[12, 'desc']],
                columnDefs: [
                    {
                        target: 12,
                        visible: false,
                        searchable: false,
                    },
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'user.nomor',
                        name: 'user.nomor'
                    },
                    {
                        data: 'bidang.nama',
                        name: 'bidang.nama',
                        render: function(data, type, row) {
                            console.log(data);
                            badges = '';
                            if (data != null) {
                                badges =
                                    `<span class="badge rounded-pill bg-label-warning mx-1 text-capitalize">${data}</span>`;
                            }

                            return badges;
                        },
                        // orderable: false,
                        // searchable: false
                    },
                    {
                        data: 'pegawai_has_kategori',
                        name: 'pegawai_has_kategori',
                        render: function(data, type, row) {
                            badges = '';
                            console.log(row.user);
                            //KARENA PENILAI TIDAK MASUK PADA DATABASE FORMASI
                            if (row.user.level == 3) {
                                badges +=
                                    '<span class="badge rounded-pill bg-label-primary mx-1 text-capitalize">Penilai</span>';
                            }
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
                    {
                        data: 'nama_shift',
                        name: 'nama_shift'
                    },
                    {
                        data: 'tgl_mulai_kerja',
                        name: 'tgl_mulai_kerja'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'user.status',
                        name: 'user.status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                ]
            });
        });
    </script>
@endpush
