<?php

namespace Qanna\Config;

use Qanna\Support\Arrayable;
use \ArrayAccess;

class Repository implements ArrayAccess {

	use Arrayable;

	public static function get($path, $default = null) {
		$keys = explode('.', $path);
		$current = &static::$container;

		foreach ($keys as $key) {
			if (!isset($current[$key])) {
				return $default;
			}
			$current = &$current[$key];
		}

		return $current;
	}
}