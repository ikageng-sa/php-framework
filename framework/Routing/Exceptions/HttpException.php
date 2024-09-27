<?php

namespace Qanna\Routing\Exceptions;

use Qanna\Http\Enum\HttpStatus;
use Qanna\Http\RedirectResponse;
use Qanna\Http\Response;
use Qanna\Http\ResponseFactory;
use Qanna\Support\Facades\View;

class HttpException extends \Exception {

	protected $statusCode;

	public function __construct(HttpStatus $statusCode = HttpStatus::INTERNAL_SERVER_ERROR, $message = null) {
		$this->statusCode = $statusCode->value;
		parent::__construct($message ?? $statusCode->getMessage());
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function asResponse(): Response | RedirectResponse {
		$data = [
			'status' => $code = $this->getStatusCode(),
			'message' => $this->getMessage(),
		];
		return (new ResponseFactory)
			->view(
				content: View::make('errors::error', $data),
				status: $code
			);
	}
}