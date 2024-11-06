<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($lokasi_kantor) ? 'Tambah lokasi kantor baru' : 'Edit lokasi kantor' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <input type="hidden" name="lokasi_kantor_id" value="{{ $lokasi_kantor->id ?? '' }}">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nama">Nama Kantor</label>
                <input type="text" class="form-control mb-3" id="nama" name="nama" placeholder="Nama Kantor"
                    value="{{ $lokasi_kantor->nama ?? '' }}" />
                <label class="form-label" for="alamat">Alamat Kantor</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Nama Kantor"
                    value="{{ $lokasi_kantor->alamat ?? '' }}" />
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="">{{ $lokasi_kantor->deskripsi ?? '' }}</textarea>
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="latitude">Latitude</label>
                <input readonly type="text" class="form-control" id="latitude" name="latitude" placeholder=""
                    value="{{ $lokasi_kantor->latitude ?? '-' }}" />
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="longitude">Longitude</label>
                <input readonly type="text" class="form-control" id="longitude" name="longitude" placeholder=""
                    value="{{ $lokasi_kantor->longitude ?? '-' }}" />
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="radius">Radius (Meter)</label>
                <input {{ empty($lokasi_kantor) ? 'readonly' : '' }} type="number" class="form-control" id="radius" name="radius" placeholder="Pilih Lokasi kantor pada peta"
                    value="{{ $lokasi_kantor->radius ?? '' }}" />
            </div>
            <div class="mb-3 col-md-12">
                <div class="map" id="new_map"></div>
            </div>
            
            <div class="col-md-12">
                <button type="button" id="btn-back" class="btn btn-secondary"> Kembali </button>
                <button type="submit" id="btn-submit" class="btn btn-primary mx-3">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // LEAFLET MAP
        var new_map = L.map('new_map');

        var marker = L.marker(); 
        var circle = L.circle(); 

        @if (empty($lokasi_kantor)) // DEFAULT MAP
            new_map = new_map.setView([-7.464472206311265,112.4311637878418], 14);
        @else // AMBIL DARI LOKASI KANTOR YG ADA
            new_map = new_map.setView([{{ $lokasi_kantor->latitude }}, {{ $lokasi_kantor->longitude }}], 19);
            circle = L.circle([{{ $lokasi_kantor->latitude }}, {{ $lokasi_kantor->longitude }}], {
                color: 'green',
                weight: 1,
                fillColor: '#0f0',
                fillOpacity: 0.25,
                radius: {{ $lokasi_kantor->radius }}
            }).addTo(new_map);
            marker = L.marker([{{ $lokasi_kantor->latitude }}, {{ $lokasi_kantor->longitude }}], {icon: marker_home}).addTo(new_map);
        @endif
        // var new_map = L.map('new_map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(new_map);
        
        $('#btn-back').click(function() {
            $('#other-page').fadeOut(function() {
                $('#other-page').empty();
                $('#main-page').fadeIn();
            });
        })
        
        // ON CHANGE
        $('#radius').on('change keyup', function () {
            circle.remove();
            var lat = $('#latitude').val();
            var lng = $('#longitude').val();
            circle = L.circle([lat, lng], {
                color: 'green',
                weight: 1,
                fillColor: '#0f0',
                fillOpacity: 0.25,
                radius: this.value
            }).addTo(new_map);
        })

        function onMapClick(e) {
            marker.remove();
            circle.remove();
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#radius').attr('readonly', false);

            marker = L.marker([lat, lng], {icon: marker_home}).addTo(new_map);
        }
        new_map.on('click', onMapClick);

        $('#btn-submit').click(function(e) {
            e.preventDefault();
            $('#btn-submit').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            ).attr('disabled', true);
            var data = new FormData($('.form-save')[0]);
            $.ajax({
                url: "{{ route('store_lokasi_kantor') }}",
                type: 'POST',
                data: data,
                async: true,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data) {
                if (data.status == 'success') {
                    Swal.fire({
                        text: data.message,
                        icon: 'success'
                    });
                    $('#other-page').fadeOut(function() {
                        $('#other-page').empty();
                        $('#main-page').fadeIn();
                    });
                    $('#datatables').DataTable().ajax.reload();
                } else if (data.status == 'error') {
                    $('#btn-submit').html('Simpan').removeAttr(
                        'disabled');
                    Swal.fire('Maaf !', data.message, 'warning');
                } else {
                    Swal.fire('ERROR', 'Terjadi Kesalahan', 'error');
                }
            }).fail(function() {
                Swal.fire("MAAF !", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
                $('#btn-submit').html('Simpan').removeAttr(
                    'disabled');
            });
        });
    })
</script>
