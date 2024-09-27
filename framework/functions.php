<?php

use Qanna\Support\Facades\Route;
use Qanna\Support\Facades\View;
use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
	/**
	 * Dump values
	 **/
	function dd(mixed ...$vars): never {

		if (array_key_exists(0, $vars) && 1 === count($vars)) {
			VarDumper::dump($vars[0]);
		} else {
			foreach ($vars as $k => $v) {
				VarDumper::dump($v, is_int($k) ? 1 + $k : $k);
			}
		}

		exit(1);
	}
}

if (!function_exists('config')) {
	/**
	 * Get a configuration
	 *	@param string $keys
	 * 	@return array|string|null
	 **/
	function config(string $keys, $default = null): array | string | null {
		return app('config')->get($keys, $default);
	}
}

if (!function_exists('getInstanceOf')) {
	/**
	 * Get an instance of a class
	 * @param string $class
	 * @return object
	 * */
	function getInstanceOf($class): object {
		return new $class();
	}
}

if (!function_exists('view')) {
	/**
	 * Render a view
	 * @param string $template
	 * @param array $mergeData
	 * @return /Frame/View/View
	 **/
	function view(string $template, array $mergeData = []) {
		return View::make($template, $mergeData);
	}
}

if (!function_exists('resources_path')) {
	/**
	 * Go to the resources path
	 **/
	function resources_path($key = null, $extension = '.template.php') {
		$key = str_replace('.', DIRECTORY_SEPARATOR, $key);
		$path = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, '/../resources/') . $key . $extension;

		if (file_exists($path)) {
			return $path;
		}

		return null;

	}
}

if (!function_exists('views_path')) {
	/**
	 * Go to the views path
	 **/
	function views_path($file = null, $ext = null) {
		$path = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . "/../resources/views/$file");
		$path .= isset($ext) ? ".$ext" : '';
		return $path;
	}
}

if (!function_exists('config_path')) {
	/**
	 * Go to the config path
	 **/
	function config_path($file = null) {
		$path = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . "/../config/$file");
		return $path;
	}
}

if (!function_exists('base_path')) {
	/**
	 * Go to the views path
	 **/
	function base_path($file = null) {
		$path = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/../$file");
		return $path;
	}
}

if (!function_exists('storage_path')) {
	function storage_path($file = null) {
		$path = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/../storage/$file");
		return $path;
	}
}

if (!function_exists('route')) {
	function route($uri, array $data = []) {
		$generatedUrl = app()->make('url')->route($uri, $data);
		return $generatedUrl;
	}
}

if (!function_exists('app')) {
	function app($key = null) {
		global $app;
		return ($key !== null) ? $app[$key] : $app;
	}
}

if (!function_exists('event')) {
	function event($event) {
		return app('events')->dispatch($event);
	}
}

if (!function_exists('assets')) {
	function assets($file) {
		$uri = app('config')->get('app.uri') ?? './';
		echo $uri . "assets/$file";
	}
}

if (!function_exists('redirect')) {
	function redirect($url = '', $status = 302, array $headers = []) {
		$redirector = app()->make('redirector');

		if (!empty($url)) {
			$redirector->to($url, $status, $headers);
		}

		return $redirector;
	}
}

if (!function_exists('session')) {
	function session($key = null) {
		$session = app('session.driver');
		$session->start();
		if (isset($key)) {
			return $session->get($key, null);
		}

		return $session;
	}
}

if (!function_exists('error')) {
	function error($key) {
		$session = app('session.driver');
		$session->start();
		$message = $session->getFlash('errors.' . $key);

		return $message;
	}
}

if (!function_exists('auth')) {
	function auth() {
		return app('auth');
	}
}