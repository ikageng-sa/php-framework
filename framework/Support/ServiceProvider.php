<?php

namespace Qanna\Support;

use Exception;

/**
 * Class ServiceProvider
 *
 * Provides a base service provider for registering and booting services within
 * the application. This class is intended to be extended by other service providers
 * to register services, bind implementations, and handle events.
 * @var \Qanna\Foundation\App The application instance.
 */
class ServiceProvider {

	/**
	 * ServiceProvider constructor.
	 *
	 * @param \Qanna\Foundation\App $app The application instance to bind to the provider.
	 */
	public function __construct(
		protected \Qanna\Foundation\App $app
	) {}

	/**
	 * Method to register any application services.
	 *
	 * This method is intended to be overridden by subclasses to register
	 * bindings or services with the application's service container.
	 *
	 * @return void
	 */
	public function register(): void {
		//
	}

	/**
	 * Method to bootstrap any application services.
	 *
	 * This method is intended to be overridden by subclasses to perform any
	 * initialization or setup required for the service provider.
	 *
	 * @return void
	 */
	public function boot(): void {
		//
	}

	/**
	 * Registers an event in the application's event container if it does not exist.
	 *
	 * @param string $event The name of the event to register.
	 * @return void
	 */
	protected function event($event): void {
		if (isset($this->app->events[$event])) {
			return;
		}

		$this->app['events']->set($event);
	}

	/**
	 * Registers a listener for a specified event.
	 *
	 * @param string $event The name of the event to attach the listener to.
	 * @param mixed $listener The listener to be attached to the event.
	 * @return void
	 */
	protected function listener($event, $listener): void {
		$this->app->make('config')->set($event, $listener);
	}

	/**
	 * Merges configuration from a file into the application's configuration.
	 *
	 * Loads configuration from the specified file path and merges it into
	 * the application's configuration under the specified key.
	 *
	 * @param string $path The path to the configuration file.
	 * @param string $key The key under which to merge the configuration.
	 * @return void
	 * @throws Exception If the configuration file does not exist.
	 */
	protected function mergeConfigFrom($path, $key) {
		if (!file_exists($path)) {
			throw new Exception("File for key $key does not exist");
		}

		$config = require $path;
		$this->app['config']->set($key, $config);
	}

	protected function loadViewsFrom(string $path, string $namespace) {
		$this->app['view.registrar']->set($namespace, $path);
	}
}
