<?php

namespace Qanna\Routing;

use Qanna\Foundation\Container;
use Qanna\Http\Request;

class ControllerDispatcher {

	protected ?Container $container;

	protected Request $request;

	protected Route $route;

	protected $controller;

	protected $method;

	function __construct(Route $route, $controller, $method) {
		$this->route = $route;
		$this->controller = $controller;
		$this->method = $method;
	}

	public function setContainer(?Container $container) {
		$this->container = $container;
		return $this;
	}

	public function request(Request $request) {
		$this->request = $request;
		return $this;
	}

	public function dispatch() {

		$controller = new ($this->controller)();
		$method = $this->method;

		$parameters = (new ParameterResolver($controller, $method, $this->container))->getResolvedParameters($this->route);

		return $controller->{$method}(...$parameters);
	}
}