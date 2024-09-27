<?php

namespace Qanna\Database\Query\Builder;

use PDOException;
use Qanna\Database\Query\Grammar;

abstract class BuilderContract {

	protected $connection;

	protected Grammar $grammar;

	protected array $bindings = [
		'table' => '',
		'select' => [],
		'delete' => '',
		'update' => [],
		'where' => [],
		'insert' => [],
		'values' => [],
		'data' => [],
		'joins' => [
			'inner' => [],
			'outer' => [],
			'left' => [],
			'right' => [],
			'full' => [],
		],
		'order_by' => [],
		'limit' => '',
		'distinct' => '',
	];

	protected array $allowedOperators = [
		'=', '<', '<=', '>=', '>', 'AND', 'LIKE', 'OR', 'IN', 'BETWEEN', 'EXISTS', 'NOT EXISTS', 'IS NULL',
	];

	protected function addData(array $data) {
		$this->bindings['data'] = array_merge($this->bindings['data'], $data);
	}

	protected function setTable($table) {
		$this->bindings['table'] = $table;
	}

	protected function getBindings() {
		$bindings = [];
		foreach ($this->bindings as $binding => $values) {

			if ($binding === 'joins') {
				foreach ($values as $join => $joinValues) {
					if (!empty($this->bindings['joins'][$join])) {
						$bindings['joins'][$join] = $joinValues;
					}
				}
				continue;
			}

			if (!empty($this->bindings[$binding])) {
				$bindings[$binding] = $values;
			}
		}

		return $bindings;
	}

	/**
	 * Determines if a value is an operator
	 * @return bool
	 */
	protected function isOperator($operator) {
		return in_array($operator, $this->allowedOperators);
	}

	/**
	 * Executes a query
	 *
	 * @param mixed $query
	 * @param array $data
	 * @param bool $fetchable
	 *
	 * @return mixed
	 */
	protected function execute($query, $data = [], bool $fetchable = false): mixed {

		try {
			$statement = $this->connection->prepare($query);
			$result = $statement->execute((is_int(array_key_first($data))) ? array_merge(...$data) : $data);
		} catch (PDOException $e) {
			echo $e->getMessage();
			die;
		}

		if ($fetchable) {
			$result = $statement->fetchAll(\PDO::FETCH_ASSOC);
			$result = (count($result) === 1 && is_array($result)) ? $result[0] : $result;
		}

		return $result;
	}

	/**
	 * Execute the query and returns the results
	 * @return mixed;
	 */
	public abstract function get(): mixed;

}