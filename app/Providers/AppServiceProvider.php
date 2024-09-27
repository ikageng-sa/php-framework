<?php

namespace App\Providers;

use Qanna\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->mergeConfigFrom(config_path('app.php'), 'app');
	}

	public function boot(): void {
		//
	}
}