<?php

namespace Qanna\Foundation;

use Exception;

class Container implements \ArrayAccess {

	protected $bindings = [];

	protected $resolved = [];

	protected $instances = [];

	protected $aliases = [];

	public function bind($key, $resolver) {
		$this->bindings[$key] = $resolver;
	}

	public function singleton($key, $resolver) {
		$this->bind($key, $resolver);
		return $this->resolve($key);
	}

	protected function resolve($key) {
		$this->resolved[$key] = $this->make($key);
		return $this->resolved($key);
	}

	protected function resolved($key) {
		return isset($this->resolved[$key]);
	}

	public function unresolve($key) {
		if (!$this->resolved($key)) {
			return false;
		}

		unset($this->resolved[$key]);
		return true;
	}

	public function make($key) {

		$resolver = $this->instances[$key] ?? $this->resolved[$key] ?? $this->bindings[$key] ?? null;

		if (!$resolver) {
			//
			return null;
		}

		if (is_callable($resolver)) {
			return $resolver($this);
		}

		return $resolver;
	}

	protected function bound($class) {
		return key_exists($class, $this->bindings);
	}

	public function unbind($key) {
		if (!isset($this->bindings[$key])) {
			return false;
		}

		unset($this->bindings[$key]);
		return true;
	}

	public function instance($key, $instance) {
		if (!is_object($instance)) {
			throw new Exception("{$instance} must be an object");
		}

		return $this->instances[$key] = $instance;

	}

	public function offsetSet($key, $value): void {
		$this->bind($key, $value);
	}

	public function offsetExists($key): bool {
		return $this->make($key) !== null;
	}

	public function offsetUnset($offset): void {
		$this->unbind($offset);
	}

	public function offsetGet($key): mixed {
		try {
			return $this->make($key);
		} catch (Exception $e) {
			throw new Exception("Undefined array key {$key}");
		}
	}

}