<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($detail_user) ? 'Tambah shift pegawai baru' : 'Ubah Shift Pegawai' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <input type="hidden" name="detail_user_id" value="{{ $detail_user->id ?? '' }}">
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Nama pegawai</label>
                <input readonly type="text" class="form-control mb-3" id="nama" name="nama" placeholder=""
                    value="{{ $detail_user->nama ?? '' }}" />
            </div>
            @php
                $kategori_array = [];
                foreach ($detail_user->pegawai_has_kategori ?? [] as $pegawai_has_kategori) {
                    array_push($kategori_array, $pegawai_has_kategori->kategori_pegawai->nama);
                    print_r($pegawai_has_kategori->kategori_pegawai->nama);
                }
                $kategori_value = implode(', ', $kategori_array);
                print_r($kategori_array);
            @endphp
            <div class="mb-3 col-md-12">
                <label for="kategori_pegawai" class="form-label">Bidang / Kategori Pegawai</label>
                <input readonly type="text" class="form-control mb-3" id="kategori_pegawai" name="kategori_pegawai" placeholder=""
                    value="{{ $kategori_value }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label" for="shift_id">Pilih Shift</label>
                <select class="form-select" id="shift_id" name="shift_id" aria-label="">
                    <option value="1">Umum</option>
                    @foreach ($shifts as $shift)
                        <option {{ $shift->id == ($detail_user->shift_id ?? 0 ) ? 'selected' : '' }} value="{{ $shift->id }}">{{ $shift->nama }} ({{ $shift->kategori_pegawai->nama }})</option>
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
        $('#shift_id').select2();

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
                url: "{{ route('store_penjadwalan_shift') }}",
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
