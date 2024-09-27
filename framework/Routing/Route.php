<?php

namespace Qanna\Routing;

use ArgumentCountError;
use Qanna\Foundation\Container;
use Qanna\Http\Request;
use Qanna\Routing\Traits\BindsParameters;

class Route {

	use BindsParameters;

	protected Container $container;

	protected $name;

	protected $uri;

	protected $method;

	protected $handler;

	protected $parameters;

	public function __construct($method, $uri, $handler) {
		$this->method = $method;
		$this->uri = $uri;
		$this->handler = $handler;
	}

	public function method() {
		return $this->method;
	}

	public function uri() {
		return $this->uri;
	}

	public function name($name = null) {
		if (!$name) {
			return $this->name;
		}

		$this->name = $name;
		return $this;
	}

	public function bindParams(Request $request) {
		$this->parameters = $this->resolveParams($request->pathInfo(), $this->uri());
		return $this;
	}

	public function setContainer(Container $container) {
		$this->container = $container;
		return $this;
	}

	public function parameters() {
		return $this->parameters;
	}

	public function run() {
		try {
			if ($this->isController()) {
				return $this->runController();
			}

			return $this->runCallable();
		} catch (ArgumentCountError $e) {
			dd($e->getMessage());
		}
	}

	public function isController() {
		return key_exists('controller', $this->handler);
	}

	public function runController() {
		return (new ControllerDispatcher($this, $this->getController(), $this->getAction()))->setContainer($this->container ?? null)->dispatch();
	}

	public function getController() {
		return $this->handler['controller'];
	}

	public function getAction() {
		return $this->handler['action'];
	}

	public function runCallable() {
		return (new CallableDispatcher($this, $this->getAction()))->setContainer($this->container ?? null)->dispatch();
	}

}