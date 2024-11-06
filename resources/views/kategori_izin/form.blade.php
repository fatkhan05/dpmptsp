<style>

</style>


<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($kategori_izin) ? 'Tambah kategori baru' : 'Edit kategori' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save">
            <input type="hidden" name="kategori_izin_id" value="{{ $kategori_izin->id ?? '' }}">
            <div class="mb-3">
                <label class="form-label" for="nama">Nama Kategori</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kategori" value="{{ $kategori_izin->nama ?? '' }}"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="form-control" placeholder="">{{ $kategori_izin->keterangan ?? '' }}</textarea>
            </div>
            <button type="button" id="btn-back" class="btn btn-secondary"> Kembali </button>
            <button type="submit" id="btn-submit" class="btn btn-primary mx-3">Simpan</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
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
                url: "{{ route('store_kategori_izin') }}",
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
