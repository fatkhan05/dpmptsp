<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pekerjaan;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use App\Models\KategoriPegawai;
use App\Imports\PekerjaanImport;
use Yajra\Datatables\Datatables;
use App\Models\PegawaiHasPekerjaan;
use App\Models\KategoriHasPekerjaan;
use App\Models\PegawaiHasKategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PekerjaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pekerjaan::with('kategori_has_pekerjaan.kategori_pegawai');
            // return $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('kategori_has_pekerjaan', function ($query, $keyword) {
                    $query->whereRelation('kategori_has_pekerjaan.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                        <button onclick="delete_data(' . $data->id . ')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('pekerjaan.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['pekerjaan'] = Pekerjaan::with(['kategori_has_pekerjaan.kategori_pegawai'])
                ->find($request->id);

            $array_kategori = [];
            foreach ($data['pekerjaan']->kategori_has_pekerjaan as $kategori_has_pekerjaan) {
                array_push($array_kategori, $kategori_has_pekerjaan->kategori_pegawai->id ?? 0);
            }
            $data['kategori_has_pekerjaan'] = $array_kategori;
        }

        $data['kategori_pagawai'] = KategoriPegawai::all();

        $content = view('pekerjaan.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // return $request->all();
        // VALIDATION
        $rules = [
            'nama' => 'required',
            'kategori_pegawai_id' => 'required',
            'lokasi_kerja' => 'required',
            // 'tanggal_mulai_pekerjaan' => 'required',
            // 'jam_mulai_pekerjaan' => 'required',
            // 'tanggal_selesai_pekerjaan' => 'required',
            // 'jam_selesai_pekerjaan' => 'required',
            'file' => 'mimes:pdf,png,jpg,jpeg|max:2048', // max 2MB
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
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        if ($request->pekerjaan_id) { // UPDATE
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id);

            // HAPUS KATEGORI YG DIBUANG
            KategoriHasPekerjaan::where('pekerjaan_id', $request->pekerjaan_id)
                ->whereNotIn('kategori_pegawai_id', $request->kategori_pegawai_id)
                ->delete();
        } else { // INSERT
            $pekerjaan = new Pekerjaan;
        }

        // JIKA KERJA DI LUAR
        if ($request->lokasi_kerja == '1') {
            // UPLOAD FOTO
            if ($request->file('file')) {
                $file = $request->file('file');
                $filename = date('YmdHis') . $file->getClientOriginalName();
                $file->move(public_path('images/surat_perintah'), $filename);
                // DELETE OLD FILE
                if ($pekerjaan->surat_perintah != null) {
                    File::delete(public_path("images/$pekerjaan->surat_perintah"));
                }

                $pekerjaan->surat_perintah = "surat_perintah/$filename";
            }

            $pekerjaan->alamat = $request->alamat_lokasi_kerja;
            $pekerjaan->latitude = $request->latitude;
            $pekerjaan->longitude = $request->longitude;
        }

        $pekerjaan->nama = $request->nama;
        $pekerjaan->lokasi = $request->lokasi_kerja == 1 ? 'Luar' : 'Dalam';
        $pekerjaan->latitude = $request->latitude ?? null;
        // $pekerjaan->mulai = $request->tanggal_mulai_pekerjaan . ' ' . $request->jam_mulai_pekerjaan;
        // $pekerjaan->selesai = $request->tanggal_selesai_pekerjaan . ' ' . $request->jam_selesai_pekerjaan;
        $pekerjaan->save();

        if ($pekerjaan) {
            foreach ($request->kategori_pegawai_id as $kategori_pegawai_id) {
                $kategori_has_pekerjaan = KategoriHasPekerjaan::firstOrCreate([
                    'pekerjaan_id' => $pekerjaan->id,
                    'kategori_pegawai_id' => $kategori_pegawai_id
                ]);

                // FIXME Notifikasi ketika ada pekerjaan baru
                // NOTIFIKATION
                // $users = User::whereRelation('detail_user', 'kategori_pegawai_id', $kategori_pegawai_id)->get('player_id');
                // foreach ($users as $user) {
                //     $this->notification([$user->player_id], 'Pekerjaan baru', "Terdapat beberapa pekerjaan baru");
                // }
                // $kategori_has_pekerjaan = new KategoriHasPekerjaan;
                // $kategori_has_pekerjaan->kategori_pegawai_id = $kategori_pegawai_id;
                // $kategori_has_pekerjaan->pekerjaan_id = $pekerjaan->id;
                // $kategori_has_pekerjaan->save();
            }
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Pekerjaan", 'data' => ''];
        }
    }

    // PENILAIAN KERJA 
    public function penilaian_kerja(Request $request)
    {
        $auth = Auth::user();

        // return    
        if ($request->ajax()) {
            $tanggal = $request->tanggal;
            // APABILA USER PENILAI MAKA
            if ($auth->level == 3) {
                $getDetail = DetailUser::with(['pegawai_has_kategori', 'bidang.kategori_pegawai'])->where('user_id', $auth->id)->get()->first();

                //GET PEGAWAI SEMUA FORMASI YANG INGIN DI TAMPILKAN
                $kategori = [];
                if (count($getDetail->pegawai_has_kategori) > 0) {
                    // JIKA FOMASI DIINPUTKAN MAKA HANYA TAMPILKAN FORMASI YANG DI PILIH TERSEBUT
                    foreach ($getDetail->pegawai_has_kategori as $key => $value) {
                        array_push($kategori, $value->kategori_pegawai_id);
                    }
                } else {
                    //TAMPILKAN SEMUA FORMASI YANG TERDAFTAR PADA BIDANG
                    foreach ($getDetail->bidang->kategori_pegawai as $key => $value) {
                        array_push($kategori, $value->id);
                    }
                }
                $data = DetailUser::with(['pegawai_has_kategori', 'pegawai_has_pekerjaan', 'point_target'])
                    ->whereHas('pegawai_has_pekerjaan', function ($query) use ($tanggal) {
                        $query->whereDate('time_take_sesudah', $tanggal);
                    })
                    ->WhereHas('pegawai_has_kategori', function ($query) use ($kategori) {
                        $query->whereIn('kategori_pegawai_id', $kategori);
                    })
                    ->get()->load(['pegawai_has_pekerjaan' => function ($query) use ($tanggal) {
                        $query->whereDate('time_take_sesudah', $tanggal);
                    }]);
            } else {
                $data = DetailUser::with(['pegawai_has_pekerjaan', 'point_target'])
                    ->whereHas('pegawai_has_pekerjaan', function ($query) use ($tanggal) {
                        $query->whereDate('time_take_sesudah', $tanggal);
                    })->get()->load(['pegawai_has_pekerjaan' => function ($query) use ($tanggal) {
                        $query->whereDate('time_take_sesudah', $tanggal);
                    }]);
            }


            // return $data;
            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($data) use ($tanggal) {
                    return '
                        <button onclick="detail(' . $data->id . ', \'' . $tanggal . '\')" class="btn btn-xs btn-primary">Detail</button>
                    ';
                })
                ->make(true);
        }
        // if ($request->ajax()) {
        //     $tanggal = $request->tanggal;
        //     $data = PegawaiHasPekerjaan::with(['detail_user', 'pekerjaan']);
        //     if (!empty($request->tanggal)) {
        //         $data = $data->whereDate('time_take_sesudah', $tanggal);
        //     }
        //     return Datatables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('nilai', function ($data) {
        //             return ['id' => $data->id, 'nilai' => $data->nilai ?? 0];
        //         })
        //         ->addColumn('action', function ($data) {
        //             $is_disabled1 = $data->status == "belum" ? 'disabled' : '';
        //             $is_disabled2 = $data->status == "selesai" ? 'disabled' : '';
        //             return '
        //                 <button ' . $is_disabled1 . ' onclick="change_status(' . $data->id . ', \'belum\')" class="btn btn-xs btn-primary">Ulangi pekerjaan</button>
        //                 <button ' . $is_disabled2 . ' onclick="change_status(' . $data->id . ', \'selesai\')" class="btn btn-xs btn-success">selesai</button>
        //             ';
        //         })
        //         ->make(true);
        // }
        return view('penilaian_kerja.main');
    }
    public function detail(Request $request)
    {
        $data['detail_user'] = DetailUser::with(['pegawai_has_pekerjaan'])
            ->find($request->id);
        $data['tanggal'] = $request->date;
        $content = view('penilaian_kerja.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function dt_detail_penilaian_pekerjaan(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = date("Y-m-d", strtotime($request->tanggal));;
            $data = PegawaiHasPekerjaan::with(['detail_user.point_target', 'pekerjaan'])->whereDate('time_take_sesudah', $tanggal)->where('detail_user_id', $request->id);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('nilai', function ($data) {
                    //RETURN NILAI JIKA ADA DAN CEK APAKAH USER MERUPAKAN KEPALA
                    return ['id' => $data->id, 'nilai' => $data->nilai ?? 0, 'kepala' => Auth::user()->level == 4 ? 'disabled' : ''];
                })
                ->addColumn('action', function ($data) {
                    $is_disabled1 = $data->status == "belum" ? 'disabled' : '';
                    $is_disabled2 = $data->status == "selesai" ? 'disabled' : '';
                    //CEK APAKAH USER MERUPAKAN KEPALA
                    if (Auth::user()->level == 4) {
                        $is_disabled1 = 'disabled';
                        $is_disabled2 = 'disabled';
                    }
                    return '
                        <button ' . $is_disabled1 . ' onclick="change_status(' . $data->id . ', \'belum\')" class="btn btn-xs btn-primary">Ulangi pekerjaan</button>
                        <button ' . $is_disabled2 . ' onclick="change_status(' . $data->id . ', \'selesai\')" class="btn btn-xs btn-success">selesai</button>
                    ';
                })
                ->make(true);
        }
    }

    public function rate_pekerjaan(Request $request)
    {
        $pekerjaan = PegawaiHasPekerjaan::find($request->id);
        $pekerjaan->nilai = $request->rate;
        $pekerjaan->komentar = $request->komentar;
        $pekerjaan->save();
        if ($pekerjaan) {
            $this->notification([$pekerjaan->detail_user->user->player_id], $pekerjaan->pekerjaan->nama . ' - ' . $pekerjaan->nilai . ' Bintang', $pekerjaan->komentar ?? '-');
            return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil menilai pekerjaan pegawai!', 'data' => ''];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Gagal menilai pekerjaan pegawai!', 'data' => ''];
        }
    }

    public function change_status(Request $request)
    {
        $pekerjaan = PegawaiHasPekerjaan::find($request->id);
        $pekerjaan->status = $request->status;
        $pekerjaan->save();
        if ($pekerjaan) {
            $nama_pekerjaan = $pekerjaan->pekerjaan->nama;
            if ($request->status == 'selesai') {
                $heading = 'Selamat!';
                $content = "Status Pekerjaan '$nama_pekerjaan' Telah Selesai";
            } else {
                $heading = 'Peringatan!';
                $content = "Harap Ulangi Pekerjaan '$nama_pekerjaan'!";
            }
            // SEND STATUS
            $this->notification([$pekerjaan->detail_user->user->player_id], $heading, $content);

            return ['status' => 'success', 'code' => 200, 'message' => 'Pekerjaan ' . $request->status, 'data' => ''];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Pekerjaan gagal ' . $request->status, 'data' => ''];
        }
    }

    public function detail_pekerjaan(Request $request)
    {
        $pekerjaan = PegawaiHasPekerjaan::with(['detail_user', 'pekerjaan'])
            ->find($request->id);
        $pekerjaan->foto_sebelum = asset('images') . '/' . $pekerjaan->foto_sebelum;
        $pekerjaan->foto_sesudah = asset('images') . '/' . $pekerjaan->foto_sesudah;
        if ($pekerjaan) {
            return ['status' => 'success', 'code' => 200, 'message' => 'Detail pekerjaan berhasil diambil', 'data' => $pekerjaan];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => 'Detail Pekerjaan tidak ditemukan', 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = Pekerjaan::find($request->id);
        $deleted->kategori_has_pekerjaan()->delete(); // HAPUS HAS MANY RELATION kategori_has_pekerjaan
        $deleted->delete(); // HAPUS PEKERJAAN

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    // IMPORT
    public function import(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'file_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file_excel');
        Excel::import(new PekerjaanImport, $file);

        return ['status' => 'success', 'message' => 'Berhasil Import Excel', 'title' => 'Success'];
    }

    // PEKERJAAN PEGAWAI
    public function pekerjaan_pegawai(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = $request->tanggal;
            $data = PegawaiHasPekerjaan::with(['detail_user.pegawai_has_kategori.kategori_pegawai', 'pekerjaan'])
                // ->selectRaw('pegawai_has_pekerjaans.*, detail_user.*, pekerjaan.*, pegawai_has_pekerjaans.created_at as tgl_pekerjaan_diberikan')
                ->whereDate('pegawai_has_pekerjaans.created_at', $tanggal)
                // ->whereHas('pekerjaan', function ($query) use ($tanggal) {
                //     $query->whereDate('created_at', $tanggal);
                // })
            ;
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('detail_user.pegawai_has_kategori', function ($query, $keyword) {
                    $query->whereRelation('detail_user.pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->editColumn('pegawai_has_pekerjaans.created_at', function ($data) {
                    return $data->created_at->format('d-m-Y H:i');
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                        <button onclick="delete_data(' . $data->id . ')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('pekerjaan_pegawai.main');
    }

    public function form_pekerjaan_pegawai(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['pegawai_has_pekerjaan'] = PegawaiHasPekerjaan::with(['detail_user', 'pekerjaan'])
                ->find($request->id);
            $data['pekerjaans'] = Pekerjaan::whereRelation('kategori_has_pekerjaan.kategori_pegawai.pegawai_has_kategori', 'detail_user_id', $data['pegawai_has_pekerjaan']->detail_user_id)->get();
        }
        $data['detail_users'] = DetailUser::whereRelation('pegawai_has_kategori', 'kategori_pegawai_id', '!=', 1)->get();
        $content = view('pekerjaan_pegawai.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function get_pekerjaan_kategori(Request $request)
    {
        // $detail_user = DetailUser::with('pegawai_has_kategori.kategori_pegawai')->find($request->detail_user_id,['id']);
        // return $detail_user;
        $data = Pekerjaan::whereRelation('kategori_has_pekerjaan.kategori_pegawai.pegawai_has_kategori', 'detail_user_id', $request->detail_user_id)->get();
        return response()->json($data);
    }

    public function store_pekerjaan_pegawai(Request $request)
    {
        // VALIDATION
        $rules = [
            'detail_user_id' => 'required',
            'pekerjaan_id' => 'required',
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
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        $pekerjaan_pegawai = PegawaiHasPekerjaan::updateOrCreate(
            ['id' => $request->pegawai_has_pekerjaan_id ?? null],
            [
                'detail_user_id' => $request->detail_user_id,
                'pekerjaan_id' => $request->pekerjaan_id,
                'jenis_pekerjaan' => $request->jenis_pekerjaan
            ]
        );

        if ($pekerjaan_pegawai) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Pekerjaan Pegawai", 'data' => ''];
        } else {
            return ['error' => 'success', 'code' => 500, 'message' => "Gagal menyimpan Pekerjaan Pegawai", 'data' => ''];
        }
    }

    public function delete_pekerjaan_pegawai(Request $request)
    {
        $deleted = PegawaiHasPekerjaan::find($request->id);
        $deleted->delete(); // HAPUS 

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }
}