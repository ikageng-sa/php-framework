<?php

namespace Qanna\Http\Contracts;

interface FormRequestContract {

	/**
	 * Authorize the form request
	 *
	 * @return bool
	 */
	public function authorize(): bool;

	/**
	 * Sets rules to validate form inputs
	 *
	 * @return array
	 */
	public function rules(): array;
}