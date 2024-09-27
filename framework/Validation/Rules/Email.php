<?php

namespace Qanna\Validation\Rules;

use Qanna\Validation\Contracts\RuleContract;

class Email implements RuleContract {

	protected $attribute;

	public function __construct($attribute) {
		$this->attribute = $attribute;
	}

	public function passes(): bool {
		return filter_var($this->attribute, FILTER_VALIDATE_EMAIL);
	}

	public function message(): string {
		return ':attribute provided is invalid';
	}
}