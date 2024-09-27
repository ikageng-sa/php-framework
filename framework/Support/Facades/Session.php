<?php

namespace Qanna\Session\Facades;

use Qanna\Support\Facades\Facade;

class Session extends Facade {
	protected static function accessor(): ?string {
		return 'session';
	}
}