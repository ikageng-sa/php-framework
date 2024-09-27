<?php

namespace Qanna\Http;

use Qanna\Session\Contracts\Session;

class RedirectResponse {
	protected $url;
	protected $statusCode;
	protected $headers = [];
	protected ?Session $session;

	public function __construct($url, $statusCode = 302, $headers = [], ?Session $session = null) {
		$this->url = $url;
		$this->statusCode = $statusCode;
		$this->headers = $headers;
		$this->session = $session;
	}

	public function getUrl() {
		return $this->url;
	}

	public function with($key, $value = null) {
		$this->session->flash($key, $value);
		return $this;
	}

	public function withErrors($value) {
		$this->session->flash('errors', $value);
		return $this;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function getHeaders() {
		return $this->headers;
	}

	protected function sendHttpResponseCode() {
		http_response_code($this->statusCode);
	}

	protected function sendHeaders() {

		foreach ($this->headers as $header => $value) {
			header("$header: $value");
		}
	}

	public function send() {
		// Send the status code for the redirect
		$this->sendHttpResponseCode();
		// Set the location header for the redirect
		header("Location: " . $this->url);
		// Send any additional headers
		$this->sendHeaders();

		exit;
	}
}
