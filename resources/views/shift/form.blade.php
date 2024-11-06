<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($shift) ? 'Tambah data master shift baru' : 'Edit Shift' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <input type="hidden" name="shift_id" value="{{ $shift->id ?? '' }}">
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Nama shift</label>
                <input type="text" class="form-control mb-3" id="nama" name="nama" placeholder="Nama shift"
                    value="{{ $shift->nama ?? '' }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label for="kategori_pegawai_id" class="form-label">Bidang / Kategori Pegawai</label>
                <select readonly class="form-select" id="kategori_pegawai_id" name="kategori_pegawai_id" aria-label="">
                    <option value="">Pilih Kategori pegawai</option>
                    @foreach ($kategori_pegawai as $kategori)
                        <option {{ $kategori->id == ($shift->kategori_pegawai_id ?? 0 ) ? 'selected' : '' }} value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="jam_mulai">Jam Mulai</label>
                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ $shift->mulai ?? '' }}">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="jam_selesai">Jam Selesai</label>
                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ $shift->selesai ?? '' }}">
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
                url: "{{ route('store_shift') }}",
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
