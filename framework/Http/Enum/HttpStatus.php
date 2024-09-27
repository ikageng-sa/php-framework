<?php

namespace Qanna\Http\Enum;

enum HttpStatus: int {

	// 2xx Success
case OK = 200;
case CREATED = 201;
case NO_CONTENT = 204;

	// 4xx Client Errors
case BAD_REQUEST = 400;
case UNAUTHORIZED = 401;
case FORBIDDEN = 403;
case NOT_FOUND = 404;
case METHOD_NOT_ALLOWED = 405;
case REQUEST_TIMEOUT = 408;
case CONFLICT = 409;

	// 5xx Server Errors
case INTERNAL_SERVER_ERROR = 500;
case NOT_IMPLEMENTED = 501;
case BAD_GATEWAY = 502;
case SERVICE_UNAVAILABLE = 503;
case GATEWAY_TIMEOUT = 504;

	// Method to get the message from the enum case
	public function getMessage(): string {
		return match ($this) {
			// 2xx Success
			self::OK => 'OK',
			self::CREATED => 'Created',
			self::NO_CONTENT => 'No Content',

			// 4xx Client Errors
			self::BAD_REQUEST => 'Bad Request',
			self::UNAUTHORIZED => 'Unauthorized',
			self::FORBIDDEN => 'Forbidden',
			self::NOT_FOUND => 'Not Found',
			self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
			self::REQUEST_TIMEOUT => 'Request Timeout',
			self::CONFLICT => 'Conflict',

			// 5xx Server Errors
			self::INTERNAL_SERVER_ERROR => 'Server Error',
			self::NOT_IMPLEMENTED => 'Not Implemented',
			self::BAD_GATEWAY => 'Bad Gateway',
			self::SERVICE_UNAVAILABLE => 'Service Unavailable',
			self::GATEWAY_TIMEOUT => 'Gateway Timeout',
		};
	}
}