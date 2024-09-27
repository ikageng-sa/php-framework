<?php

namespace Qanna\Routing\Traits;

trait ResolvesUri {

	/**
	 * Validates that the request method matches the handler method.
	 *
	 * @param string $method The request method.
	 * @param string $handlerMethod The method defined in the route handler.
	 * @return bool True if they match, false otherwise.
	 */
	protected function validateMethod($method, $handlerMethod) {
		return strtoupper($method) == strtoupper($handlerMethod);
	}

	/**
	 * Splits a route into its components.
	 *
	 * @param string $route The route to split.
	 * @return array The components of the route.
	 */
	protected function splitRoute($route) {
		return explode('/', trim($route, '/'));
	}

	/**
	 * Counts the number of placeholders in the route pattern.
	 *
	 * @param string $pattern The route pattern.
	 * @return int The number of placeholders in the pattern.
	 */
	protected function countPlaceholders($pattern) {
		return substr_count($pattern, '{');
	}

	/**
	 * Checks if the parts of the route match the parts of the URI.
	 *
	 * @param array $routeParts The parts of the route pattern.
	 * @param array $uriParts The parts of the request URI.
	 * @return bool True if they match, false otherwise.
	 */
	protected function partsMatch($routeParts, $uriParts) {
		return count($routeParts) === count($uriParts);
	}

	/**
	 * Extracts the placeholders from the route pattern and matches them to the URI.
	 *
	 * @param array $routeParts The parts of the route pattern.
	 * @param array $uriParts The parts of the request URI.
	 * @return array Array with 'match' status, 'placeHolderCount', and 'params'.
	 */
	protected function extractParts($routeParts, $uriParts) {
		$placeHolderCount = 0;
		$params = [];
		$match = true;

		foreach ($routeParts as $index => $routePart) {
			if ($routePart === $uriParts[$index]) {
				continue;
			} elseif (str_contains($routePart, '{')) {
				$placeHolderCount++;
				$paramName = str_replace(['{', '}'], '', $routePart);
				$params[$paramName] = $uriParts[$index];
			} else {
				$match = false;
				break;
			}
		}

		return [
			'match' => $match,
			'placeHolders' => $placeHolderCount,
			'params' => $params,
		];
	}

}