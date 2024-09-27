<?php

namespace Qanna\Http;

use Qanna\Http\Contracts\FormRequestContract;
use Qanna\Routing\Exceptions\ValidationException;
use Qanna\Validation\Validator;

class FormRequest extends Request implements FormRequestContract {

	private Request $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}

	public function authorize(): bool {
		return true;
	}

	public function rules(): array {
		return [];
	}

	public function all() {
		return $this->request->post;
	}

	public function validateInput() {
		$validator = $this->createValidator($this)->validate();
		$session = $this->session;
		$session->start();
		if (!$validator->passed()) {
			$session->flash('errors', $validator->getErrorBag());
			throw new ValidationException($this);
		}

		return $this;
	}

	public function referer(): ?string {
		return $this->request->referer();
	}

	public function intented() {
		return $this->request->pathInfo();
	}

	protected function createValidator(Request $request) {
		return new Validator($request);
	}

	public function __get($name) {
		return $this->request->post[$name] ?? $this->request->files[$name] ?? null;
	}
}