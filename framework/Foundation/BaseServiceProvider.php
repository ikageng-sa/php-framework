<?php

namespace Qanna\Foundation;

use Qanna\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->app->provider(\App\Providers\AppServiceProvider::class);

		$this->mergeConfigFrom(config_path('app.php'), 'app');
		$this->setDefaultTimezone();
	}

	protected function setDefaultTimezone() {
		date_default_timezone_set($this->app['config']->get('app.timezone'));
	}

}