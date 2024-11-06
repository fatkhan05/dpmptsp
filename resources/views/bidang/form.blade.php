<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($bidang) ? 'Tambah Kategori Bidang Baru' : 'Edit Kategori pegawai' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <div class="mb-3 col-md-12">
                <input type="hidden" class="form-control mb-3" id="kode" name="id" readonly
                    value="{{ $bidang->id ?? '' }}" />
                <label class="form-label" for="kode">Kode Bidang</label>
                <input type="text" class="form-control mb-3" id="kode" name="kode" placeholder="Kode Bidang"
                    readonly value="{{ $bidang->kode ?? $kode }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Nama Bidang</label>
                <input type="text" class="form-control mb-3" id="nama" name="nama"
                    placeholder="Nama Kategori Bidang" value="{{ $bidang->nama ?? '' }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Deskripsi</label>
                <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control">{{ $bidang->bidang_deskripsi ?? '' }}</textarea>
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
        $('#koordinator_id').select2();

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
                url: "{{ route('store_kategori_bidang') }}",
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
