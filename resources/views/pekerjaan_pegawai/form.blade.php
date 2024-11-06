<style>
</style>


<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($pekerjaan) ? 'Tambah kategori baru' : 'Edit kategori' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row align-items-end">
            <input type="hidden" name="pegawai_has_pekerjaan_id" value="{{ $pegawai_has_pekerjaan->id ?? '' }}">
            <div class="mb-3 col-md-12">
                <label for="detail_user_id" class="form-label">Nama Pegawai</label>
                <select class="form-select select2" id="detail_user_id" name="detail_user_id" aria-label="">
                    <option value="">Pilih Pegawai</option>
                    @foreach ($detail_users as $detail_user)
                        <option
                            {{ ($pegawai_has_pekerjaan->detail_user_id ?? 0) == $detail_user->id ? 'selected' : '' }}
                            value="{{ $detail_user->id }}">{{ $detail_user->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-12">
                <label for="pekerjaan_id" class="form-label">Pilih Pekerjaan</label>
                <select class="form-select select2" id="pekerjaan_id" name="pekerjaan_id" aria-label=""
                    {{ empty($pegawai_has_pekerjaan) ? 'disabled' : '' }}>
                    @foreach ($pekerjaans ?? [] as $pekerjaan)
                        <option {{ $pekerjaan->id == $pegawai_has_pekerjaan->pekerjaan_id ? 'selected' : '' }}
                            value="{{ $pekerjaan->id }}">{{ $pekerjaan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="">Jenis Perkerjaan</label>
                    <div class="form-check mt-3">
                        <input name="jenis_pekerjaan" class="form-check-input" type="radio" value="primer"
                            {{ $pegawai_has_pekerjaan->jenis_pekerjaan ?? '' == 'primer' ? 'checked' : '' }}>
                        <label class="form-check-label" for="defaultRadio1">
                            Primer
                        </label>
                    </div>

                    <div class="form-check">
                        <input name="jenis_pekerjaan" class="form-check-input" type="radio" value="sekunder"
                            id="defaultRadio2"
                            {{ $pegawai_has_pekerjaan->jenis_pekerjaan ?? '' == 'sekunder' ? 'checked' : '' }}>
                        <label class="form-check-label" for="defaultRadio2">
                            Sekunder
                        </label>
                    </div>
                </div>

            </div>
            <div class="mb-3 col-md-12">
                <button type="button" id="btn-back" class="btn btn-secondary"> Kembali </button>
                <button type="submit" id="btn-submit" class="btn btn-primary mx-3">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // SELECT2
        $('.select2').select2();

        $('#detail_user_id').change(function() {
            var id = $('#detail_user_id').val();
            $.post("{!! route('get_pekerjaan_kategori') !!}", {
                detail_user_id: id
            }).done(function(data) {
                if (data.length > 0) {
                    var pekerjaan = '';
                    $.each(data, function(k, v) {
                        pekerjaan += '<option value="' + v.id + '">' + v.nama +
                            '</option>';
                    });

                    $('#pekerjaan_id').html(pekerjaan);
                    $('#pekerjaan_id').removeAttr('disabled');
                    $('#pekerjaan_id').select2();
                }
            });
        });

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
                url: "{{ route('store_pekerjaan_pegawai') }}",
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
