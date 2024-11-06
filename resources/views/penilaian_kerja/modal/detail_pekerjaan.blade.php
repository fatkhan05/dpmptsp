@push('css')
    
@endpush

<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 p-2">
                        <a class="pan" data-big="" href="#" id="pan">
                            <img src="" alt=""
                                class="img-fluid" id="photo_pekerjaan">
                        </a>
                    </div>
                    <div class="col-md-auto">
                        <table class="table table-borderless">
                            <tr>
                                <td>Pegawai</td>
                                <td>: <span id="nama_pegawai"></span></td>
                            </tr>
                            <tr>
                                <td>Latitude</td>
                                <td>: <span id="latitude"></span></td>
                            </tr>
                            <tr>
                                <td>Longitude</td>
                                <td>: <span id="longitude"></span></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: <span id="tanggal"></span></td>
                            </tr>
                            <tr>
                                <td>Time take</td>
                                <td>: <span id="time_take"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $(".pan").pan().each(function() {
                $(this).attr('title', 'CLICK TO ZOOM');
            });
        });
    </script>
@endpush
