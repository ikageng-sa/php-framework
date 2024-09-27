<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Qanna\Http\Enum\HttpStatus;
use Qanna\Support\Facades\Auth;

class RegisterController {

	public function showForm() {
		if (auth()->check()) {
			return redirect()->to('/home');
		}

		return view('auth.register');
	}

	public function register(\App\Http\Requests\Auth\RegisterRequest $request) {

		User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Auth::hashPassword($request->password),
		]);

		if (Auth::login(['email' => $request->email, 'password' => $request->password])) {
			return redirect()->to('/home', HttpStatus::OK->value);
		}

		return redirect()->back();
	}

}