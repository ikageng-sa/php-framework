<?php

namespace Qanna\Database\Query\Builder;

use Qanna\Database\Query\Grammar;

class Update extends BuilderContract {

	use Filterable;

	public function __construct($connection, Grammar $grammar, array $attributes, $table) {

		$this->connection = $connection;
		$this->grammar = $grammar;

		if (!empty($table)) {
			$this->setTable($table);
		}

		$update = [];

		$update[] = array_keys($attributes);

		$this->addData($attributes);
		$this->bindings['update'] = $update;

	}

	public function table(string $name) {
		$this->setTable($name);
		return $this;
	}

	public function get(): mixed {
		return $this->execute(
			query: $this->grammar->compileUpdate($this->getBindings()),
			data: $this->getBindings()['data'] ?? []
		);
	}

}