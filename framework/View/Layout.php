<?php

namespace Qanna\View;

class Layout {

	protected string $layout;

	protected string $path;

	public function __construct(string $path, string $layout) {
		$this->path = $path;
		$this->layout = $layout;
	}

}