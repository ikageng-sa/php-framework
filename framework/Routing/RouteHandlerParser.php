<?php

namespace Qanna\Routing;

/**
 * Class Parser
 *
 * Provides methods to parse handlers into a structured format. Handlers can be provided as strings or arrays,
 * and this class will parse them to extract the controller and resource details.
 */
class RouteHandlerParser {

	/**
	 * Parses a handler into a structured format.
	 *
	 * @param mixed $handler The handler to be parsed. Can be a string in the format `Controller@method` or an array
	 *                       where the controller and resource are specified.
	 * @return array An associative array with keys `controller` and `resource`.
	 */
	public static function parse($handler) {
		$parsedController = [];

		if (is_string($handler) && strpos($handler, '@')) {
			// If handler is a string and contains '@', parse it as a string format
			$parsedController = static::parseFromStr($handler);
		} elseif (is_array($handler)) {
			// If handler is an array, parse it as an array format
			$parsedController = static::parseFromArr($handler);
		} elseif (is_callable($handler)) {
			$parsedController = ['action' => $handler];
		}

		return $parsedController;
	}

	/**
	 * Parses a handler given as a string.
	 *
	 * @param string $handler The handler string to be parsed, typically in the format `Controller@method`.
	 * @return array An associative array with `controller` and `resource` extracted from the string.
	 */
	private static function parseFromStr($handler) {
		$parsedHandler = [];

		foreach (explode('@', $handler) as $var) {
			if (strpos($var, 'Controller')) {
				// Assuming the controller name ends with 'Controller'
				$parsedHandler['controller'] = "App\\Controllers\\$var";
			} else {
				$parsedHandler['action'] = $var;
			}
		}

		return $parsedHandler;
	}

	/**
	 * Parses a handler given as an array.
	 *
	 * @param array $handler The handler array to be parsed.
	 * @return array An associative array with `controller` and `resource` extracted from the array.
	 */
	private static function parseFromArr($handler) {
		$parsedHandler = [];

		foreach ($handler as $var) {
			if (str_contains($var, '\\')) {
				// Assuming a fully qualified class name for the controller
				$parsedHandler['controller'] = $var;
			} else {
				$parsedHandler['action'] = $var;
			}
		}

		return $parsedHandler;
	}
}
