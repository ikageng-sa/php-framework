<?php
namespace App\Http\Controllers\Auth;

use Qanna\Http\Enum\HttpStatus;
use Qanna\Support\Facades\Auth;

class LoginController {

	public function showForm() {

		if (auth()->check()) {
			return redirect()->to('/home');
		}

		return view('auth.login');
	}

	public function login(\App\Http\Requests\Auth\LoginRequest $request) {

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			return redirect()->to('/home', HttpStatus::OK->value);
		}

		return redirect(status: HttpStatus::FORBIDDEN->value)->back();
	}
}