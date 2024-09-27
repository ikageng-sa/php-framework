<?php

namespace Qanna\Routing;

use Qanna\Http\Request;

class UrlGenerator {

	public RouteCollection $routes;

	public ?Request $request;

	public function __construct(RouteCollection $routes, ?Request $request) {
		$this->routes = $routes;
		$this->request = $request;
	}

	public function route($uri, array $data = []) {
		return $this->routes->resolveUriParameters($uri, $data);
	}

	public function getRoutes() {
		return $this->routes->getRoutes();
	}

	public function getRouteNames() {
		return $this->routes->getRouteNames();
	}

	public function getRequest() {
		return $this->request;
	}

}