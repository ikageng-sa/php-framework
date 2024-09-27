<?php

namespace Qanna\Database\Query;

use PDOException;
use Qanna\Database\Query\Builder\Delete;
use Qanna\Database\Query\Builder\Insert;
use Qanna\Database\Query\Builder\Select;
use Qanna\Database\Query\Builder\Update;

class Builder {

	protected $connection;

	protected Grammar $grammar;

	protected $bindings = [
		'table' => '',
		'insert' => [],
		'data' => [],
	];

	public function __construct($connection, Grammar $grammar) {
		$this->connection = $connection;
		$this->grammar = $grammar;
	}

	protected array $allowedOperators = [
		'=', '<', '<=', '>=', '>', 'AND', 'LIKE', 'OR',
	];

	public function table(string $name) {
		$this->setTable($name);
		return $this;
	}

	public function select(array | string $column = '*') {
		return new Select($this->connection, $this->grammar, $column, $this->bindings['table']);
	}

	public function insert(array $attributes) {

		$insert = [];
		$data = [];

		foreach ($attributes as $key => $attribute) {
			if (is_array($attribute)) {
				foreach ($attribute as $subKey => $value) {
					$insert[$key][] = "{$subKey}{$key}";
					$data["{$subKey}{$key}"] = $value;
				}
			} else {
				$insert[] = array_keys($attributes);
				$data = $attributes;
				break;
			}
		}

		$this->addData('insert', $insert);
		$this->addData('data', $data);

		return $this->run();
	}

	public function delete() {
		return new Delete($this->connection, $this->grammar, $this->bindings['table']);
	}

	public function update(array $attributes) {
		return new Update($this->connection, $this->grammar, $attributes, $this->bindings['table'] ?? '');
	}

	/**
	 * Sets the table name
	 *
	 * @param string $table
	 */
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

	protected function addData(string $key, array $data) {
		$this->bindings[$key] = array_merge($this->bindings[$key], $data);
	}

	public function get() {
		return $this->run(true);
	}

	protected function run(bool $fetchable = false) {
		return $this->execute(
			query: $this->grammar->compileInsert($this->getBindings()),
			data: $this->getBindings()['data'],
			fetchable: $fetchable
		);
	}

}