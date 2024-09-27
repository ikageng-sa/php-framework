<?php

namespace Qanna\Validation\Rules;

use Qanna\Validation\Contracts\RuleContract;

class Confirmed implements RuleContract {

	protected $attribute;

	protected $inputKey;

	protected $matchKey;

	protected $inputs;

	public function __construct($matchKey, $attribute, $inputKey, $inputs) {
		$this->matchKey = $matchKey;
		$this->attribute = $attribute;
		$this->inputKey = $inputKey;
		$this->inputs = $inputs;
	}

	public function passes(): bool {
		return $this->attribute === $this->inputs[$this->matchKey];
	}

	public function message(): string {
		return ':attributes do not match';
	}
}