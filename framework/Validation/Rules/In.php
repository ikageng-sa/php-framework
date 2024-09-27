<?php

namespace Qanna\Validation\Rules;

use Qanna\Validation\Contracts\RuleContract;

class In implements RuleContract {

	protected $attribute;

	public function __construct($iterable, $attribute) {
		$this->attribute = $attribute;
	}

	public function passes(): bool {
		return is_string($this->attribute);
	}

	public function message(): string {
		return ':attribute must be a string';
	}
}