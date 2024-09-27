<?php

namespace Qanna\View;

class ViewCompiler {

	protected string $layout;

	protected string $view;

	protected string $content;

	protected array $data = [];

	public function __construct(string $layout, string $view, array $data = []) {
		$this->layout = $layout;
		$this->view = $view;
		$this->data = $data;
	}

	public function compileView() {
		extract($this->data);
		$content = $this->getView();

		if (!str_contains($content, '<html')) {
			$content = $this->yield($this->getLayout(), $content);
		}

		ob_start();
		eval('?>' . $content);
		$this->content = ob_get_clean();

		return $this;
	}

	public function get() {
		return $this->content;
	}

	protected function getLayout() {
		return file_get_contents($this->layout);
	}

	protected function getView() {
		return file_get_contents($this->view);
	}

	public function yield ($layout, $view) {
		return str_replace("@yield", $view, $layout);
	}
}