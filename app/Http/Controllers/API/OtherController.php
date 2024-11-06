<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\DetailUser;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\LokasiPegawai;
use App\Models\AndroidVersion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    public function get_photo(Request $request)
    {
        try {
            return response()->file(public_path("images/$request->path"));
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
    }

    public function lokasi_pegawai(Request $request)
    {
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'waktu' => 'required',
            'lokasi' => 'required',
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

        $lokasi_pegawai = LokasiPegawai::create([
            'detail_user_id' => $request->detail_user_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
        ]);

        if ($lokasi_pegawai) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Lokasi Pegawai berhasil dilaporkan ', 'data' => $lokasi_pegawai];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Gagal ', 'data' => ''];
        }
    }

    public function send_notification(Request $request)
    {
        $url = 'https://onesignal.com/api/v1/notifications';
        // return $request->all();
        $response = $this->notification([$request->player_id], $request->heading, $request->content);
        if ($response) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Mnegirim notifikasi'];
        }
        return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mnegirim notifikasi'];
    }

    public function riwayat_notification(Request $request)
    {
        $notifications = Notification::where('detail_user_id', $request->detail_user_id)
            ->get()
        ;
        return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil mengambil riwayat notifikasi', 'data' => $notifications];
    }

    public function change_photo_profile(Request $request)
    {
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
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
        $img = Image::make($file->getRealPath());
        $img->resize(720, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path('images/profile').'/'.$filename, 60);
        
        $detail_user = DetailUser::find($request->detail_user_id);
        // DELETE OLD FILE
        if ($detail_user->photo != null) {
            File::delete(public_path("images/$detail_user->photo"));
        }

        // STORE NEW FILE NAME
        $detail_user->photo = "profile/$filename";
        $detail_user->save();

        if ($detail_user) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil mengubah photo profile', 'data' => $detail_user];
        }else{
            return ['status' => 'error', 'code' => 500, 'message' => 'Gagal mengubah photo profile', 'data' => ''];
        }
    }

    public function get_version()
    {
        $last_version = AndroidVersion::orderBy('tanggal', 'desc')->first();
        if ($last_version) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil mengambil versi', 'data' => $last_version];
        }else{
            return ['status' => 'error', 'code' => 500, 'message' => 'Gagal mengambil versi', 'data' => ''];
        }
    }

    public function ubah_password(Request $request)
    {
        $detail_user = DetailUser::all();
        // return $detail_user;
        foreach ($detail_user as $value) {
            $user = User::find($value->user_id);
            // return $user;
            $user->password = Hash::make($value->nik);
            $user->save();
        }
    }
}
