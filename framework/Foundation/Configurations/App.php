<?php

namespace Qanna\Foundation\Configurations;

use Qanna\Foundation\App as Application;
use Qanna\Support\Facades\Facade;

class App {

	protected string $basePath;

	protected string $routesPath = '';

	protected static $application;

	public function __construct(string $basePath) {
		if (!empty($basePath)) {
			$this->basePath = "$basePath";
		}
	}

	public static function configure($path) {
		return new static(
			basePath: $path
		);
	}

	public function withRouting($routesPath) {
		$this->routesPath = $this->basePath . $routesPath;
		return $this;
	}

	public function create() {
		static::$application = new Application($this->basePath);

		Facade::setAppInstance(static::$application);

		if ($this->routesPath) {
			require $this->routesPath;
			static::$application->make('router')->refreshRouteNames();
		}

		return static::$application;
	}

	public static function __callStatic($name, $arguments) {
		return static::$application;
	}

}