<?php

namespace Qanna\Validation\Rules;

use Qanna\Validation\Contracts\RuleContract;

class Nullable implements RuleContract {

	protected $attribute;

	public function __construct($attribute) {
		$this->attribute = $attribute;
	}

	public function passes(): bool {
		return !empty($this->attribute) || empty($this->attribute);
	}

	public function message(): string {
		return '';
	}
}