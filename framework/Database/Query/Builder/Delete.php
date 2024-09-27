<?php

namespace Qanna\Database\Query\Builder;

use Qanna\Database\Query\Grammar;

class Delete extends BuilderContract {

	use Filterable;

	public function __construct($connection, Grammar $grammar, $table) {

		$this->connection = $connection;
		$this->grammar = $grammar;
		$this->bindings['delete'] = true;

		if (!empty($table)) {
			$this->setTable($table);
		}

	}

	public function from(string $table) {
		$this->setTable($table);
		return $this;
	}

	public function get(): mixed {
		return $this->execute(
			query: $this->grammar->compileDelete($this->getBindings()),
			data: $this->getBindings()['data'] ?? []
		);
	}

}