<?php

namespace Qanna\Validation\Facades;

use Qanna\Support\Facades\Facade;
use Qanna\Validation\ValidationFactory;

class Rule extends Facade {
	protected static function accessor(): ?string {
		return ValidationFactory::class;
	}
}