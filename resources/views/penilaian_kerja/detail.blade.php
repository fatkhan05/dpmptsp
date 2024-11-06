<style>

</style>

<div class="card  px-4 pb-4">
    <h5 class="card-header">Detail Penilaian Kerja Pegawai</h5>
    <div class="row mb-4">
        <div class="col-md-3 align-self-center text-center">
            <div class="p-profile">
                <a href="#" class="pan" data-big="{{ asset('images') . '/' . $detail_user->photo }}">
                    <img src="{{ asset('images') . '/' . $detail_user->photo }}" alt="Photo Profile"
                        class="rounded-circle">
                </a>
            </div>
        </div>
        <div class="col-md">
            <div class="row">
                <div class="col">
                    <h3>{{ $detail_user->nama }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Bidang / Bagian:</p>
                    <ul>
                        @foreach ($detail_user->pegawai_has_kategori as $pegawai_has_kategori)
                            <li>{{ $pegawai_has_kategori->kategori_pegawai->nama }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <p>{{ $detail_user->alamat }}</p>
                    <p>{{ $detail_user->telepon }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-4 mb-3">
        <label class="col-sm-1 col-form-label" for="basic-default-name">Tanggal</label>
        <div class="col-sm-3">
            {{-- <input class="form-control" type="date" value="2021-06-18" id="html5-date-input" /> --}}
            <input type="text" class="form-control" id="detail_datepicker" value="{{ $tanggal }}">
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <input type="hidden" value="{{ $detail_user->id }}" id="id_pegawai">
        <table class="table table-hover table-bordered" id="detail_datatables">
            <thead>
                <tr>
                    <th>No.</th>
                    <th style="width: 500px">Pekerjaan</th>
                    <th>Lokasi</th>
                    <th>Sebelum</th>
                    <th>Sesudah</th>
                    <th>Point</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">

            </tbody>
        </table>
    </div>

    <div class="row my-4">
        <div class="col-12">
            <button type="button" id="btn-back" class="btn btn-secondary btn-block"> Kembali </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // DATEPICKER
        var tanggal = $('#detail_datepicker');
        tanggal.datepicker({
            format: "dd-mm-yyyy",
            // format: "mm-yyyy",
            language: "id",
            orientation: "bottom right",
            todayHighlight: true,
            // startView: "date",
            // minViewMode: "date",
            autoclose: true
        });
        tanggal.datepicker('setDate', new Date($('#detail_datepicker').val()));

        tanggal.change(function() {
            $('#detail_datatables').DataTable().ajax.reload();
            $(".pan").pan();
        })

        $('#btn-back').click(function() {
            $('#other-page').fadeOut(function() {
                $('#other-page').empty();
                $('#main-page').fadeIn();
            });
        })
        //datatables
        var table = $('#detail_datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dt_detail_penilaian_pekerjaan') }}",
                type: "POST",
                data: {
                    'id': $('#id_pegawai').val(),
                    'tanggal': function() {
                        // return '2022-12-01'
                        return $('#detail_datepicker').val()
                    }
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'pekerjaan.nama',
                    name: 'pekerjaan.nama',
                    render: function(data, type, row) {
                        return `<div style="white-space:normal;width:100%">${data}</div>`;
                    }
                },
                {
                    data: 'pekerjaan.alamat',
                    name: 'pekerjaan.alamat',
                },

                // {
                //     data: 'detail_user',
                //     name: 'detail_user.nama',
                //     render: function(data, type, row) {
                //         return `<a href="{{ route('pegawai') }}?redirect=detail_user&detail_user_id=${data.id}">${data.nama}</a>`
                //     }
                // },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row) {
                        return `<a href="#" onclick='detail_sebelum(${data})'>Lihat detail</a>`
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row) {
                        return `<a href="#" onclick='detail_sesudah(${data})'>Lihat detail</a>`
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'nilai',
                    name: 'nilai.nilai',
                    render: function(data, type, row) {

                        return data.nilai * 20;
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'nilai',
                    name: 'nilai.nilai',
                    render: function(data, type, row) {

                        return `
                            <div class="rating">

                                <input ${data.nilai == 5? 'checked': ''} onclick="rate(${data.id}, 5, ${data.nilai})" type="radio" ${data.kepala} name="rating${data.id}" value="5" id="star5${data.id}"><label for="star5${data.id}">☆</label>
                                <input ${data.nilai == 4? 'checked': ''} onclick="rate(${data.id}, 4, ${data.nilai})" type="radio" ${data.kepala} name="rating${data.id}" value="4" id="star4${data.id}"><label for="star4${data.id}">☆</label>
                                <input ${data.nilai == 3? 'checked': ''} onclick="rate(${data.id}, 3, ${data.nilai})" type="radio" ${data.kepala} name="rating${data.id}" value="3" id="star3${data.id}"><label for="star3${data.id}">☆</label>
                                <input ${data.nilai == 2? 'checked': ''} onclick="rate(${data.id}, 2, ${data.nilai})" type="radio" ${data.kepala} name="rating${data.id}" value="2" id="star2${data.id}"><label for="star2${data.id}">☆</label>
                                <input ${data.nilai == 1? 'checked': ''} onclick="rate(${data.id}, 1, ${data.nilai})" type="radio" ${data.kepala} name="rating${data.id}" value="1" id="star1${data.id}"><label for="star1${data.id}">☆</label>
                                </div>
                            `;
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ]

        });

    });



    async function rate(id, val, cur_val) {
        const {
            value: text
        } = await Swal.fire({
            input: 'textarea',
            inputLabel: 'Komentar',
            inputPlaceholder: 'Ketik komentar disini...',
            inputAttributes: {
                'aria-label': 'Ketik komentar disini'
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
        })
        if (text || text == '') { // jika ditekan tombil SImpan
            $.ajax({
                type: "POST",
                url: "{{ route('rate_pekerjaan') }}",
                data: {
                    'id': id,
                    'rate': val,
                    'komentar': text,
                }
            }).done(function(data) {
                if (data.status == 'success') {
                    Swal.fire({
                        text: data.message,
                        icon: 'success'
                    })
                } else {
                    Swal.fire("Maaf!", "Terjadi kesalahan!", "error")
                }
            })
            $('#detail_datatables').DataTable().ajax.reload();
            $('#datatables').DataTable().ajax.reload();
        } else { // jika tekan cancel
            // Swal.fire('text')
            $(`#star${val}${id}`).prop('checked', false)
            $(`#star${cur_val}${id}`).prop('checked', true)
        }
    }

    function change_status(id, status) {
        var text = status == 'belum' ? 'Ulangi' : 'Selesaikan';
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: `${text} pekerjaan.`,
            type: "warning",
            showCancelButton: true,
            showCloseButton: true,
            icon: 'warning',
            cancelButtonText: 'Batal',
            confirmButtonText: text,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('change_status_pekerjaan') }}",
                    data: {
                        'id': id,
                        'status': status,
                    }
                }).done(function(data) {
                    if (data.status == 'success') {
                        Swal.fire({
                            text: data.message,
                            icon: 'success'
                        })
                    } else {
                        Swal.fire("Maaf!", "Terjadi kesalahan!", "error")
                    }
                })
                $('#detail_datatables').DataTable().ajax.reload();
            }
        });
    }

    function detail_sebelum(id) {
        $('#photo_pekerjaan').attr('src', ' ');
        $('#pan').attr('data-big', ' ');

        $('#modalCenter').modal('show');
        $.ajax({
            type: "POST",
            url: "{{ route('detail_pekerjaan') }}",
            data: {
                id: id
            }
        }).done(function(data) {
            if (data.status == 'success') {
                var detail_pekerjaan = data.data;
                $('#pan').attr('data-big', detail_pekerjaan.foto_sebelum);
                $('#photo_pekerjaan').attr('src', detail_pekerjaan.foto_sebelum);
                $('#nama_pegawai').text(detail_pekerjaan.detail_user.nama);
                $('#latitude').text(detail_pekerjaan.latitude_sebelum);
                $('#longitude').text(detail_pekerjaan.longitude_sebelum);
                $('#tanggal').text(detail_pekerjaan.time_take_sebelum.split(' ')[0]);
                $('#time_take').text(detail_pekerjaan.time_take_sebelum.split(' ')[1]);
            } else {
                Swal.fire('Maaf!', 'Terjadi kesalahan', 'error')
            }
        })
    }

    function detail_sesudah(id) {
        $('#photo_pekerjaan').attr('src', ' ');
        $('#pan').attr('data-big', ' ');

        $('#modalCenter').modal('show');
        $.ajax({
            type: "POST",
            url: "{{ route('detail_pekerjaan') }}",
            data: {
                id: id
            }
        }).done(function(data) {
            if (data.status == 'success') {
                var detail_pekerjaan = data.data;
                $('#pan').attr('data-big', detail_pekerjaan.foto_sesudah);
                $('#photo_pekerjaan').attr('src', detail_pekerjaan.foto_sesudah);
                $('#nama_pegawai').text(detail_pekerjaan.detail_user.nama);
                $('#latitude').text(detail_pekerjaan.latitude_sesudah);
                $('#longitude').text(detail_pekerjaan.longitude_sesudah);
                $('#tanggal').text(detail_pekerjaan.time_take_sesudah.split(' ')[0]);
                $('#time_take').text(detail_pekerjaan.time_take_sesudah.split(' ')[1]);
            } else {
                Swal.fire('Maaf!', 'Terjadi kesalahan', 'error')
            }
        })
    }
</script>
