<?php

namespace Qanna\Http;

use Exception;
use InvalidArgumentException;

class Response {

	private string | \Qanna\View\View $content = '';

	private int $status = 200;

	private array $headers = [];

	/**
	 * Constructor for the Response class.
	 *
	 * @param string|\Qanna\Frame\Views\View|\Qanna\Frame\Http\FormRequest $content The content to be returned in the response.
	 * It could be a string, a view object, or a form request object.
	 * @param int $status The HTTP status code for the response. Defaults to 200 (OK).
	 * @param array $headers Additional HTTP headers to send with the response.
	 */
	public function __construct($content, $status = 200, array $headers = []) {
		$this->setContent($content);
		$this->setHeaders($headers);
		$this->setResponseCode($status);
	}

	/**
	 * Send the response to the client.
	 * Starts a session if necessary, processes the content, and sends headers and content.
	 *
	 * @return void
	 */
	public function send(): void {
		$this->sendHeaders();
		$this->sendResponseCode();
		$this->getContent($this->content);
	}

	/**
	 * Send the headers to the client.
	 * Loops through any custom headers and sends them.
	 *
	 * @return void
	 */
	protected function sendHeaders(): void {
		foreach ($this->headers as $key => $value) {
			header("$key: $value");
		}
	}

	/**
	 * Send the headers to the client.
	 *
	 * @return void
	 */
	public function sendResponseCode() {
		// Ensure the status code is sent with the headers.
		http_response_code($this->status);
	}

	/**
	 * Sets the http headers.
	 *
	 * @param array $headers
	 * @return self
	 */
	public function setHeaders(array $headers = []) {
		$this->headers = array_merge($this->headers, $headers);

		return $this;
	}

	/**
	 * Sets the http response code
	 *
	 * @param int $code
	 * @return self
	 */
	public function setResponseCode($status) {
		$this->status = $status;
		return $this;
	}

	public function setContent($content) {

		if (empty($content) || !$content instanceOf \Qanna\View\View) {
			throw new Exception('Invalid content type ' . get_class($content));
		}

		$this->content = $content;
		return $this;
	}

	/**
	 * Determine and process the type of content to send.
	 *
	 * @param string|\Qanna\Frame\Views\View|\Qanna\Frame\Http\FormRequest $content The content to be processed and sent.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the content is empty or invalid.
	 */
	protected function getContent($content): void {

		if (empty($this->content)) {
			throw new InvalidArgumentException('No content provided for the response.');
		}

		// If the content is a view, render it.
		if ($this->content instanceof \Qanna\View\View) {
			$content->render();
			return;
		}

		// If the content is a FormRequest, set status 400 and redirect with errors.
		if ($this->content instanceof \Qanna\Http\FormRequest) {
			$this->status = 400;
			$_SESSION['errors'] = $this->content->getErrorBag();
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			return;
		}

		// If the content is an array, convert to JSON and send it.
		if ($this->shouldBeJson($this->content)) {
			header('Content-Type: application/json');
			echo json_encode($this->content);
			return;
		}

		// Otherwise, assume it's a string and send it directly.
		echo $this->content;
	}

	/**
	 * Determine if the response content should be JSON.
	 * If the content is an array, it indicates that it should be returned as JSON.
	 *
	 * @param mixed $content The content to check.
	 *
	 * @return bool True if the content should be returned as JSON, false otherwise.
	 */
	protected function shouldBeJson($content): bool {
		return is_array($content);
	}

	/**
	 * Set an individual HTTP header.
	 *
	 * @param string $header The name of the header to set.
	 * @param string $value The value of the header.
	 *
	 * @return self
	 */
	protected function header(string $header, string $value) {
		header("$header: $value", true, $this->status);
		return $this;
	}
}
