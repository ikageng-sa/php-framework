<?php

namespace Qanna\Support;
/**
 * Class Rule
 *
 * Represents a validation rule that can be applied to a set of input data.
 * This class is intended to be extended to define specific validation rules.
 */
class Rule {

	/**
	 * @var array The input data to be validated.
	 */
	protected array $inputs;

	/**
	 * @var string The attribute of the input data being validated.
	 */
	protected string $attribute;

	/**
	 * @var string|array|null The parameters for the validation rule.
	 */
	protected string | array | null $params;

	/**
	 * Rule constructor.
	 *
	 * @param array $inputs The input data to be validated.
	 * @param string $attribute The attribute of the input data being validated.
	 * @param string|array|null $params The parameters for the validation rule.
	 */
	public function __construct(
		array $inputs,
		string $attribute,
		string | array | null $params
	) {
		$this->inputs = $inputs;
		$this->attribute = $inputs[$attribute] ?? null;
		$this->params = $params;
	}

	/**
	 * Defines the validation rule logic.
	 *
	 * This method should be overridden by subclasses to implement specific validation logic.
	 *
	 * @return bool Returns true if the rule is valid, false otherwise.
	 */
	protected function rule(): bool {
		return false;
	}

	/**
	 * Provides the validation error message.
	 *
	 * This method should be overridden by subclasses to provide a specific error message.
	 *
	 * @return string The error message to be returned if validation fails.
	 */
	protected function message(): string {
		return '';
	}

	/**
	 * Validates the input data against the rule.
	 *
	 * Returns true if the input data is valid according to the rule, otherwise returns an error message.
	 *
	 * @return bool|string True if validation passes, or an error message if validation fails.
	 */
	public function validate(): bool | string {
		if (!$this->rule()) {
			return $this->message();
		}

		return true;
	}

	/**
	 * Magic method to access protected properties.
	 *
	 * Provides read-only access to the protected properties of the class.
	 *
	 * @param string $name The name of the property to access.
	 * @return mixed|null The value of the property, or null if it does not exist.
	 */
	public function __get($name) {
		return $this->{$name} ?? null;
	}
}
