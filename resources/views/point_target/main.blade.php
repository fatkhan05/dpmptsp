@extends('layouts.main')

@section('title')
    Point Target
@endsection

@push('css')
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Point Target </h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah Point
                        Target</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Formasi</th>
                            <th>Point</th>
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
                url: '{{ route('form_point_target') }}',
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
                    url: "{{ route('point_target') }}",
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
                        data: 'pegawai_has_kategori',
                        name: 'pegawai_has_kategori',
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
                    {
                        data: 'nilai',
                        name: 'nilai',
                        render: (data, type, row) => {
                            return (data ?? '-') + ` / Hari`;
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
        });
    </script>
@endpush
