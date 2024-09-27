<?php
namespace App\Http\Controllers;

class HomeController {

	public function index() {
		if (auth()->check()) {
			return redirect()->to('/home');
		}

		return view('home');
	}

}