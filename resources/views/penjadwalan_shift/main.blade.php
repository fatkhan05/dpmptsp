@extends('layouts.main')

@section('title')
    Penjadwalan Shift 
@endsection

@push('css')
    
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Penjadwalan Shift </h5>
            <div class="row mb-4">
                {{-- <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah Shift Pegawai</button>
                </div> --}}
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>NIK</th>
                            <th>Kategori / Bidang</th>
                            <th>Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>No. HP</th>
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
                url: '{{ route("form_penjadwalan_shift") }}',
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

        $(document).ready(function() {
            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('penjadwalan_shift') }}",
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
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'pegawai_has_kategori',
                        name: 'pegawai_has_kategori',
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
                        data: 'nama_shift',
                        name: 'nama_shift'
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
                        data: 'telepon',
                        name: 'telepon'
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
