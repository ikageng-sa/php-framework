<?php

namespace Qanna\Routing;

use Qanna\Foundation\Container;

class CallableDispatcher {

	protected ?Container $container;

	protected Route $route;

	protected $callable;

	public function __construct(Route $route, callable $callable) {
		$this->route = $route;
		$this->callable = $callable;
	}

	public function setContainer(?Container $container) {
		$this->container = $container;
		return $this;
	}

	public function dispatch() {
		$callable = $this->callable;

		$parameters = (new ParameterResolver(classormethod: $callable, method: null, container: $this->container))->getResolvedParameters($this->route);

		return $callable(...$parameters);
	}
}