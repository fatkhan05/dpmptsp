<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Libraries\FLib;
use App\Http\Libraries\Formatters;
use App\Models\DetailUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //login pengguna
    public function login(Request $request)
    {
        //Validasi Data
        if (empty($request->nomor_pegawai)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Kolom Nomor pegawai Harus Diisi', 'data' => ''];
        }

        if (empty($request->player_id)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Kolom player_id Harus Diisi', 'data' => ''];
        }
        
        if (empty($request->password)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Kolom Password Harus Diisi', 'data' => ''];
        }

        // login bisa menggunakan Email / No Telp
        $user = User::with([
                'detail_user.pegawai_has_kategori.kategori_pegawai', 
                'detail_user.provinsi', 
                'detail_user.kabupaten', 
                'detail_user.kecamatan', 
                'detail_user.desa',
                'detail_user.shift',
                'detail_user.point_target',
            ])
            ->where('nomor', $request->nomor_pegawai)
            ->orWhere(function ($query) use($request) {
                $query->whereRelation('detail_user', 'nik', $request->nomor_pegawai);
            })
            ->first();
        // return $user;
        //cek apakah email terdaftar
        if ($user) {
            //cek apakah password sesuai
            if (Hash::check($request->password, $user->password)) {
                // cek apakah akun pengguna aktif
                if ($user->status == '1') {
                    //update token ketika token masih kosong
                    if (empty($user->token)) {
                        $user->token = Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15);
                    }
                    $user->player_id = $request->player_id; // Update Player id setaip login
                    $user->save();
                    return ['status' => 'success', 'code' => 200, 'message' => 'Selamat Datang ' . $user->nama_lengkap . '', 'data' => $user];
                } else {
                    return ['status' => 'error', 'code' => 500, 'message' => 'Akun Yang Anda Masukkan Tidak Aktif', 'data' => ''];
                }
            } else {
                return ['status' => 'error', 'code' => 500, 'message' => 'Akun Yang Anda Masukkan Tidak Terdaftar', 'data' => ''];
            }
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Akun Yang Anda Masukkan Tidak Terdaftar', 'data' => ''];
        }
    }

    //get data profil
    public function profile(Request $request)
    {
        $user = User::with([
                'detail_user.pegawai_has_kategori.kategori_pegawai', 
                'detail_user.provinsi', 
                'detail_user.kabupaten', 
                'detail_user.kecamatan', 
                'detail_user.desa',
                'detail_user.shift',
                'detail_user.point_target',
            ])
            ->where('token', $request->token)
            ->first();

        if ($user) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Profil Berhasil diambil', 'data' => $user];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Profil Tidak Ditemukan', 'data' => ''];
        }
    }

    // public function change_passowrd(Request $request)
    // {
    //     // VALIDATION
    //     $rules = [
    //         'detail_user_id' => 'required',
    //         'password_lama' => 'required',
    //         'password_baru' => 'required',
    //         'password_conf' => 'required',
    //     ];
    //     $messages = [
    //         'required' => 'Kolom :attribute harus diisi',
    //     ];

    //     $validator = Validator::make($request->all(), $rules, $messages);
    //     if ($validator->fails()) {
    //         $messages = [];
    //         foreach (json_decode($validator->errors()) as $key => $val) {
    //             array_push($messages, $val[0]);
    //         }
    //         return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
    //     }
        
    //     $detail_user = DetailUser::with('user')
    //         ->where('id', $request->detail_user_id)
    //         ->whereRelation('user', 'password', Hash::make($request->password_lama))
    // }

    //ubah profil
    // public function ubahProfil(Request $request)
    // {
    //     // return $request->all();
    //     $cekEmail = Users::where('email', $request->email)->first();

    //     $users = FLib::userToken($request->token);
    //     $users = Users::find($users->id);

    //     // Validasi Data
    //     if (empty($request->nama_lengkap)) {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Kolom Nama Lengkap Harus Diisi', 'data' => ''];
    //     }
    //     if (empty($request->email)) {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Kolom Email Harus Diisi', 'data' => ''];
    //     }
    //     if (!Formatters::validEmail($request->email)) {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Format Email Harus Benar', 'data' => ''];
    //     }
    //     if (empty($request->no_telp)) {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Kolom No Telp Harus Diisi', 'data' => ''];
    //     }
    //     if ($cekEmail && $cekEmail->id != (($users) ? $users->id : 0)) {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Email sudah terdaftar!', 'data' => ''];
    //     }

    //     $users->nama_lengkap = $request->nama_lengkap;
    //     $users->email = $request->email;
    //     $users->no_telp = $request->no_telp;
    //     $users->save();

    //     if ($users) {
    //         return ['status' => 'success', 'code' => 200, 'message' => 'Profil Berhasil Diubah', 'data' => $users];
    //     } else {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Profil Gagal Diubah', 'data' => ''];
    //     }
    // }

    //ubah password pengguna
    public function change_password(Request $request)
    {
        // return $request->all();
        $detail_user = DetailUser::with('user')->find($request->detail_user_id);

        //cek password lama
        if (!Hash::check($request->password_lama, $detail_user->user->password)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Masukkan Password Lama Anda Dengan Benar', 'data' => ''];
        }
        //cek password baru tidak sama dengan ulangi password
        if ($request->password_baru != $request->ulangi_password) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Kata Sandi Tidak Sesuai', 'data' => ''];
        }
        //cek password baru apakah sama dengan password lama
        if (Hash::check($request->password_baru, $detail_user->user->password)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Password Baru Harus Berbeda Dengan Password Lama', 'data' => ''];
        }

        $detail_user->user->password = Hash::make($request->password_baru);
        $detail_user->user->save();

        if ($detail_user) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Password Berhasil Diubah', 'data' => $detail_user];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Password Gagal Diubah', 'data' => ''];
        }
    }

    //ubah foto profil
    // public function ubahFotoProfil(Request $request)
    // {
    //     // return $request->all();

    //     $users = FLib::userToken($request->token);
    //     $users = Users::find($users->id);

    //     if (!empty($request->foto) && $request->foto != '0') {
    //         if (!empty($users) && $users->foto != '') {
    //             $path = "admin/img/pengguna/" . $users->foto;
    //             if (file_exists($path)) {
    //                 unlink($path);
    //             }
    //         }
    //         $nama_foto = str_replace([' ', '/'], '-', $users->nama_lengkap);
    //         $ext_foto       = $request->foto->getClientOriginalExtension();
    //         $filename       = $nama_foto . "-" . date('Ymdhis') . "." . $ext_foto;
    //         $temp_foto      = 'admin/img/pengguna';
    //         $proses         = $request->foto->move($temp_foto, $filename);
    //         $users->foto  = $filename;
    //     }

    //     $users->save();

    //     if ($users) {
    //         return ['status' => 'success', 'code' => 200, 'message' => 'Foto Profil Berhasil Diubah', 'data' => $users];
    //     } else {
    //         return ['status' => 'error', 'code' => 500, 'message' => 'Foto Profil Gagal Diubah', 'data' => ''];
    //     }
    // }
}
