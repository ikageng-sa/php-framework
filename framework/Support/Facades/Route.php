<?php

namespace Qanna\Support\Facades;

/**
 * @method static \Qanna\Routing\Route delete(string $uri, \Closure|array|string|null $handler)
 * @method static \Qanna\Routing\Route get(string $uri, \Closure|array|string|null $handler)
 * @method static \Qanna\Routing\Route patch(string $uri, \Closure|array|string|null $handler)
 * @method static \Qanna\Routing\Route post(string $uri, \Closure|array|string|null $handler)
 * @method static \Qanna\Routing\Route put(string $uri, \Closure|array|string|null $handler)
 * @method static \Qanna\Frame\Routing\RouteRergistrar name(string $name)
 * @method static \Qanna\Frame\Routing\RouterRegistrar dispatch(\Qanna\Frame\Http\Request $request)
 *
 * @see \Qanna\Frame\Routing\Router
 */
class Route extends Facade {
	protected static function accessor(): string | null {
		return 'router';
	}
}