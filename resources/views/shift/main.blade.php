@extends('layouts.main')

@section('title')
    Data Master Shift 
@endsection

@push('css')
    
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Data Master Shift </h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah Shift</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Shift</th>
                            <th>Kategori Pegawai</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
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
        // FORM PAGE
        function form_page(id = 0) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route("form_shift") }}',
                data: {
                    id: id,
                }
            }).done(function (data) {
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
                    $.post("{{ route('delete_shift') }}", {
                        id: id
                    }).done(function(data) {
                        if (data == 'true') {
                            Swal.fire(data.status, "Data berhasil dihapus!", "success");
                        }
                        $('#datatables').DataTable().ajax.reload();
                    }).fail(function() {
                        Swal.fire("Sorry!", "Gagal menghapus data!", "error");
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                }
            });
        }

        $(document).ready(function() {
            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('shift') }}",
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
                        name: 'nama'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        render: function (data, type, row){
                            return data ?? 'Umum';
                        }
                    },
                    {
                        data: 'mulai',
                        name: 'mulai'
                    },
                    {
                        data: 'selesai',
                        name: 'selesai'
                    },
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