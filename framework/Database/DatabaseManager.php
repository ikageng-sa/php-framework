<?php

namespace Qanna\Database;

use Qanna\Config\Repository;
use Qanna\Database\Connectors\MySqlConnector;
use Qanna\Foundation\Manager;

class DatabaseManager extends Manager {

	protected string $default;

	protected array $drivers = [];

	public function __construct(Repository $config) {
		$this->config = $config;
		$this->default = $config->get('database.default', 'mysql');
	}

	public function config($driver): mixed {
		return $this->config->get("database.connections.$driver") ?? $this->config->get('database.connections');
	}

	protected function createMysqlDriver($config) {
		return new MySqlConnector($config);
	}

}