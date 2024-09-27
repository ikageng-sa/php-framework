<?php

namespace Qanna\Routing;

use Qanna\Http\RedirectResponse;
use Qanna\Session\Contracts\Session;

class Redirector {

	protected UrlGenerator $generator;

	protected Session $session;

	public function __construct(UrlGenerator $generator, Session $session) {
		$this->generator = $generator;
		$this->session = $session;
	}

	/**
	 * Creates a redirect response instance
	 *
	 * @param string $uri Url to redirect to
	 * @param int $status Response status code
	 * @param array $headers Response headers
	 *
	 * @return \Qanna\Http\RedirectResponse
	 */
	protected function createResponse($url, $status = 302, array $headers = []) {
		return new RedirectResponse($url, $status, $headers, $this->getSession());
	}

	/**
	 * Returns the session instance
	 *
	 * @return \Qanna\Session\Contracts\Session | null
	 */
	protected function getSession() {
		return $this->session;
	}

	/**
	 * Sets the session instance
	 */
	public function setSession(Session $session) {
		$this->session = $session;
	}

	/**
	 * Creates a redirect response to send the client to
	 *
	 * @param string $uri Url to redirect to
	 * @param int $status Response status code
	 * @param array $headers Response headers
	 *
	 * @return \Qanna\Http\RedirectResponse
	 */
	public function to($url, $status = 302, array $headers = []) {
		return $this->createResponse(
			$url, $status, $headers
		);
	}

	/**
	 * Redirect back to the referer uri
	 *
	 * @param int $status Response status code
	 * @param array $headers Response headers
	 *
	 * @return \Qanna\Http\RedirectResponse
	 */
	public function back($status = 302, array $headers = []) {
		return $this->to($this->generator->getRequest()->referer(), 302);
	}

	/**
	 * Reload the current request
	 *
	 * @param int $status Response status code
	 * @param array $headers Response headers
	 *
	 * @return \Qanna\Http\RedirectResponse
	 */
	public function refresh($status = 302, array $headers = []) {
		return $this->to($this->generator->getRequest()->pathInfo(), $status, $headers);
	}

	/**
	 * Redirect back to the referer uri
	 *
	 * @param string $route Route name or uri
	 * @param int $status Response status code
	 * @param array $headers Response headers
	 *
	 * @return \Qanna\Http\RedirectResponse
	 */
	public function route($route, $status = 302, array $headers = []) {
		return $this->to($this->generator->route($route), $status, $headers);
	}
}