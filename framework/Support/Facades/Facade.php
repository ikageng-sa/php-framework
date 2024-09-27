<?php

namespace Qanna\Support\Facades;

class Facade {

	protected static \Qanna\Foundation\App $app;

	protected static function accessor(): string | null {
		return null;
	}

	public static function setAppInstance($app) {
		static::$app = $app;
	}

	public static function __callStatic(string $method, array | string $arguments = null) {
		$app = static::$app;
		$accessor = static::accessor();

		if (!isset($app[$accessor])) {
			$app->instance($accessor, new $accessor());
		}

		$accessor = $app->make(static::accessor());

		return $accessor->$method(...$arguments);
	}
}