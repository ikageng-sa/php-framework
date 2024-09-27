<?php

namespace Qanna\Routing\Traits;

use Exception;
use InvalidArgumentException;

trait MatchesUriToRoute {

	use ResolvesUri;

	/**
	 * Matches a route to the given HTTP method and URI.
	 *
	 * @param string $method The HTTP method of the request.
	 * @param string $uri The requested URI.
	 * @return bool True if a route matches, false otherwise.
	 */
	public function resolveUri($lookups, $uri) {

		if (empty($this->routes)) {
			return null;
		}

		foreach ($lookups as $pattern) {
			$routeParts = $this->splitRoute($pattern);
			$uriParts = $this->splitRoute($uri);
			$placeholdersCount = $this->countPlaceholders($pattern);

			if ($this->partsMatch($routeParts, $uriParts)) {
				$result = $this->extractParts($routeParts, $uriParts);

				if ($result['match'] && $placeholdersCount === $result['placeHolders']) {
					return array_merge($result, ['uri' => $pattern]);
				}

			}
		}

		return false;
	}

	/**
	 * Resolves the URI parameters based on the given route and data.
	 *
	 * @param string $uri The URI pattern to resolve.
	 * @param array $data The data to replace placeholders with.
	 * @return string The URI with resolved parameters.
	 * @throws Exception If a required parameter is missing.
	 */
	public function resolveUriParameters($uri_or_name, array $data = []) {
		$key = $this->routeNames[$uri_or_name] ?? $this->lookups[$uri_or_name] ?? null;
		if (!$key) {
			throw new Exception('Undefined route ' . $uri_or_name);
		}

		preg_match_all('/\{.*?\}/', $key, $params);

		$matches = array_diff(array_map(
			fn($value) => preg_replace('/[{}]/', '', $value), $params[0]
		), array_keys($data));

		if (!empty($matches)) {
			throw new InvalidArgumentException("Missing parameter(s) '" . implode(', ', array_values($matches)) . "'");
		};

		return preg_replace_callback('/\{(\w+)\}/', function ($matches) use ($data) {
			$key = $matches[1];
			return isset($data[$key]) ? $data[$key] : $matches[0];
		}, $key);
	}

}