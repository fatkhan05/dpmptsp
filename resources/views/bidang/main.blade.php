@extends('layouts.main')

@section('title')
    Kategori Bidang
@endsection

@push('css')
    <style>
        table td {
            max-width: 120px;
            white-space: nowrap;
            text-overflow: ellipsis;
            word-break: break-all;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Kategori Bidang </h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah
                        Kategori Bidang</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped table-hover" id="datatables" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Bidang</th>
                            <th>Nama Bidang</th>
                            <th>Deskripsi</th>
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
                url: '{{ route('form_kategori_bidang') }}',
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

        $(document).ready(function() {
            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bidang') }}",
                    type: "get",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'bidang_deskripsi',
                        name: 'bidang_deskripsi',
                        render: function(data, type, row) {

                            return `<div style="width: 200px;"><span class="overflow-hidden" >${data}</span></div>`;
                        },
                        orderable: false,
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
