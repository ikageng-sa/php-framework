<?php

namespace Qanna\View;

use ArrayAccess;
use Qanna\Support\Arrayable;

class ViewNamespaceRegistrar implements ArrayAccess {

	use Arrayable;

	public function get($path, $default = null) {
		return static::$container[$path] ?? $default;
	}

	public function set($namespace, $dir) {
		static::$container[$namespace] = $dir;
	}

}