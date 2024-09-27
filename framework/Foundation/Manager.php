<?php

namespace Qanna\Foundation;

use InvalidArgumentException;
use Qanna\Config\Repository;

abstract class Manager {

	protected Repository $config;

	protected string $default;

	protected array $drivers = [];

	public abstract function __construct(Repository $config);

	protected function getDefaultDriver(): string {
		return $this->default;
	}

	protected abstract function config($driver): mixed;

	public function driver() {
		$driver = $this->getDefaultDriver();
		$config = $this->config($driver);

		if (isset($this->drivers[$driver])) {
			return $this->drivers[$driver];
		}

		$method = 'create' . ucFirst($driver) . 'Driver';

		if (method_exists($this, $method)) {
			$session = $this->{$method}($config);
			return $this->drivers[$driver] = $session;
		}

		throw new InvalidArgumentException("Driver {$driver} is not supported");
	}

	public function __call($method, $arguments) {
		return $this->driver()->{$method}(...$arguments);
	}
}