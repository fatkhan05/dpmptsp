<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	public function index()
	{
		return view('login.main');
	}

	public function authenticate(Request $request)
	{
		// VALIDATION
		$rules = [
			'username' => 'required',
			'password' => 'required',
		];
		$messages = [
			'required' => 'Kolom :attribute harus diisi',
		];
		$credential = $request->validate($rules, $messages);

		// DAFTARKAN LEVEL JIKA INGIN MENGAKSES HALAMAN ADMIN WEBSITE
		if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'level' => [1, 3, 4]])) {
			$request->session()->regenerate();
			return redirect()->intended(route('dashboard'));
		}

		return back()->with('loginError', 'Login Gagal!. Username atau password salah');
	}

	public function logout(Request $request)
	{
		// dd($request);
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect(route('login'));
	}

	public function pengaturan(Request $request)
	{
		$data['token'] = auth()->user()->token;
		$data['detail_user_id'] = auth()->user()->detail_user->id;
		// dd($data);
		return view('pengaturan.main', $data);
	}
}