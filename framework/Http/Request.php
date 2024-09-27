<?php

namespace Qanna\Http;

use Qanna\Session\Contracts\Session;

/**
 * Class Request
 *
 * Represents an HTTP request and provides methods to access
 * request data, including cookies, server information,
 * and form data.
 */
class Request {

	protected Session $session;

	/**
	 * @var array $cookies Associative array of cookies.
	 */
	protected readonly array $cookies;

	/**
	 * @var array $server Associative array of server variables.
	 */
	protected readonly array $server;

	/**
	 * @var array $get Associative array of get.
	 */
	protected readonly array $get;

	/**
	 * @var array $post Associative array of post.
	 */
	protected readonly array $post;

	/**
	 * @var array $files Associative array of files.
	 */
	protected readonly array $files;

	/**
	 * Constructs a new Request instance.
	 *
	 * @param array $cookies Associative array of cookies.
	 * @param array $server Associative array of server variables.
	 * @param array $get Associative array of get variables.
	 * @param array $post Associative array of post variables.
	 * @param array $files Associative array of files variables.
	 */
	public function __construct(array $cookies, array $server, array $get, array $post, array $files) {
		$this->cookies = $cookies;
		$this->server = $server;
		$this->get = $get;
		$this->post = $post;
		$this->files = $files;
	}

	/**
	 * Captures the current request from global variables.
	 *
	 * @return Request The captured request instance.
	 */
	public static function capture(): Request {
		return static::createFromGlobal();
	}

	/**
	 * Creates a Request instance from global variables.
	 *
	 * @return Request The newly created Request instance.
	 */
	private static function createFromGlobal(): Request {

		// Handle method spoofing
		if (!empty($_POST['_method'])) {
			$_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
			unset($_POST['_method']);
		}

		return new static(
			$_COOKIE,
			$_SERVER,
			$_GET,
			$_POST,
			$_FILES
		);
	}

	/**
	 * Gets the path info from the request URI.
	 *
	 * @return string The path info.
	 */
	public function pathInfo(): string {
		return strtok($this->server['REQUEST_URI'] ?? '', '?');
	}

	/**
	 * Gets the host from the request.
	 *
	 * @return string The host.
	 */
	public function host(): string {
		return $this->server['HTTP_HOST'];
	}

	/**
	 * Gets the request scheme.
	 *
	 * @return string The path info.
	 */
	public function scheme(): string {
		return $this->server['REQUEST_SCHEME'];
	}

	/**
	 * Gets the HTTP method of the request.
	 *
	 * @return string The HTTP method (e.g., GET, POST).
	 */
	public function method(): string {
		return $this->server['REQUEST_METHOD'] ?? 'UNKNOWN';
	}

	/**
	 * Gets the FormRequest instance if available.
	 *
	 * @return FormRequest|null The FormRequest instance or null.
	 */
	// public function getFormRequest(): ?FormRequest {
	// 	return $this->form;
	// }

	/**
	 * Gets the URI of the referring page.
	 *
	 * @return string|null The referring URI or null if not available.
	 */
	public function referer(): ?string {
		return parse_url($this->server['HTTP_REFERER'])['path'] ?? null;
	}

	public function setSession(Session $session) {
		$this->session = $session;
		return $this;
	}
}
