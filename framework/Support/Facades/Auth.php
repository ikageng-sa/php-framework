<?php

namespace Qanna\Support\Facades;

class Auth extends Facade {
	protected static function accessor(): ?string {
		return 'auth';
	}
}