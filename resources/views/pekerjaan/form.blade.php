<style>
    .fade {
        transition-duration: 0s;
    }
</style>


<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($pekerjaan) ? 'Tambah kategori baru' : 'Edit kategori' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row align-items-end">
            <input type="hidden" name="pekerjaan_id" value="{{ $pekerjaan->id ?? '' }}">
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Nama Pekerjaan</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pekerjaan"
                    value="{{ $pekerjaan->nama ?? '' }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label for="kategori_pegawai_id" class="form-label">Bidang / Kategori Pegawai Pelaksana</label>
                <select class="form-select" id="kategori_pegawai_id" name="kategori_pegawai_id[]" multiple="multiple"
                    aria-label="">
                    @foreach ($kategori_pagawai as $kategori)
                        <option {{ in_array($kategori->id, $kategori_has_pekerjaan ?? []) ? 'selected' : '' }}
                            value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3">
                <label class="form-label" for="">Lokasi Kerja</label>
                <div class="form-check mt-3">
                    <input class="form-check-input" {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Dalam' ? 'checked' : '' }}
                        type="radio" name="lokasi_kerja" id="inlineRadio1" value="0" />
                    <label class="form-check-label" for="inlineRadio1">Area Kantor</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Luar' ? 'checked' : '' }}
                        type="radio" name="lokasi_kerja" id="inlineRadio2" value="1" />
                    <label class="form-check-label" for="inlineRadio2">Luar Area Kantor</label>
                </div>
            </div>
            <div class="mb-3 col-md-6 {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Dalam' ? 'fade' : '' }}"
                id="lokasi_pekerjaan">
                <label class="form-label" for="alamat_lokasi_kerja">Alamat lokasi pekerjaan</label>
                <textarea id="alamat_lokasi_kerja" name="alamat_lokasi_kerja" class="form-control" placeholder="">{{ $pekerjaan->alamat ?? '' }}</textarea>
            </div>
            <div class="mb-3 col-md-3 {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Dalam' ? 'fade' : '' }}"
                id="latlong_pekerjaan">
                <div class="row">
                    <div class="col-lg-12 mb-1">
                        <label class="form-label" for="latitude">Latitude - Longitude</label>
                        <input readonly type="text" class="form-control form-control-sm" id="latitude"
                            name="latitude" value="{{ $pekerjaan->latitude ?? '' }}">
                    </div>
                    <div class="col-lg-12">
                        <input readonly type="text" class="form-control form-control-sm" id="longitude"
                            name="longitude" value="{{ $pekerjaan->longitude ?? '' }}">
                    </div>
                </div>
            </div>
            {{-- <div class="mb-3 col-md-3">
                <label class="form-label" for="tanggal_mulai_pekerjaan">Tanggal dan Jam Mulai Pekerjaan</label>
                <input type="text" class="form-control datepicker" id="tanggal_mulai_pekerjaan" name="tanggal_mulai_pekerjaan" value="{{ explode(' ', ($pekerjaan->mulai ?? ''))[0] }}">
            </div>
            <div class="mb-3 col-md-3">
                <input type="time" class="form-control" id="" name="jam_mulai_pekerjaan" value="{{ explode(' ', ($pekerjaan->mulai ?? ''))[1] ?? '' }}">
            </div>
            
            <div class="mb-3 col-md-3">
                <label class="form-label" for="selesai_pekerjaan">Tanggal dan Jam Selesai Pekerjaan</label>
                <input type="text" class="form-control datepicker" id="selesai_pekerjaan" name="tanggal_selesai_pekerjaan" placeholder="" value="{{ explode(' ', ($pekerjaan->selesai ?? ''))[0] }}" />
            </div>
            <div class="mb-3 col-md-3">
                <input type="time" class="form-control" id="" name="jam_selesai_pekerjaan" value="{{ explode(' ', ($pekerjaan->selesai ?? ''))[1] ?? '' }}">
            </div> --}}
            <div class="mb-3 col-md-3 {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Dalam' ? 'd-none' : '' }}"
                id="surat_perintah">
                <label class="form-label" for="file">Surat Perintah <span>(Max: 2MB)</span></label>
                @if (!empty($pekerjaan) && ($pekerjaan->surat_perintah ?? '') != '')
                    <br>
                    <a class="mb-2" href="{{ asset("images/$pekerjaan->surat_perintah") }}" target="_blank"><i
                            class='bx bx-download'></i> Unduh / lihat surat lampiran</a>
                    {{-- <button type="button" class="btn btn-warning btn-xs" ><i class='bx bx-x'></i></button> --}}
                @endif
                <input type="file" class="form-control mt-3" id="file" name="file" placeholder="" />
            </div>
            <div class="mb-3 col-md-12">
                <button type="button" id="btn-back" class="btn btn-secondary"> Kembali </button>
                <button type="submit" id="btn-submit" class="btn btn-primary mx-3">Simpan</button>
            </div>
        </form>

        <div class="mb-3 row {{ ($pekerjaan->lokasi ?? 'Dalam') == 'Dalam' ? 'd-none' : '' }}" id="map">
            <div class="col">
                <div class="map" id="map"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // SELECT2
        $('#kategori_pegawai_id').select2({
            placeholder: 'Pilih kategori / bidang pegawai'
        });

        // DATEPICKER
        var tanggal = $('.datepicker');
        tanggal.datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            language: "id",
            orientation: "bottom right",
            todayHighlight: true,
            autoclose: true,
            zIndexOffset: 1000
        });

        // LOKASI KERJA
        $('input[name=lokasi_kerja]').change(function() {
            if (this.value == '1') {
                $('#lokasi_pekerjaan').removeClass('fade');
                $('#latlong_pekerjaan').removeClass('fade');
                $('#surat_perintah').removeClass('d-none');
                $('#map').removeClass('d-none');
                setTimeout(function() {
                    map.invalidateSize();
                }, 10);
            } else {
                $('#lokasi_pekerjaan').addClass('fade');
                $('#latlong_pekerjaan').addClass('fade');
                $('#surat_perintah').addClass('d-none');
                $('#map').addClass('d-none');
            }
        });

        // MAP
        var map = L.map('map');
        var marker = L.marker();

        @if (empty($pekerjaan) || ($pekerjaan->latitude ?? '') == '') // DEFAULT MAP
            map = map.setView([-7.464472206311265, 112.4311637878418], 14);
        @else // AMBIL DARI LOKASI KERJA YG ADA
            map = map.setView([{{ $pekerjaan->latitude }}, {{ $pekerjaan->longitude }}], 19);
            marker = L.marker([{{ $pekerjaan->latitude }}, {{ $pekerjaan->longitude }}], {
                icon: marker_home
            }).addTo(map);
        @endif
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // ONCLICK MAP
        function onMapClick(e) {
            marker.remove();
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#radius').attr('readonly', false);

            marker = L.marker([lat, lng], {
                icon: marker_home
            }).addTo(map);
        }
        map.on('click', onMapClick);

        $('#btn-back').click(function() {
            $('#other-page').fadeOut(function() {
                $('#other-page').empty();
                $('#main-page').fadeIn();
            });
        })

        $('#btn-submit').click(function(e) {
            e.preventDefault();
            $('#btn-submit').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            ).attr('disabled', true);
            var data = new FormData($('.form-save')[0]);
            $.ajax({
                url: "{{ route('store_pekerjaan') }}",
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
