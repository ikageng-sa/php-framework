<?php

namespace Qanna\Routing;

use Qanna\Http\Enum\HttpStatus;
use Qanna\Http\Request;
use Qanna\Routing\Exceptions\HttpException;
use Qanna\Routing\Traits\MatchesUriToRoute;

class RouteCollection implements \ArrayAccess {

	use MatchesUriToRoute;

	protected array $routes = [];

	protected array $lookups = [];

	protected array $routeNames = [];

	public function add(Route $route, $name = null) {
		$method = $route->method();
		$uri = $route->uri();

		$this->addRoute($method, $uri, $route);
		$this->addLookUp($route->name() ?? $uri, $uri);
		return $this;
	}

	protected function addRoute($method, $uri, Route $route) {
		$this->routes[$method][$uri] = $route;
	}

	public function addLookUp($name, $uri) {
		return $this->lookups[$name] = $uri;
	}

	public function offsetExists($route): bool {
		return $this->findRoute($route) == null;
	}

	public function offsetGet($route): mixed {
		return $this->findRoute($route);
	}

	public function offsetSet($route, $value): void {
		//
	}

	public function offsetUnset(mixed $offset): void {
		//
	}

	public function findRoute(Request $request) {

		$matched = $this->resolveUri($this->lookups, $request->pathInfo());

		if (empty($matched)) {
			throw new HttpException(HttpStatus::NOT_FOUND);
		}

		return $this->findRouteFromMethod($request->method(), $matched['uri']);
	}

	protected function findRouteFromMethod(string $method, string $uri) {

		if (!isset($this->routes[$method])) {
			return null;
		}

		return $this->routes[$method][$uri] ?? null;
	}

	public function findUriByName($name) {
		$uri = null;
		if (isset($this->routeNames[$name])) {
			$uri = $this->routeNames[$name];
		}

		return $uri;
	}

	public function findRouteByName($name) {
		$route = null;
		if ($uri = $this->findUriByName($name)) {
			foreach ($this->routes as $method => $routes) {
				if (isset($routes[$uri])) {
					$route = $routes[$uri];
					break;
				}
			}
		}

		return $route;
	}

	public function refreshNames() {
		$routes = $this->routes;

		foreach ($routes as $method) {
			foreach ($method as $route) {
				if (($name = $route->name()) !== null) {
					$this->routeNames[$name] = $route->uri();
				}
			}
		}
	}

	public function all() {
		return $this->routes;
	}

	public function match(Request $request, ?Route $route) {
		if (!$route) {
			throw new HttpException(HttpStatus::INTERNAL_SERVER_ERROR);
		}

		$route->bindParams($request);
		return $route;
	}

	public function getRoutes() {
		return $this->routes;
	}

	public function getRouteNames() {
		return $this->routeNames;
	}
}