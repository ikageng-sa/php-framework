<?php

namespace Qanna\View;

use Exception;
use Qanna\Foundation\Container;

class View {

	protected const EXT = '.template.php';

	protected Container $container;

	protected string $path;

	protected ?string $namespace;

	protected string $layout;

	protected string $view;

	protected array $data = [];

	public function __construct(Container $container) {
		$this->container = $container;
		$config = $container['config']->get('view');
		$this->path = $config['path'];
		$this->layout = $config['path'] . str_replace('.', DIRECTORY_SEPARATOR, $config['layout']) . self::EXT;
	}

	public function make(string $view, $data = []) {

		$this->namespace = $this->parseNamespace($view);
		$view = $this->parseView($view);
		$this->data = $data;

		$this->path = $this->viewRegistrar()->get($this->namespace, null) ?? $this->path;

		if (!file_exists($this->getViewPath($this->path, $view))) {
			throw new Exception("View $view could not be found");
		}

		return $this;
	}

	public function extends ($layout) {
		if (!file_exists($this->layout = $this->path . str_replace('.', DIRECTORY_SEPARATOR, $layout) . self::EXT)) {
			throw new Exception("Layout $layout could not be found");
		}
		return $this;
	}

	public function with(array $data) {
		$this->data = $data;
		return $this;
	}

	protected function parseNamespace($view) {
		return ($parsedNameSpace = strstr($view, '::', true)) ? $parsedNameSpace : 'default';

	}

	protected function parseView($view) {
		$parsedView = strstr($view, '::');

		if (!$parsedView) {
			$parsedView = $view;
		}

		$parsedView = str_replace('.', DIRECTORY_SEPARATOR, $parsedView);

		return trim($parsedView, '::');
	}

	protected function getViewPath($path, $view, $ext = self::EXT) {
		return $this->view = $path . $view . $ext;
	}

	protected function viewRegistrar() {
		return $this->container->make('view.registrar');
	}

	/**
	 * Renders the view and outputs the HTML content.
	 *
	 * If raw mode is enabled, it will output HTML for debugging purposes.
	 */
	public function render() {
		$content = $this->viewCompiler(
			$this->layout, $this->view, $this->data
		)->compileView()->get();

		echo $content;
	}

	protected function viewCompiler($layout, $view, $data) {
		return new ViewCompiler($layout, $view, $data);
	}

}