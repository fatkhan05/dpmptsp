<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Models\JamKerja;
use App\Models\Presensi;
use App\Models\Pekerjaan;
use App\Models\Perizinan;
use App\Models\DetailUser;
use App\Models\KategoriIzin;
use App\Models\LokasiKantor;
use Illuminate\Http\Request;
use App\Models\DetailPerizinan;
use App\Models\PegawaiHasPekerjaan;
use App\Http\Controllers\Controller;
use App\Models\KategoriHasPekerjaan;
use App\Models\PointTarget;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function lokasi_kantor(Request $request)
    {
        if ($request->lokasi_kantor_id) {
            $lokasi_kantor = LokasiKantor::find($request->lokasi_kantor_id);
        } else {
            $lokasi_kantor = LokasiKantor::all();
        }
        return ['status' => 'success', 'code' => 200, 'message' => "Lokasi kantor", 'data' => $lokasi_kantor];
    }

    public function pekerjaan_pegawai(Request $request)
    {
        // VALIDATION
        // $rules = [
        //     'user_id' => 'required',
        // ];
        // $messages = [
        //     'required' => 'Kolom :attribute harus diisi',
        // ];
        // // $this->validate($request, $rules, $messages);
        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        $pegawai_has_pekerjaan = PegawaiHasPekerjaan::with(['detail_user', 'pekerjaan'])
            ->where('detail_user_id', $request->detail_user_id)
            // ->whereRelation('pekerjaan', 'mulai', '<', date('Y-m-d H:i:s'))
            // ->whereRelation('pekerjaan', 'selesai', '>', date('Y-m-d H:i:s'))
        ;
        if ($request->status) { // where status
            $pegawai_has_pekerjaan = $pegawai_has_pekerjaan->where('status', $request->status);
        }
        if ($request->bulan) { // where bulan-tahun
            $bulan = explode('-', $request->bulan)[0];
            $tahun = explode('-', $request->bulan)[1];
            $pegawai_has_pekerjaan = $pegawai_has_pekerjaan->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        }
        $pegawai_has_pekerjaan = $pegawai_has_pekerjaan->get();

        if ($pegawai_has_pekerjaan) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Data Pekerjaan Pegawai Berhasil diambil', 'data' => $pegawai_has_pekerjaan];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Pekerjaan Pegawai Tidak Ditemukan', 'data' => ''];
        }
    }

    public function take_pekerjaan(Request $request)
    {
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'pekerjaan_id' => 'required',
            'pekerjaan_id.*' => 'required',
            'jenis_pekerjaan' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }


        foreach ($request->pekerjaan_id as $key => $pekerjaan_id) {
            // $kategori_user = DetailUser::select('kategori_pegawai_id')->find($request->detail_user_id);
            // return 
            // // KategoriHasPekerjaan::whereRela ('kategori_pegawai_id', );
            // TODO validasi jika pekerjaan yg diambil tidak sesuai bidang
            $taken = PegawaiHasPekerjaan::create([
                'detail_user_id' => $request->detail_user_id,
                'pekerjaan_id' => $pekerjaan_id,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
            ]);

            if (!$taken) {
                return ['status' => 'error', 'code' => 500, 'message' => "Pekerjaan $pekerjaan_id Gagal Diambil", 'data' => ''];
            }
        }
        return ['status' => 'success', 'code' => 200, 'message' => "Pekerjaan berhasil diambil", 'data' => ''];
    }

    public function create_pekerjaan(Request $request)
    {
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'nama_kerja' => 'required',
            'lokasi_kerja' => 'required',
            'lokasi_kerja' => 'required',
            'datetime_mulai_pekerjaan' => 'required',
            'datetime_selesai_pekerjaan' => 'required',
            'kategori_pegawai_id' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        if ($request->hasFile('file')) {
            if ($request->file('file')->extension() == 'pdf') { // jika ekstensi file pdf 
                $rules['file'] = 'mimes:pdf|max:2048';
            } else {
                $rules['file'] = 'mimes:pdf,png,jpg,jpeg';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        $filename = "";
        // return $request->file('file')->extension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = date('YmdHis') . $file->getClientOriginalName();

            if ($file->extension() == 'pdf') {
                $file->move(public_path('images/surat_perintah'), $filename);
            } else {
                $img = Image::make($file->getRealPath());
                $img->resize(720, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(public_path('images/surat_perintah') . '/' . $filename, 80);
            }
        }


        // $detail_user = DetailUser::find($request->detail_user_id);

        // INSERT PEKERJAAN
        $pekerjaan = Pekerjaan::create([
            'nama' => $request->nama_kerja,
            'lokasi' => $request->lokasi_kerja,
            'alamat' => $request->alamat,
            'mulai' => $request->datetime_mulai_pekerjaan,
            'selesai' => $request->datetime_selesai_pekerjaan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'surat_perintah' => 'surat_perintah/' . $filename,
        ]);

        // INSERT KATEGORI HAS PEKERJAAN
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => $request->kategori_pegawai_id,
            'pekerjaan_id' => $pekerjaan->id,
        ]);

        // INSERT PEGAWAI HAS PEKERJAAN
        PegawaiHasPekerjaan::create([
            'detail_user_id' => $request->detail_user_id,
            'pekerjaan_id' => $pekerjaan->id,
        ]);

        return ['status' => 'success', 'code' => 200, 'message' => "Pekerjaan berhasil ditambah", 'data' => ''];
    }

    public function mulai_pekerjaan(Request $request)
    {
        // return $request->all();
        // VALIDATION
        $rules = [
            'pekerjaan_pegawai_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg',
            'time_take' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        // UPLOAD FOTO
        $file = $request->file('photo');
        $filename = date('YmdHis') . $file->getClientOriginalName();
        // $file->move(public_path('images/pekerjaan'), $filename);
        $img = Image::make($file->getRealPath());
        $img->resize(720, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path('images/pekerjaan') . '/' . $filename, 60);

        // UPDATE PEGAWAI HAS PEKERJAAN
        $pegawai_has_pekerjaan = PegawaiHasPekerjaan::find($request->pekerjaan_pegawai_id);
        $pegawai_has_pekerjaan->latitude_sebelum = $request->latitude;
        $pegawai_has_pekerjaan->longitude_sebelum = $request->longitude;
        $pegawai_has_pekerjaan->foto_sebelum = "pekerjaan/$filename";
        $pegawai_has_pekerjaan->time_take_sebelum = $request->time_take;
        $pegawai_has_pekerjaan->save();

        return ['status' => 'success', 'code' => 200, 'message' => 'Progres pekerjaan berhasil disimpan', 'data' => $pegawai_has_pekerjaan];
    }

    public function get_pekerjaan(Request $request)
    {
        $pegawai_has_pekerjaan = PegawaiHasPekerjaan::find($request->pekerjaan_pegawai_id);
        if ($pegawai_has_pekerjaan) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil diambil', 'data' => $pegawai_has_pekerjaan];
        }
        return ['status' => 'error', 'code' => 404, 'message' => 'Data tidak ditemukan', 'data' => ''];
    }

    public function selesai_pekerjaan(Request $request)
    {
        // return $request->image->extension();
        // VALIDATION
        $rules = [
            'pekerjaan_pegawai_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg',
            'time_take' => 'required',
            'status' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        // UPLOAD FOTO
        $file = $request->file('photo');
        $filename = date('YmdHis') . $file->getClientOriginalName();
        // $file->move(public_path('images/pekerjaan'), $filename);
        $img = Image::make($file->getRealPath());
        $img->resize(720, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path('images/pekerjaan') . '/' . $filename, 60);

        // UPDATE PEGAWAI HAS PEKERJAAN
        $pegawai_has_pekerjaan = PegawaiHasPekerjaan::find($request->pekerjaan_pegawai_id);
        $pegawai_has_pekerjaan->latitude_sesudah = $request->latitude;
        $pegawai_has_pekerjaan->longitude_sesudah = $request->longitude;
        $pegawai_has_pekerjaan->foto_sesudah = "pekerjaan/$filename";
        $pegawai_has_pekerjaan->time_take_sesudah = $request->time_take;
        $pegawai_has_pekerjaan->status = $request->status;
        $pegawai_has_pekerjaan->save();

        return ['status' => 'success', 'code' => 200, 'message' => 'Progres pekerjaan berhasil disimpan', 'data' => $pegawai_has_pekerjaan];
    }

    public function pekerjaan_kategori_pegawai(Request $request)
    {
        $kategori_has_pekerjaan = KategoriHasPekerjaan::with(['kategori_pegawai', 'pekerjaan'])
            // ->whereRelation('pekerjaan', 'mulai', '<', date('Y-m-d H:i:s'))
            // ->whereRelation('pekerjaan', 'selesai', '>', date('Y-m-d H:i:s'))
            ->whereRelation('pekerjaan', 'nama', 'like', "%$request->key_word%")
            // ->whereIn('kategori_pegawai_id', $request->kategori_pegawai_id)
            ->get();

        if ($kategori_has_pekerjaan) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Data Pekerjaan Per Kategori Pegawai Berhasil diambil', 'data' => $kategori_has_pekerjaan];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Pekerjaan Per Kategori Pegawai Tidak Ditemukan', 'data' => ''];
        }
    }

    public function presensi_masuk(Request $request)
    {
        // FIXME Jika hari ini sudah presensi masuk, tidak bisa presensi lagi hari ini. 
        // return $request->all();
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg',
            'datetime' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        // UPLOAD FOTO
        $file = $request->file('photo');
        $filename = date('YmdHis') . $file->getClientOriginalName();
        // $file->move(public_path('images/presensi'), $filename);
        $img = Image::make($file->getRealPath());
        $img->resize(720, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path('images/presensi') . '/' . $filename, 60);

        $detail_user = DetailUser::with('shift')->find($request->detail_user_id);

        $jam_masuk_kerja = $detail_user->shift->mulai;
        $jam_masuk_kerja = new DateTime($jam_masuk_kerja);
        $jam_masuk_presensi = explode(' ', $request->datetime)[1]; // get time only
        $jam_masuk_presensi = new DateTime($jam_masuk_presensi);

        // COMPARE JAM MASUK KERJA DAN JAM PRESENSI MASUK
        $telat = '00:00';
        if ($jam_masuk_kerja < $jam_masuk_presensi) {
            $interval = $jam_masuk_kerja->diff($jam_masuk_presensi);
            $telat = $interval->format('%H:%I');
        }
        // SAVE DATA
        $presensi = Presensi::create([
            'detail_user_id' => $request->detail_user_id,
            'masuk' => $request->datetime,
            'telat' => $telat,
            'lokasi_presensi_masuk' => 'IN AREA',
            'latitude_masuk' => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'foto_masuk' => "presensi/$filename",
        ]);

        return ['status' => 'success', 'code' => 200, 'message' => 'Presensi masuk berhasil', 'data' => ''];
    }

    public function presensi_pulang(Request $request)
    {
        // return $request->all();
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg',
            'datetime' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        // UPLOAD FOTO
        $file = $request->file('photo');
        $filename = date('YmdHis') . $file->getClientOriginalName();
        // $file->move(public_path('images/presensi'), $filename);
        $img = Image::make($file->getRealPath());
        $img->resize(720, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path('images/presensi') . '/' . $filename, 60);

        $detail_user = DetailUser::with('shift')->find($request->detail_user_id);

        $jam_pulang_kerja = $detail_user->shift->selesai;
        $jam_pulang_kerja = new DateTime($jam_pulang_kerja);
        $jam_pulang_presensi = explode(' ', $request->datetime)[1]; // get time only
        $jam_pulang_presensi = new DateTime($jam_pulang_presensi);

        // COMPARE JAM pulang KERJA DAN JAM PRESENSI pulang
        $pulang_cepat = '00:00';
        if ($jam_pulang_kerja > $jam_pulang_presensi) {
            $interval = $jam_pulang_kerja->diff($jam_pulang_presensi);
            $pulang_cepat = $interval->format('%H:%I');
        }
        // SAVE DATA
        $date = explode(' ', $request->datetime)[0]; // get date only
        $presensi = Presensi::where('detail_user_id', $request->detail_user_id)
            ->orderBy('created_at', 'desc')
            ->first()
            ->update([
                'pulang' => $request->datetime,
                'pulang_cepat' => $pulang_cepat,
                'lokasi_presensi_pulang' => 'IN AREA',
                'latitude_pulang' => $request->latitude,
                'longitude_pulang' => $request->longitude,
                'foto_pulang' => "presensi/$filename",
            ]);
        if ($presensi) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Presensi pulang berhasil', 'data' => $presensi];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Presensi pulang gagal, (presensi masuk tidak ditemukan)', 'data' => ''];
        }
    }

    public function riwayat_presensi(Request $request)
    {
        $date = explode('-', $request->date);
        $tahun = $date[0];
        $bulan = $date[1];
        $tanggal = $date[2];
        $presensis = Presensi::where('detail_user_id', $request->detail_user_id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc');

        if ($tanggal != '0') {
            $presensis = $presensis->whereDay('created_at', $tanggal)
                ->first();
        } else {
            $presensis = $presensis->get();
        }

        if ($presensis) {
            return ['status' => 'success', 'code' => 200, 'message' => "Riwayat Presensi bulan $request->bulan-$request->tahun berhasil diambil", 'data' => $presensis];
        } else {
            return ['status' => 'success', 'code' => 500, 'message' => "Riwayat Presensi bulan $request->bulan-$request->tahun gagal berhasil diambil", 'data' => $presensis];
        }
    }

    public function kategori_izin(Request $request)
    {
        $kategori_izin = KategoriIzin::all();
        return ['status' => 'success', 'code' => 200, 'message' => "Kategori Izin", 'data' => $kategori_izin];
    }

    public function pengajuan_izin(Request $request)
    {
        // return $request->all();
        $rules = [
            'detail_user_id' => 'required',
            'kategori_izin_id' => 'required',
            'mulai' => 'required',
            'lampiran.*' => 'required|image|mimes:png,jpg,jpeg',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        $perizinan = Perizinan::create([
            'kategori_izin_id' => $request->kategori_izin_id,
            'detail_user_id' => $request->detail_user_id,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'keterangan' => $request->keterangan,
        ]);

        if ($perizinan) {
            // UPLOAD FOTO
            foreach ($request->file('lampiran') ?? [] as $key => $value) {
                $file = $value;
                $filename = date('YmdHis') . $key . $file->getClientOriginalName();
                // $file->move(public_path('images/perizinan'), $filename);
                $img = Image::make($file->getRealPath());
                $img->resize(720, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(public_path('images/perizinan') . '/' . $filename, 60);

                $detail_perizinan = DetailPerizinan::create([
                    'perizinan_id' => $perizinan->id,
                    'lampiran' => 'perizinan/' . $filename,
                ]);
            }
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil menyimpan perizinan', 'perizinan' => $perizinan, 'lampiran' => $perizinan->detail_perizinan];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Gagal menyimpan perizinan', 'data' => ''];
        }
    }

    public function riwayat_pengajuan_izin(Request $request)
    {
        $perizinan = Perizinan::with('detail_perizinan')
            ->where('detail_user_id', $request->detail_user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return ['status' => 'success', 'code' => 200, 'message' => "Riwayat pengejuan izin berhasil diambil", 'data' => $perizinan];
    }

    public function point_target(Request $request)
    {
        $point_target = PointTarget::where('detail_user_id', $request->detail_user_id)->get();
        if ($point_target) {
            return ['status' => 'success', 'code' => 200, 'message' => "Point Target pegawai berhasil diambil", 'data' => $point_target];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => "Point Target pegawai gagal diambil", 'data' => ''];
        }
    }
}