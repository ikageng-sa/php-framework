<?php

namespace Qanna\Support\Facades;

class View extends Facade {
	protected static function accessor(): string | null {
		return 'view';
	}
}