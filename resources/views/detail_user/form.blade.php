<style>
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($detail_user) ? 'Tambah data Pegawai baru' : 'Edit Pegawai' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <input type="hidden" name="detail_user_id" value="{{ $detail_user->id ?? '' }}">
            <div class="col-md-6 align-self-center"> {{-- bagian kiri --}}
                <div class="row">
                    <div class="mb-3 col-md-12 text-center">
                        <div class="p-profile">
                            <a href="#" class="{{ !empty($detail_user->photo) ? 'pan' : '' }}"
                                data-big="{{ asset('images') . '/' . ($detail_user->photo ?? '') }}">
                                <img src="{{ asset('images') . '/' . ($detail_user->photo ?? 'no-image/image.png') }}"
                                    alt="Photo Profile" class="rounded-circle"
                                    style="background-color: rgb(211, 211, 211)">
                            </a>
                        </div>
                    </div>
                    @if ($mode == 'w')
                        <div class="mb-3 col-md-12">
                            <input class="form-control" type="file" id="formFile" name="photo"
                                {{ $mode == 'r' ? 'disabled' : '' }} />
                        </div>
                    @endif
                    <div class="col-md-12">
                        <label class="form-label" for="nik">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" class="form-control mb-3" id="nik" name="nik" placeholder=""
                            {{ $mode == 'r' ? 'disabled' : '' }} value="{{ $detail_user->nik ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control mb-3" id="nama" name="nama" placeholder=""
                            {{ $mode == 'r' ? 'disabled' : '' }} value="{{ $detail_user->nama ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="nomor">Nomor Pegawai</label>
                        <input type="text" class="form-control mb-3" id="nomor" name="nomor" placeholder=""
                            {{ $mode == 'r' ? 'disabled' : '' }} value="{{ $detail_user->user->nomor ?? '' }}" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="basic-default-password32">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input {{ !empty($detail_user) ? 'disabled' : '' }} type="password"
                                            class="form-control" id="basic-default-password32" name="password_field"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="basic-default-password" />
                                        <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($detail_user) && $mode == 'w')
                                <div class="col-auto">
                                    <button type="button" class="btn btn-white" id="edit-password"><i
                                            class="bx bx-pencil bx-sm"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="telepon">Telepon</label>
                        <input type="text" class="form-control mb-3" id="telepon" name="telepon" placeholder=""
                            {{ $mode == 'r' ? 'disabled' : '' }} value="{{ $detail_user->telepon ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="tgl_mulai_kerja">Tanggal Mulai Bekerja</label>
                        <input type="text" class="form-control mb-3 datepicker" id="tgl_mulai_kerja"
                            name="tgl_mulai_kerja" placeholder="" {{ $mode == 'r' ? 'disabled' : '' }}
                            value="{{ $detail_user->tgl_mulai_kerja ?? '' }}" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="shift_id">Shift Kerja</label>
                        <select class="form-select mb-3 select2" id="shift_id" name="shift_id"
                            {{ $mode == 'r' ? 'disabled' : '' }}>
                            @foreach ($shifts as $shift)
                                <option {{ $shift->id == ($detail_user->shift_id ?? 1) ? 'selected' : '' }}
                                    value="{{ $shift->id }}">{{ $shift->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label" for="status">Status Pekerjaan</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                id="status" {{ ($detail_user->user->status ?? '1') == 1 ? 'checked' : '' }}
                                {{ $mode == 'r' ? 'disabled' : '' }} />
                            <label class="form-check-label" for="status" id="label_status">Aktif</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-select mb-3 select2" id="jenis_kelamin" name="jenis_kelamin"
                            {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option selected value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control mb-3" id="tempat_lahir" name="tempat_lahir"
                            placeholder="" {{ $mode == 'r' ? 'disabled' : '' }}
                            value="{{ $detail_user->tempat_lahir ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="tgl_lahir">Tanggal Lahir</label>
                        <input type="text" class="form-control mb-3 datepicker" id="tgl_lahir" name="tgl_lahir"
                            placeholder="" {{ $mode == 'r' ? 'disabled' : '' }}
                            value="{{ $detail_user->tgl_lahir ?? '' }}" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="">User Sebagai</label>
                        <div class="form-group">
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input" type="radio" name="level_user" value="pegawai"
                                    {{ empty($detail_user) ? 'checked' : ($detail_user->user->level != 4 ? 'checked' : '') }}
                                    {{ $mode == 'r' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Pegawai</label>
                            </div>
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input" type="radio" name="level_user"
                                    value="kepala_dinas"
                                    {{ empty($detail_user) ? '' : ($detail_user->user->level == 4 ? 'checked' : '') }}
                                    {{ $mode == 'r' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">Kepala Dinas</label>
                            </div>
                        </div>
                    </div>
                    {{-- CEK APAKAH USER MERUPAKN KEPALA DINAS KALAU IYA MAKA HIDDEN FORM BERIKUT --}}
                    <div class="col-md-12 mb-3" id="el_bidang"
                        style="{{ empty($detail_user) ? '' : ($detail_user->user->level == 4 ? 'display:none' : '') }}">
                        <label class="form-label" for="bidang_id">Bidang</label>
                        <select class="form-select mb-3 select2" id="bidang_id" name="bidang_id" style="width: 100%"
                            {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option value="">Pilih Bidang</option>
                            @foreach ($bidangs as $bidang)
                                <option {{ $bidang->id == ($detail_user->bidang_id ?? 0) ? 'selected' : '' }}
                                    value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- CEK APAKAH USER MERUPAKN KEPALA DINAS KALAU IYA MAKA HIDDEN FORM BERIKUT --}}
                    <div class="col-md-12 mb-3" id="el_kategori_pegawai"
                        style="{{ empty($detail_user) ? '' : ($detail_user->user->level == 4 ? 'display:none' : '') }}">
                        <label class="form-label" for="kategori_pegawai_id">Formasi</label>
                        <select class="form-select mb-3 select2" id="kategori_pegawai_id" style="width: 100%"
                            name="kategori_pegawai_id[]" multiple="multiple"
                            {{ empty($detail_user) ? 'disabled' : '' }} {{ $mode == 'r' ? 'disabled' : '' }}>
                            {{-- <option value="">Pilih Kategori / bidang pegawai</option> --}}
                            <option value="1" {{ in_array('1', $pegawai_has_kategori ?? []) ? 'selected' : '' }}>
                                Admin</option>
                            <option value="penilai"
                                {{ empty($detail_user) ? '' : ($detail_user->user->level == 3 ? 'selected' : '') }}>
                                Penilai
                            </option>
                            @foreach ($kategori_pegawais as $kategori_pegawai)
                                <option
                                    {{ in_array($kategori_pegawai->id, $pegawai_has_kategori ?? []) ? 'selected' : '' }}
                                    value="{{ $kategori_pegawai->id }}">{{ $kategori_pegawai->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-12" id="username_admin" {{ !in_array(1, $pegawai_has_kategori ?? []) ? 'hidden' : '' }}> --}}
                    <div class="col-md-12" id="username_admin" >
                        <label class="form-label" for="username_field">Username</label>
                        <input type="text" class="form-control mb-3" id="username_field" name="username_field"
                            placeholder="" {{ $mode == 'r' ? 'disabled' : '' }}
                            value="{{ $detail_user->user->username ?? '' }}" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control" placeholder="" {{ $mode == 'r' ? 'disabled' : '' }}>{{ $detail_user->alamat ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="provinsi">Provinsi</label>
                        <select class="form-select mb-3 select2" id="provinsi" name="provinsi"
                            {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinsis as $provinsi)
                                <option {{ $provinsi->id == ($detail_user->provinsi_id ?? 0) ? 'selected' : '' }}
                                    value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="kabupaten">kabupaten / Kota</label>
                        <select class="form-select mb-3 select2" id="kabupaten" name="kabupaten"
                            {{ empty($detail_user) ? 'disabled' : '' }} {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option value="">Pilih Kab. / Kota</option>
                            @foreach ($kabupatens ?? [] as $kabupaten)
                                <option {{ $kabupaten->id == ($detail_user->kabupaten_id ?? 0) ? 'selected' : '' }}
                                    value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="kecamatan">Kecamatan</label>
                        <select class="form-select mb-3 select2" id="kecamatan" name="kecamatan"
                            {{ empty($detail_user) ? 'disabled' : '' }} {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($kecamatans ?? [] as $kecamatan)
                                <option {{ $kecamatan->id == ($detail_user->kecamatan_id ?? 0) ? 'selected' : '' }}
                                    value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="desa">Desa</label>
                        <select class="form-select mb-3 select2" id="desa" name="desa"
                            {{ empty($detail_user) ? 'disabled' : '' }} {{ $mode == 'r' ? 'disabled' : '' }}>
                            <option value="">Pilih Desa</option>
                            @foreach ($desas ?? [] as $desa)
                                <option {{ $desa->id == ($detail_user->desa_id ?? 0) ? 'selected' : '' }}
                                    value="{{ $desa->id }}">{{ $desa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="button" id="btn-back" class="btn btn-secondary"> Kembali </button>
                @if ($mode == 'w')
                    <button type="submit" id="btn-submit" class="btn btn-primary mx-3">Simpan</button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // IMAGE PAN
        $(".pan").pan();

        // Toggle Password Visibility
        window.Helpers.initPasswordToggle();
        // SELECT2
        $('.select2').select2();

        // DATEPICKER
        var tanggal = $('.datepicker');
        tanggal.datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            language: "id",
            orientation: "bottom left",
            todayHighlight: true,
            autoclose: true
        });

        // EDIT PASSWORD BUTTON
        $('#edit-password').click(function() {
            var password = $('#basic-default-password32');
            if (password.is(":disabled")) {
                password.prop("disabled", false);
            } else {
                password.prop("disabled", true);
            }
        })

        // STATUS
        $('#status').change(function() {
            if ($('#status').is(':checked')) {
                $('#label_status').text('Aktif')
            } else {
                $('#label_status').text('Tidak Aktif')
            }
        })

        // KATEGORI PEGAWAI
        $('#kategori_pegawai_id').change(function() {
            var kategori_pegawai_id = $('#kategori_pegawai_id').val();
            if (kategori_pegawai_id.includes('1')) {
                $('#username_admin').attr('hidden', false);
            } else {
                $('#username_admin').attr('hidden', false);
                // $('#username_admin').attr('hidden', true);
            }
        })
        //DISABLE BIDANG DAN FORMASI JIKA SAMA DENGAN KEPALA
        $('input[type=radio][name=level_user]').change(function() {
            if (this.value == 'kepala_dinas') {
                $("#bidang_id").val(null).change();
                $('#kategori_pegawai_id').val(null).trigger('change');
                $('#el_bidang').hide();
                $('#el_kategori_pegawai').hide();
            } else {
                $('#el_bidang').show();
                $('#el_kategori_pegawai').show();
            }

        });
        //TRIGGER FORMASI
        $('#bidang_id').change(function() {
            var id = $('#bidang_id').val();
            $('#kategori_pegawai_id').prop('disabled', true);
            $('#kategori_pegawai_id').val(null).trigger('change');
            $.post("{!! route('getFormasi') !!}", {
                id: id
            }).done(function(data) {
                var kategori_pegawai_id =
                    '<option value="1">Admin</option><option value="penilai">Penilai</option>';
                $.each(data, function(k, v) {
                    kategori_pegawai_id += '<option value="' + v.id + '">' + v
                        .nama +
                        '</option>';
                });
                $('#kategori_pegawai_id').html(kategori_pegawai_id);
                $('#kategori_pegawai_id').removeAttr('disabled');
                $('#kategori_pegawai_id').select2();
            });
        });

        // TRIGGER KABUPATEN
        $('#provinsi').change(function() {
            var id = $('#provinsi').val();
            $.post("{!! route('getKabupaten') !!}", {
                id: id
            }).done(function(data) {
                if (data.length > 0) {
                    var kabupaten = '<option>Pilih Kabupaten</option>';
                    $.each(data, function(k, v) {
                        kabupaten += '<option value="' + v.id + '">' + v.nama +
                            '</option>';
                    });

                    $('#kabupaten').html(kabupaten);
                    $('#kabupaten').removeAttr('disabled');
                    $('#kabupaten').select2();
                }
            });
        });

        // TRIGGER KECAMATAN
        $('#kabupaten').change(function() {
            var id = $('#kabupaten').val();
            $.post("{!! route('getKecamatan') !!}", {
                id: id
            }).done(function(data) {
                if (data.length > 0) {
                    var kecamatan = '<option>Pilih Kecamatan</option>';
                    $.each(data, function(k, v) {
                        kecamatan += '<option value="' + v.id + '">' + v.nama +
                            '</option>';
                    });

                    $('#kecamatan').html(kecamatan);
                    $('#kecamatan').removeAttr('disabled');
                    $('#kecamatan').select2();
                }
            });
        });

        // TRIGGER DESA
        $('#kecamatan').change(function() {
            var id = $('#kecamatan').val();
            $.post("{!! route('getDesa') !!}", {
                id: id
            }).done(function(data) {
                if (data.length > 0) {
                    var desa = '<option>Pilih Desa</option>';
                    $.each(data, function(k, v) {
                        desa += '<option value="' + v.id + '">' + v.nama + '</option>';
                    });

                    $('#desa').html(desa);
                    $('#desa').removeAttr('disabled');
                    $('#desa').select2();
                }
            });
        });

        // BUTTON BACK
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
                url: "{{ route('store_pegawai') }}",
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
