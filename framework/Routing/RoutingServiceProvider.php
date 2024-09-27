<?php

namespace Qanna\Routing;

use Qanna\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->registerRouter();
		$this->registerUrlGenerator();
		$this->registerRedirector();
	}

	protected function registerRouter() {
		$this->app->singleton('router', fn() => new Router($this->app));
	}

	protected function registerUrlGenerator() {
		$this->app->bind('url', function ($app) {
			$router = $app->make('router');
			$request = $app->make('request');
			return new UrlGenerator($router->getRoutes(), $request);
		});
	}

	protected function registerRedirector() {
		$app = $this->app;
		$app->bind('redirector', function ($app) {
			$generator = $app->make('url');
			$session = $app->make('session')->driver();
			return new Redirector($generator, $session);
		});
	}

}