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
            <h5 class="card-header">Peta Sebaran Pegawai </h5>
            <table class="table table-borderless" style="width: 500px">
                <tr>
                    <td>Tanggal</td>
                    <td style="width: 300px">: {{ date('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td>Pukul</td>
                    <td>: {{ date('H:i') }}</td>
                </tr>
                <tr>
                    <td>Status Waktu</td>
                    <td>: {{ $status_waktu }}</td>
                </tr>
                <tr>
                    <td>Lokasi Kantor</td>
                    <td>
                        <select name="lokasi_kantor_id" class="form-select" id="lokasi_kantor_id">
                            @foreach ($lokasi_kantor as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>
            
            {{-- MAP --}}
            <div class="map mb-4" id="map"></div>

            <div class="row mx-4 mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="tanggal">
                </div>
            </div>
            {{-- Table --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="datatables">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                            <th>Status</th>
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
    @php
        $kantor_utama = $lokasi_kantor[0] ?? null;
    @endphp
    <script>
        function peringatan(player_id) { // TODO ngirim pemberitahuan ke mobile
            $.ajax({
                type: 'POST',
                url: '{{ route("notification") }}',
                data: {
                    'player_id': player_id,
                    'heading': 'Peringatan!',
                    'content': 'Anda berada di luar area kantor',
                }
            }).done(function (data) {
                if (data.status) {
                    Swal.fire({
                        text: `Pegawai Berhasil Diperingatkan`,
                        icon: 'success'
                    });
                } else {
                    Swal.fire({
                        text: `Pegawai Gagal Diperingatkan`,
                        icon: 'error'
                    });
                    console.log(data.message);
                }
            })
        }

        $(document).ready(function() {
            // MAP
            var map = L.map('map');
            map = map.setView([{{ $kantor_utama->latitude ?? -7.464472206311265}}, {{ $kantor_utama->longitude ?? 112.4311637878418 }}], 19); 
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // CIRCEL DAN MARKER SETIAP LOKASI KANTOR
            @foreach ($lokasi_kantor as $lokasi)
                // CIRCLE
                var circle = L.circle([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {
                    color: 'green',
                    weight: 1,
                    fillColor: '#0f0',
                    fillOpacity: 0.25,
                    radius: {{ $lokasi->radius }}
                }).addTo(map);
                // MARKER
                marker = L.marker([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {icon: marker_home}).addTo(map);
                marker.bindPopup('{{ $lokasi->nama }} <br> {{ $lokasi->alamat }}')
            @endforeach

            // MARKER UNTUK SETIAP PEGAWAI
            @foreach ($lokasi_pegawai as $lokasi)
                @php
                    $player_id = $lokasi->detail_user->user->player_id;
                    // LIST KATEGORI PEGAWAI
                    $kategori_array = [];
                    foreach ($lokasi->detail_user->pegawai_has_kategori as $pegawai_has_kategori) {
                        array_push($kategori_array, $pegawai_has_kategori->kategori_pegawai->nama);
                    }
                    $kategori_value = implode(', ', $kategori_array);
                @endphp
                // MARKER
                var popup = `
                    {{ $lokasi->detail_user->nama }} ({{ $kategori_value }}) <br>
                    {!! $lokasi->status != 1 ? "<button type='button' class='btn btn-warning btn-sm' onclick='peringatan($player_id)'>Peringatkan</button>" : '' !!}
                `;
                marker = L.marker([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {icon: {{ $lokasi->status == 1 ? 'marker_success' : 'marker_danger' }}}).addTo(map);
                marker.bindPopup(popup);
            @endforeach

            // CHANGE KANTOR
            $('#lokasi_kantor_id').change(function (){
                var lokasi_kantor_id = $('#lokasi_kantor_id').val();
                console.log(lokasi_kantor_id);
                $.ajax({
                    type: 'GET',
                    url: '{{ route("get_lokasi_kantor") }}',
                    data: {
                        lokasi_kantor_id: lokasi_kantor_id,
                    }
                }).done(function (data) {
                    if (data.status) {
                        // console.log(data.data);
                        var lokasi_kantor = data.data;
                        map = map.setView([lokasi_kantor.latitude, lokasi_kantor.longitude], 19);
                    } else {
                        console.log(data.message);
                    }
                })
            })
            
            // DATEPICKER
            var tanggal = $('#tanggal');
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

            // SELECT2
            $('#lokasi_kantor_id').select2({
                placeholder: 'Pilih kategori / bidang pegawai'
            });

            // DATATABLES
            var dTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('sebaran_pegawai') }}",
                    type: "get",
                    data: {
                        'tanggal': function() {
                            return $('#tanggal').val()
                        },
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'detail_user.nama',
                        name: 'detail_user.nama'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
