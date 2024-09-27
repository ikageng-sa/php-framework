<?php

namespace Qanna\Foundation;

use Qanna\Auth\AuthServiceProvider;
use Qanna\Config\Repository;
use Qanna\Database\DatabaseServiceProvider;
use Qanna\Foundation\BaseServiceProvider;
use Qanna\Routing\RoutingServiceProvider;
use Qanna\Session\SessionServiceProvider;
use Qanna\View\ViewServiceProvider;

class App extends Container {

	protected $basePath;

	public $providers = [];

	public $facades = [];

	protected bool $baseProviderBooted = false;

	protected $baseProviders = [
		BaseServiceProvider::class,
		DatabaseServiceProvider::class,
		SessionServiceProvider::class,
		ViewServiceProvider::class,
		RoutingServiceProvider::class,
		AuthServiceProvider::class,
	];

	public function __construct(string $basePath = null) {
		if (!empty($basePath)) {
			$this->basePath = "$basePath";
		}
		$this->registerBaseBindings();

		$this->register($this->baseProviders);
	}

	protected function registerBaseBindings() {
		$this->singleton('config', fn() => (new Repository()));
	}

	protected function register(array $providers) {
		foreach ($providers as $key => $value) {
			if (is_array($value)) {
				$this->register($value);
				continue;
			}

			$provider = new $value($this);
			$provider->register();
		}
	}

	protected function initializeProvider($key, $provider) {
		if (!$provider instanceof \Qanna\Support\ServiceProvider) {
			return $this->providers[$key] = new $provider($this);
		}

		return null;
	}

	protected function bootApplicationBaseProvider() {
		$baseProviders = [];

		foreach ($this->baseProviders as $provider) {
			$baseProviders[] = new $provider($this);
		}

		foreach ($baseProviders as $provider) {
			$provider->boot();
		}

		foreach ($baseProviders as $provider) {
			$provider->register();
		}

		return $this->baseProviderBooted = true;
	}

	public function provider(string $serviceProvider) {
		$this->providers[] = $serviceProvider;
	}

}