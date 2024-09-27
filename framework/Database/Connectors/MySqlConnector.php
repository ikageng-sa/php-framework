<?php

namespace Qanna\Database\Connectors;

class MySqlConnector {

	protected ?string $dsn;

	protected ?string $username;

	protected ?string $password;

	protected $timezone;

	public function __construct(?array $config = []) {
		$this->dsn = $this->getDsn($config);
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->timezone = date_default_timezone_get();
	}

	protected function getDsn($config) {
		return "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database_name']};charset={$config['charset']}";
	}

	public function connection() {
		$connection = new \PDO(
			dsn: $this->dsn,
			username: $this->username,
			password: $this->password,

		);

		return $connection;
	}
}