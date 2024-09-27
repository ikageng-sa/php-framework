<?php

namespace Qanna\Database\Query\Builder;

use Qanna\Database\Query\Grammar;

class Select extends BuilderContract {

	use Filterable;

	public function __construct($connection, Grammar $grammar, array | string $column, ?string $table = '') {
		$this->connection = $connection;
		$this->grammar = $grammar;

		if (!empty($table)) {
			$this->from($table);
		}

		if (is_string($column)) {
			$column = explode(', ', $column);
		}

		$this->bindings['select'][] = array_merge($this->bindings['select'], (array) $column);
	}

	/**
	 * Sets the table
	 *
	 * @param string $table
	 * @return self
	 */
	public function from(string $table) {
		$this->setTable($table);
		return $this;
	}

	public function distinct() {
		$this->bindings['distinct'] = true;
		return $this;
	}

	public function get(): mixed {
		return $this->execute(
			query: $this->grammar->compileSelect($this->getBindings()),
			data: $this->getBindings()['data'] ?? [],
			fetchable: true
		);
	}

}