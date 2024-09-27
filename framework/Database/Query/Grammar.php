<?php

namespace Qanna\Database\Query;

use Exception;

class Grammar {

	protected array $bindings = [];

	protected string $query = '';

	/**
	 * Compiles the select statement from bindings
	 * @param array $bindings
	 * @return string
	 */
	public function compileSelect(array $bindings) {
		$this->bindings = $bindings;
		$query = $this->select()->distinct()->columns('select')->table('FROM')->where()->limit()->orderBy()->getCompiledQuery();
		return $query;
	}

	public function compileInsert(array $bindings) {
		$this->bindings = $bindings;
		$query = $this->insert()->table('INTO')->columns('insert', '(', ')')->values()->getCompiledQuery();
		return $query;
	}

	public function compileDelete(array $bindings) {
		$this->bindings = $bindings;
		$query = $this->delete()->table('FROM')->where()->limit()->orderBy()->getCompiledQuery();
		return $query;
	}

	public function compileUpdate(array $bindings) {
		$this->bindings = $bindings;
		$query = $this->update()->table('')->set()->where()->getCompiledQuery();
		return $query;
	}

	protected function select() {
		$bindings = $this->bindings;
		if (!isset($bindings['select'])) {
			$this->query = 'SELECT *';
			return $this;
		}

		$this->query = "SELECT ";
		return $this;
	}

	protected function insert() {
		$this->query = 'INSERT ';
		return $this;
	}

	protected function update() {
		$this->query = 'UPDATE ';
		return $this;
	}

	protected function delete() {
		$this->query = 'DELETE ';
		return $this;
	}

	public function set() {
		$bindings = $this->bindings;
		$columns = $bindings['update'][0] ?? $bindings['update'];
		$set = '';

		foreach ($columns as $key) {
			$set .= "{$key} = :{$key}, ";
		}
		$set = rtrim($set, ', ');

		$this->query .= "SET {$set} ";
		return $this;
	}

	protected function columns(string $key, $open_tag = '', $close_tag = '') {
		$bindings = $this->bindings;
		$columns = '';

		if (!isset($bindings[$key])) {
			return $this;
		}
		$columns = $bindings[$key][0] ?? $bindings[$key];

		$columns = str_replace('0', '', $open_tag . implode(', ', $columns) . $close_tag);

		$this->query .= "{$columns} ";
		return $this;
	}

	protected function distinct() {
		if (!isset($this->bindings['distinct'])) {
			return $this;
		}

		$this->query .= 'DISTINCT ';
		return $this;
	}

	protected function table($concat = 'FROM') {
		$bindings = $this->bindings;
		if (!isset($bindings['table'])) {
			throw new Exception('No table specified');
		}

		$this->query .= trim("$concat {$bindings['table']}", ' ') . ' ';
		return $this;
	}

	protected function where() {
		$bindings = $this->bindings;
		if (!isset($bindings['where']) || empty($bindings['where'])) {
			return $this;
		}

		$clauses = [];

		foreach ($bindings['where'] as $column => $condition) {
			$clauses[] = "{$column} {$condition['operator']} :{$column} {$condition['connector']}";
		}

		$clause = implode(' ', $clauses);
		$clause = rtrim($clause, 'ANDOR ');
		$this->query .= "WHERE {$clause} ";
		return $this;
	}

	public function values() {
		$bindings = $this->bindings;

		if (!isset($bindings['data'])) {
			throw new Exception('No data specified');
		}

		$values = 'VALUES ';
		foreach ($bindings['insert'] as $columns) {
			$values .= '(:' . implode(', :', $columns) . '), ';
		}

		$this->query .= rtrim($values, ', ') . ' ';
		return $this;
	}

	protected function limit() {
		$bindings = $this->bindings;
		if (!isset($bindings['limit'])) {
			return $this;
		}

		$this->query .= "LIMIT {$bindings['limit']} ";
		return $this;
	}

	protected function orderBy() {
		$bindings = $this->bindings;
		if (!isset($bindings['order_by'])) {
			return $this;
		}

		$columns = implode(', ', $bindings['order_by']['columns']);
		$sequence = $bindings['order_by']['sequence'] ?? '';
		$this->query .= "ORDER BY {$columns} $sequence ";
		return $this;
	}

	protected function insertAfter($word, $content) {
		return preg_replace("/$word\s+/i", "{$word} {$content} ", $this->query, 1);
	}

	protected function getCompiledQuery() {
		return rtrim($this->query) . ';';
	}
}