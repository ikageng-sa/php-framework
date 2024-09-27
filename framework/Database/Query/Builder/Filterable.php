<?php

namespace Qanna\Database\Query\Builder;

trait Filterable {

	/**
	 * Add a filter clause to the query
	 *
	 * @param string $column
	 * @param string $operator Sets an operator
	 * @param string | null $value
	 * @param string $connector
	 * @return self
	 */
	public function where(string $column, $operator = '=', $value = null, $connector = 'AND') {

		$bindings = [
			'operator' => ($this->isOperator($operator)) ? $operator : '=',
			'connector' => strtoupper($connector),
		];

		$this->bindings['where'][$column] = $bindings;
		$this->addData([$column => ($this->isOperator($operator)) ? $value : $operator]);

		return $this;
	}

	/**
	 * Add a filter clause to the query
	 *
	 * @param string $column
	 * @param string $operator Sets an operator
	 * @param string | null $value
	 * @param string $connector
	 * @return self
	 */
	public function orWhere(string $column, $operator = '=', $value = null) {
		$this->where($column, $operator, $value, connector: 'or');
		return $this;
	}

	/**
	 * Add a order cluase to the query
	 *
	 * @param array | string $column
	 * @param string | null $c optional('ASC', 'DESC')
	 * @return self
	 */
	public function orderBy(array | string $column, string | null $sequence = null) {

		if (is_string($column)) {
			$column = explode(', ', $column);
		}

		$bindings['columns'] = $column;
		if (isset($sequence)) {
			$bindings['sequence'] = $sequence;
		}
		$this->bindings['order_by'] = $bindings;
		return $this;
	}

	/**
	 * Adds a limit to the number of rows clause to the query
	 *
	 * @param int $number
	 * @return self
	 */
	public function limit(int $number) {
		$this->bindings['limit'] = $number;
		return $this;
	}

	/**
	 * Returns the first record if found
	 */
	public function first() {
		$this->limit(1);
		return $this->get();
	}

}