<?php

namespace Qanna\View;

use Qanna\Support\ServiceProvider;
use Qanna\View\ViewNamespaceRegistrar;

class ViewServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->registerConfig();
		$this->registerBindings();
		$this->registerViewNamespaces();
	}

	protected function registerConfig() {
		$this->mergeConfigFrom(config_path('view.php'), 'view');
	}

	protected function registerBindings() {
		$this->app->bind('view', fn() => (new View($this->app)));
		$this->app->singleton('view.registrar', fn() => (new ViewNamespaceRegistrar));
	}

	protected function registerViewNamespaces() {
		$this->loadViewsFrom(
			views_path(),
			'default'
		);
		$this->loadViewsFrom(
			__DIR__ . '/resources/views/',
			'errors'
		);
	}
}