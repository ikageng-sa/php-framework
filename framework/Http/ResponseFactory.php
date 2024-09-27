<?php

namespace Qanna\Http;

class ResponseFactory {

	public function make($content, $status, array $headers = []) {

		return new Response(
			content: $content,
			status: $status,
			headers: $headers
		);
	}

	public function view($content, $status) {
		$headers = [
			'Content-Type' => 'text/html',
		];
		return $this->make($content, $status, $headers);
	}
}