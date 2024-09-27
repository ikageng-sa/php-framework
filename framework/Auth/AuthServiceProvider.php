<?php

namespace Qanna\Auth;

use Qanna\Database\Query\Builder;
use Qanna\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->app->singleton('auth', function ($app) {
			$session = $app->make('session.driver');
			$builder = $app[Builder::class];
			return new AuthManager($session, $builder);
		});
	}
}