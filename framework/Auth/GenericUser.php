<?php

namespace Qanna\Auth;

class GenericUser implements UserInterface {
	/**
	 * $var array
	 */
	protected $attributes = [];

	public function __construct($attributes = []) {
		$this->attributes = $attributes;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function getPassword(): ?string {
		return $this->password;
	}

	public function __set(string $name, mixed $value) {
		return $this->attributes[$name] = $value;
	}

	public function __get(string $name) {
		return $this->attributes[$name] ?? null;
	}
}