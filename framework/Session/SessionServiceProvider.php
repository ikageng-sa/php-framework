<?php

namespace Qanna\Session;

use Qanna\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider {
	public function register(): void {
		$this->mergeConfigFrom(
			config_path('session.php'),
			'session'
		);

		$app = $this->app;
		$this->registerSessionManager($app);
		$this->registerSessionDriver($app);
	}

	protected function registerSessionManager($app) {
		$app->singleton('session', function ($app) {
			return new SessionManager($app['config']);
		});
	}

	protected function registerSessionDriver($app) {
		$app->singleton('session.driver', function ($app) {
			return $app['session']->driver();
		});
	}
}