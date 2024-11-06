@extends('layouts.main')

@section('title')
    Pengaturan  
@endsection

@push('css')
    
@endpush

@section('content')
    <!-- Basic Bootstrap Table -->
    <div id="main-page">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">Ganti Password </h5>
            </div>
            <div class="card-body">
                <form class="form-save row">
                    <div class="mb-3 col-md-12">
                        <input type="hidden" name="token" id="" value="{{ $token }}">
                        <input type="hidden" name="detail_user_id" id="" value="{{ $detail_user_id }}">
                        <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password32">Password Sebelumnya</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="basic-default-password32" name="password_lama"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password12">Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="basic-default-password12" name="password_baru"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password34">Ulangi Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="basic-default-password34" name="ulangi_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" id="btn-submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="other-page">
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#btn-submit').click(function(e) {
            e.preventDefault();
            $('#btn-submit').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            ).attr('disabled', true);
            var data = new FormData($('.form-save')[0]);
            $.ajax({
                url: "{{ route('change_password') }}",
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
                    window.location.replace("{{ route('dashboard') }}");
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
    });
</script>
@endpush
