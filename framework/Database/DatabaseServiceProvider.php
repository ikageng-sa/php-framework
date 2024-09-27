<?php

namespace Qanna\Database;

use Qanna\Database\Query\Builder;
use Qanna\Database\Query\Grammar;
use Qanna\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider {

	public function register(): void {
		$app = $this->app;
		$this->mergeConfigFrom(
			config_path('database.php'),
			'database'
		);
		$this->registerDatabaseManager($app);
		$this->registerDatabaseDriver($app);
		$this->registerDatabaseConnection($app);
		$this->registerQueryBuilder($app);
		Query::setQueryBuilder($this->app[Builder::class]);

	}

	protected function registerDatabaseManager($app) {
		$app->singleton('db', function ($app) {
			$config = $app['config'];
			return new DatabaseManager($config);
		});
	}

	protected function registerDatabaseDriver($app) {
		$app->singleton('db.driver', function ($app) {
			return $app['db']->driver();
		});
	}

	protected function registerDatabaseConnection($app) {
		$app->singleton('db.connection', function ($app) {
			return $app['db.driver']->connection();
		});
	}

	protected function registerQueryBuilder($app) {
		$app->singleton(Builder::class, function ($app) {
			return new Builder($app['db.connection'], new Grammar());
		});
	}
}