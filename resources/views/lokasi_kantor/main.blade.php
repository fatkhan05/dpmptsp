@extends('layouts.main')

@section('title')
    Lokasi Kantor
@endsection

@push('css')
    
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card px-4 pb-4">
            <h5 class="card-header">Lokasi Kantor</h5>
            <div class="row mb-4">
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="form_page()"><i class='bx bx-plus'></i> Tambah Kantor</button>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kantor</th>
                            <th>Alamat</th>
                            <th>Deskripsi</th>
                            <th>Lokasi Map</th>
                            <th>Radius (Meter)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
    
                    </tbody>
                </table>
            </div>

            {{-- <div id="map"></div> --}}
        </div>
    </div>
    {{-- MODAL --}}
    @include('lokasi_kantor.modal.view_map')

    <div id="other-page">

    </div>
@endsection

@push('js')
    <script>
        // LEAFLET MAP
        var map = L.map('map');
        // var map = L.map('map').setView([-0.3157868,115.0294763], 5.2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        $('#modalCenter').on('show.bs.modal', function(){
            setTimeout(function() { 
                map.invalidateSize();
            }, 10);
        });
        
        // // ONCLICK MAP
        // var popup = L.popup();
        // function onMapClick(e) {
        //     popup
        //         .setLatLng(e.latlng)
        //         .setContent("You clicked the map at " + e.latlng.toString())
        //         .openOn(map);
        // }
        // map.on('click', onMapClick);

        // FORM PAGE
        function form_page(id = 0) {
            $('#main-page').hide();
            $.ajax({
                type: 'POST',
                url: '{{ route("form_lokasi_kantor") }}',
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
                    $.post("{{ route('delete_lokasi_kantor') }}", {
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

        function set_circle(latitude, longitude, radius, nama) {
            console.log(latitude);
            console.log(longitude);
            console.log(radius);
            map.setView([latitude, longitude], 19);
            var circle = L.circle([latitude, longitude], {
                color: 'green',
                weight: 1,
                fillColor: '#0f0',
                fillOpacity: 0.25,
                radius: radius
            }).addTo(map);
            circle.bindPopup(nama);
            // MARKER
            var marker = L.marker([latitude, longitude], {icon: marker_home}).addTo(map);
            $('#modalCenter').modal('show');
            
        }

        $(document).ready(function() {
            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('lokasi_kantor') }}",
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
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: 'latitude',
                        name: 'latitude',
                        render: function (data, type, row) {
                            return `<button type="button" class="btn btn-link" onclick="set_circle(${row.latitude}, ${row.longitude}, ${row.radius}, '${row.nama}')" >Lihat Map</button>`
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'radius',
                        name: 'radius',
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
