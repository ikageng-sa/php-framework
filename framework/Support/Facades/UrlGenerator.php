<?php

namespace Qanna\Support\Facades;

class UrlGenerator extends Facade {
	protected static function accessor(): string | null {
		return 'url';
	}
}