<style>

</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ empty($kategori_pegawai) ? 'Tambah Kategori Pegawai baru' : 'Edit Kategori pegawai' }}</h5>
    </div>
    <div class="card-body">
        <form class="form-save row">
            <input type="hidden" name="kategori_pegawai_id" value="{{ $kategori_pegawai->id ?? '' }}">
            <div class="mb-3 col-md-12">
                <label class="form-label" for="kode">Kode Kategori</label>
                <input type="text" class="form-control mb-3" id="kode" name="kode"
                    placeholder="Kode Kategori" readonly value="{{ $kategori_pegawai->kode ?? $kode }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label" for="nama">Nama Kategori</label>
                <input type="text" class="form-control mb-3" id="nama" name="nama"
                    placeholder="Nama Kategori" value="{{ $kategori_pegawai->nama ?? '' }}" />
            </div>
            <div class="mb-3 col-md-12">
                <label for="bidang_id" class="form-label">Kategori Bidang</label>
                <select class="form-select" id="bidang_id" name="bidang_id" aria-label="">
                    <option value="">Pilih Kategori Bidang</option>
                    @foreach ($bidang as $bdg)
                        <option {{ $bdg->id == ($kategori_pegawai->bidang_id ?? 0) ? 'selected' : '' }}
                            value="{{ $bdg->id }}">{{ $bdg->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-12">
                <label for="koordinator_id" class="form-label">Koordinator / Penanggung Jawab</label>
                <select class="form-select" id="koordinator_id" name="koordinator_id" aria-label="">
                    <option value="0">Pilih Pegawai</option>
                    @foreach ($pegawais as $pegawai)
                        <option {{ $pegawai->id == ($kategori_pegawai->detail_user_id ?? 0) ? 'selected' : '' }}
                            value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
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
        $('#koordinator_id').select2();
        $('#bidang_id').select2();

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
                url: "{{ route('store_kategori_pegawai') }}",
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
