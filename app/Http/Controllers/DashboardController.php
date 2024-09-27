<?php
namespace App\Http\Controllers;

use Qanna\Http\Enum\HttpStatus;

class DashboardController {

	public function index() {
		if (!auth()->check()) {
			return redirect()->to('/login', HttpStatus::FORBIDDEN->value);
		}

		return view('dashboard')->extends('layouts.dashboard');
	}

}