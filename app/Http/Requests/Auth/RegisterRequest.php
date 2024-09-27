<?php

namespace App\Http\Requests\Auth;

use Qanna\Http\FormRequest;

class RegisterRequest extends FormRequest {

	public function authorize(): bool {
		return true;
	}

	public function rules(): array {
		return [
			'name' => 'string|min:3',
			'email' => 'email|unique:users',
			'password' => 'string|min:6|confirmed:confirm-password',
		];
	}
}