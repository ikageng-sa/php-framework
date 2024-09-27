<?php

namespace Qanna\Foundation\Http;

use Qanna\Foundation\App;
use Qanna\Http\RedirectResponse;
use Qanna\Http\Request;
use Qanna\Http\Response;
use Throwable;

class Kernel {

	/**
	 * The Kernel handles incoming HTTP requests and provides a response.
	 * It serves as the central point in the request lifecycle, dispatching
	 * the request through the routing system and returning a response.
	 *
	 * @param App $app The application instance responsible for managing facades, services, and configurations.
	 */
	public function __construct(
		protected App $app,
	) {}

	/**
	 * Handle the incoming HTTP request and return an HTTP response.
	 *
	 * This method uses the routing system to find the appropriate
	 * controller and action for the incoming request,
	 * dispatches it, and returns a response.
	 *
	 * @param Request $request The incoming HTTP request,
	 * containing all necessary data like method, path, and
	 * form data.
	 * @return Response The generated HTTP response, containing
	 * the content to be returned to the client.
	 */
	public function handle(Request $request): Response | RedirectResponse {

		$this->app->instance('request', $request);

		$router = $this->app->make('router');

		try {

			$response = $router->dispatch($request);

		} catch (Throwable $e) {
			if (method_exists($e, 'asResponse')) {
				return $e->asResponse();
			}
			dd($e);
		}

		return $response;
	}

}
