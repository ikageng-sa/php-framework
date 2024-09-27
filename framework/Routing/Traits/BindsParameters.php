<?php

namespace Qanna\Routing\Traits;

trait BindsParameters {

	use ResolvesUri;

	public function resolveParams($uri, $routeUri) {
		$result = $this->extractParts($this->splitRoute($routeUri), $this->splitRoute($uri));
		return $result['params'];
	}
}