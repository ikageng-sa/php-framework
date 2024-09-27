<?php

namespace Qanna\Validation;

use Qanna\Validation\Rules\Confirmed;
use Qanna\Validation\Rules\Email;
use Qanna\Validation\Rules\In;
use Qanna\Validation\Rules\IsString;
use Qanna\Validation\Rules\Max;
use Qanna\Validation\Rules\Min;
use Qanna\Validation\Rules\Nullable;
use Qanna\Validation\Rules\Numeric;
use Qanna\Validation\Rules\Required;
use Qanna\Validation\Rules\Unique;

class ValidationFactory {

	public function string($attribute): bool | string {
		$rule = new IsString($attribute);
		$passed = $rule->passes();
		return (!$passed) ? $rule->message() : true;
	}

	public function numeric($attribute): bool | string {
		$rule = new Numeric($attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function email($attribute): bool | string {
		$rule = new Email($attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function min($length, $attribute): bool | string {
		$rule = new Min($length, $attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function required($attribute): bool | string {
		$rule = new Required($attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function nullable($attribute): bool | string {
		$rule = new Nullable($attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function unique($table, $attribute, $inputKey): bool | string {
		$rule = new Unique($table, $attribute, $inputKey);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function exists($table, $attribute, $inputKey): bool | string {
		$rule = new Unique($table, $attribute, $inputKey);
		$passed = !$rule->passes();
		return ($passed) ? true : ':attribute does not exist';
	}

	public function confirmed($matchKey, $attribute, $inputKey, $inputs): bool | string {
		$rule = new Confirmed($matchKey, $attribute, $inputKey, $inputs);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function max($length, $attribute) {
		$rule = new Max($length, $attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}

	public function in($iterable, $attribute) {
		$rule = new In($iterable, $attribute);
		$passed = $rule->passes();
		return ($passed) ? true : $rule->message();
	}
}