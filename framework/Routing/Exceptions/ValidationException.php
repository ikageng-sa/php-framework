<?php

namespace Qanna\Routing\Exceptions;

use Qanna\Http\Enum\HttpStatus;
use Qanna\Http\RedirectResponse;
use Qanna\Http\Request;

class ValidationException extends \Exception {

	protected $statusCode;
	private $request;

	public function __construct(Request $request, HttpStatus $statusCode = HttpStatus::BAD_REQUEST) {

		$this->statusCode = $statusCode->value;
		$this->request = $request;

		parent::__construct($message ?? $statusCode->getMessage());
	}

	public function asResponse(): RedirectResponse {

		return new RedirectResponse($this->request->referer(), $this->statusCode);
	}
}