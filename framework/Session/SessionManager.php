<?php

namespace Qanna\Session;

use Qanna\Config\Repository;
use Qanna\Foundation\Manager;
use Qanna\Session\Handlers\ArraySession;

class SessionManager extends Manager {

	protected string $default;

	protected array $drivers = [];

	public function __construct(Repository $config) {
		$this->config = $config;
		$this->default = $config->get('session.driver', 'array');
	}

	protected function config($driver): mixed {
		return $this->config->get("session.drivers.$driver") ?? $this->config->get('session');
	}

	protected function createArrayDriver($settings) {
		return $this->buildSession(
			new ArraySession($settings)
		);
	}

	protected function buildSession($handler) {
		return new Store($handler);
	}

	public function __call($method, $arguments) {
		return $this->driver()->{$method}(...$arguments);
	}

}