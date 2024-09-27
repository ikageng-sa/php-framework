<?php

namespace Qanna\Support\Facades;

class Redirector extends Facade {
	protected static function accessor(): string | null {
		return 'redirector';
	}
}