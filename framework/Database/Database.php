<?php

namespace Qanna\Database;

use PDO;
use Qanna\Foundation\App;

class Database {

	protected $driver;

	protected $username;

	protected $password;

	protected $db;

	protected $host;

	protected $port;

	protected $charset;

	protected $connection;

	public function __construct(
		protected App $app
	) {

		$default = $app['config']->get('database.default');
		$connection = $app['config']->get("database.connections.$default");

		$this->username = $connection['username'];
		$this->password = $connection['password'];
		$this->driver = $connection['driver'];
		$this->host = $connection['host'];
		$this->db = $connection['database_name'];
		$this->port = $connection['port'];
		$this->charset = $connection['charset'];

	}

	public function connect() {

		return $this->connection = new PDO(
			dsn: $this->getDsn(),
			username: $this->username,
			password: $this->password,

		);

	}

	protected function getDsn() {
		return "$this->driver:host=$this->host;port=$this->port;dbname=$this->db;charset=$this->charset";
	}

}
