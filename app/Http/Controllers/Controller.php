<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
		date_default_timezone_set('Asia/Jakarta');
	}

    public function generateKode($model, $prefix=null, $digits=3){
		// GENERATE KODE
		$num = 0;
		$data = $model::select('kode');
        if ($prefix) {
            $data = $data->where('kode', 'like', $prefix.'%')
                ->orderBy('kode', 'desc')
                ->first();
            $dash_prefix = $prefix.'-';
            if ($data) {
                $num = explode('-', $data->kode)[1];
            }
        }else{
            $data = $data->orderBy('kode', 'desc')
                ->first();
            $dash_prefix = '';
            if ($data) {
                $num = $data->kode;
            }
        }

		$next_kode = $dash_prefix . sprintf("%0${digits}d", (string)((int)$num + 1));
		return $next_kode;
	}

    public function notification($player_ids, $heading, $content)
    {
        $url = 'https://onesignal.com/api/v1/notifications';
        $response = Http::post($url, [
            "app_id" => "a5e9d24c-9c4c-4f89-b6d8-0c4d766745e1",
            "include_player_ids" => $player_ids,
            "android_accent_color" => "FF9976D2",
            "headings" => ["en" => $heading],
            "contents" => ["en" => $content]
        ]);
        foreach ($player_ids as $player_id) {
            $detail_user = DetailUser::whereRelation('user', 'player_id', $player_id)
                ->orderBy('updated_at', 'desc')
                ->first()
            ;
            Notification::create([
                'detail_user_id' => $detail_user->id,
                'player_id' => $player_id ?? '-',
                'heading' => $heading,
                'content' => $content,
            ]);
        }
        return $response->ok();
    }
  
}
