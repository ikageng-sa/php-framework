<?php

namespace App\Http\Requests\Auth;

use Qanna\Http\FormRequest as HttpFormRequest;

class LoginRequest extends HttpFormRequest {

	public function authorize(): bool {
		return true;
	}

	public function rules(): array {
		return [
			'email' => 'exists:users|required|email',
			'password' => 'required|string|min:6',
		];
	}
}