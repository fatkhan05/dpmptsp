<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($detail_user_point_target) ? 'Tambah' : 'Edit' }} Point Target</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nilai">Point</label>
                <input type="number" class="form-control mb-3" id="nilai" name="nilai"
                    placeholder="Jumlah point perhari"
                    value="{{ $detail_user_point_target->point_target->nilai ?? '' }}" />
                <div class="form-text">Target point diatas dihitung perhari.</div>
            </div>
            {{-- <div class="mb-3 col-md-4">
                <label class="form-label" for="kategori">Kategori Point</label>
                <select class="form-select" id="kategori" name="kategori" aria-label="">
                    @foreach ($kategoris as $kategori)
                        <option {{ ($detail_user_point_target->point_target->kategori ?? '') == $kategori->kategori ? 'selected' : '' }} value="{{ $kategori->kategori }}">{{ $kategori->kategori }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="mb-3 col-md-6">
                <label class="form-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="">{{ $detail_user_point_target->deskripsi ?? '' }}</textarea>
            </div>
            <div class="mb-3 col-md-4">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="semua_pegawai" value="1"
                        id="semua_pegawai" {{ empty($detail_user_point_target) ? 'checked' : '' }} />
                    <label class="form-check-label" for="semua_pegawai" id="label_status">Semua Pegawai</label>
                </div>
            </div>
            <div class="mb-3 col-md-12 {{ empty($detail_user_point_target) ? 'd-none' : '' }}" id="pilih_pegawai">
                <label class="form-label" for="detail_user_id">Pilih Pegawai</label>
                <select class="form-select" id="detail_user_id" name="detail_user_id[]" multiple="multiple"
                    aria-label="">
                    @foreach ($detail_users as $detail_user)
                        <option {{ ($detail_user_point_target->id ?? '') == $detail_user->id ? 'selected' : '' }}
                            value="{{ $detail_user->id }}">{{ $detail_user->nama }}</option>
                    @endforeach
                </select>
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
        // SELECT2
        $('#detail_user_id').select2();
        $('#kategori').select2({
            tags: true,
        });

        $('input[name=kategori]').change(function() {
            console.log(this.value);
        });

        $('#semua_pegawai').change(function() {
            if ($('#semua_pegawai').is(':checked')) {
                $('#pilih_pegawai').addClass('d-none')
            } else {
                $('#pilih_pegawai').removeClass('d-none');
                $('#detail_user_id').select2();
            }
        })

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
                url: "{{ route('store_point_target') }}",
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
