<?php

namespace Qanna\Support\Contracts;

use Qanna\Http\RedirectResponse;
use Qanna\Http\Response;

interface ExceptionContract {

	/**
	 * Sends back as response
	 *
	 * @return \Qanna\Http\Response | Qanna\Http\RedirectResponse
	 */
	public function asResponse(): Response | RedirectResponse;
}