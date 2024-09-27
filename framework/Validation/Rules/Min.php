<?php

namespace Qanna\Validation\Rules;

use Qanna\Validation\Contracts\RuleContract;

class Min implements RuleContract {

	protected $length;
	protected $attribute;

	public function __construct($length, $attribute) {
		$this->length = $length;
		$this->attribute = $attribute;
	}

	public function passes(): bool {

		if (is_string($this->attribute)) {
			return strlen($this->attribute) >= $this->length;
		}

		return $this->attribute >= $this->length;
	}

	public function message(): string {
		if (is_string($this->attribute)) {
			return ":attribute must have at least {$this->length} characters";
		}

		return ":attribute must be more than {$this->length}";
	}
}