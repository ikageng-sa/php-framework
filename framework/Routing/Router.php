<?php

namespace Qanna\Routing;

use Qanna\Foundation\Container;
use Qanna\Http\Enum\HttpStatus;
use Qanna\Http\RedirectResponse;
use Qanna\Http\Request;
use Qanna\Http\ResponseFactory;
use Qanna\View\View;

/**
 * Registers routes
 */
class Router {

	protected RouteCollection $routes;

	protected Container $container;

	public Route $current;

	public function __construct($app) {
		$this->container = $app;
		$this->routes = new RouteCollection();
	}

	/**
	 * Registers a GET route.
	 *
	 * @param string $uri The URI for the route.
	 * @param \Closure|string|array $handler The handler for the route (can be a controller action or closure).
	 * @return void
	 */
	public function get($uri, $handler) {
		return $this->addRoute(
			$this->createRoute('GET', $uri, $handler)
		);
	}

	/**
	 * Registers a POST route.
	 *
	 * @param string $uri The URI for the route.
	 * @param \Closure|string|array $handler The handler for the route (can be a controller action or closure).
	 * @return void
	 */
	public function post($uri, $handler) {
		return $this->addRoute(
			$this->createRoute('POST', $uri, $handler)
		);
	}

	/**
	 * Registers a PUT route.
	 *
	 * @param string $uri The URI for the route.
	 * @param \Closure|string|array $handler The handler for the route (can be a controller action or closure).
	 * @return void
	 */
	public function put($uri, $handler) {
		return $this->addRoute(
			$this->createRoute('PUT', $uri, $handler)
		);
	}

	/**
	 * Registers a PATCH route.
	 *
	 * @param string $uri The URI for the route.
	 * @param \Closure|string|array $handler The handler for the route (can be a controller action or closure).
	 * @return void
	 */
	public function patch($uri, $handler) {
		return $this->addRoute(
			$this->createRoute('PATCH', $uri, $handler)
		);
	}

	/**
	 * Registers a DELETE route.
	 *
	 * @param string $uri The URI for the route.
	 * @param \Closure|string|array $handler The handler for the route (can be a controller action or closure).
	 * @return void
	 */
	public function delete($uri, $handler) {
		return $this->addRoute(
			$this->createRoute('DELETE', $uri, $handler)
		);
	}

	/**
	 * Creates a route Instance with the specified HTTP method, URI, and handler.
	 *
	 * @param string $method The HTTP method for the route (GET, POST, PATCH, PUT, DELETE).
	 * @param string $uri The URI for the route.
	 * @param \Closure|array|string $handler The handler for the route (can be a controller action or closure).
	 * @return \Qanna\Routing\Route
	 */
	public function createRoute($method, $uri, $handler) {
		return new Route($method, $uri, RouteHandlerParser::parse($handler));
	}

	/**
	 * Adds a route to the routes collection with a specified HTTP method, URI, and handler.
	 *
	 * @param \Qanna\Routing\Route $route
	 * @return \Qanna\Routing\Route
	 */
	public function addRoute(Route $route) {
		$this->routes->add($route);
		return $this->current = $route;
	}

	/**
	 * Dispatch a request
	 *
	 * @param \Qanna\Http\Request $request
	 * @return \Qanna\Http\Response | \Qanna\Http\RedirectResponse
	 */
	public function dispatch(\Qanna\Http\Request $request) {

		$dispatched = $this->routes
			->match($request, $route = $this->routes->findRoute($request)->setContainer($this->container))
			->run();

		$this->container->instance('route', $route);

		if ($dispatched instanceof \Qanna\Http\RedirectResponse) {
			return $dispatched;
		}

		if ($dispatched instanceof View) {
			return $this->createResponse($dispatched, HttpStatus::OK->value);
		}

	}

	/**
	 * Creates a response instance
	 *
	 * @param mixed $view
	 * @param int $status
	 *
	 * @return \Qanna\Http\Response
	 */
	public function createResponse(mixed $view, int $status) {
		return (new ResponseFactory)->view($view, $status);
	}

	public function refreshRouteNames() {
		$this->routes->refreshNames();
	}

	public function getRoutes() {
		return $this->routes;
	}

}