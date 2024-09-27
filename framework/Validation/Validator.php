<?php

namespace Qanna\Validation;

use Qanna\Http\FormRequest;
use Qanna\Http\Request;

class Validator {

	protected FormRequest $request;

	protected array $errorBag = [];

	public function __construct(FormRequest $request) {
		$this->request = $request;
	}

	public function make(array $data = [], array $rules = []) {

	}

	public function validatingPostRequest() {
		return !empty($this->request->all());
	}

	public function validate() {

		if (!$this->authorized()) {
			echo "Validation request is unauthorized";
			exit;
		}

		$factory = $this->getValidationFactory();

		if ($this->validatingPostRequest()) {
			$inputs = $this->request->all();
		}

		foreach ($this->getRules() as $attribute => $ruleSet) {
			if (is_string($ruleSet)) {
				$ruleSet = explode('|', $ruleSet);
			}

			foreach ($ruleSet as $rule) {
				$parameters = [];
				$validated = null;

				if ($isRule = strstr($rule, ':', true)) {
					$parameters = $this->parseParameters($rule);
					$rule = $isRule;
				}

				$parameters = array_merge($parameters, [
					$this->{$attribute}, $attribute, $inputs,
				]);
				if (method_exists($factory, $rule)) {
					$validated = $this->validateFromFactory($factory, $rule, $parameters);
				}

				if (is_string($validated)) {
					$this->errorBag[$attribute] = str_replace(':attribute', ucfirst($attribute), $validated);
				}
			}
		}

		return $this;
	}

	protected function validateFromFactory($factory, $rule, $parameters) {
		return $factory->{$rule}(...$parameters);
	}

	protected function parseParameters($rule) {
		$parameters = null;
		if (strstr($rule, ':')) {
			$parameters = trim(strstr($rule, ':'), ':');

			$parameters = explode('.', $parameters);

			foreach ($parameters as $key => $param) {
				if (str_contains($param, ',')) {
					$parameters[$key] = explode(',', $param);
				}
			}

			$parameters = count($parameters) == 1 && is_array($parameters[0]) ? $parameters[0] : $parameters;
		}
		return $parameters;
	}

	public function authorized(): bool {
		return $this->request->authorize();
	}

	protected function getValidationFactory() {
		return new ValidationFactory();
	}

	protected function getRules() {
		return $this->request->rules();
	}

	public function getErrorBag() {
		return $this->errorBag;
	}

	public function passed() {
		return empty($this->errorBag);
	}

	public function __get($name) {
		return $this->request->{$name};
	}

}