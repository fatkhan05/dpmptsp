<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importexcelsave" enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="formFile" class="form-label">Cari File Excel</label>
                            <input class="form-control" type="file" id="formFile" name="file_excel" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save-excel">Import</button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#btn-save-excel').click(function(e) {
                e.preventDefault();
                var data = new FormData($('#importexcelsave')[0]);
                $('#btn-save-excel').text('Sending...');
                $.ajax({
                    url: "{{ route('import_pegawai') }}",
                    type: 'POST',
                    data: data,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function(data) {
                    if (data.status == 'success') {
                        Swal.fire("Success", "Excel Berhasil Diimport", "success");
                        $('#datatables').DataTable().ajax.reload();
                        $('#basicModal').modal('hide');
                        $('#btn-save-excel').text('Import');
                        $('#importexcelsave')[0].reset();
                    };
                })
            });
        });
    </script>
@endpush
