<?php

namespace Qanna\Support;

trait Arrayable {
	private static $container = [];

	public function offsetSet($key, $value): void {
		static::set($key, $value);
	}

	public function offsetExists($key): bool {
		return static::get($key) == null;
	}

	public function offsetUnset($key): void {
		unset(static::$container[$key]);
	}

	public function offsetGet($key): mixed {
		return static::get($key);
	}

	/**
	 * Set value using dot notation
	 * @param string $path
	 *
	 **/
	public static function set($path, $value) {
		$keys = explode('.', $path);
		$current = &static::$container;

		foreach ($keys as $key) {
			if (!isset($current[$key])) {
				$current[$key] = [];
			}
			$current = &$current[$key];
		}
		$current = $value;
	}

	/**
	 * Get value using dot notation
	 * @param string $path
	 **/
	public static function get($path) {
		$keys = explode('.', $path);
		$current = &static::$container;

		foreach ($keys as $key) {
			if (!isset($current[$key])) {
				return null;
			}
			$current = &$current[$key];
		}

		return $current;
	}

	public function all() {
		return static::$container;
	}

	public static function __callStatic($method, $args) {
		call_user_func_array([(new static ), $method], $args);
	}
}