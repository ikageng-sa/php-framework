<?php

namespace Qanna\Database;

use Qanna\Database\Query\Builder;

class Query {

	protected string $table;

	protected static Builder $builder;

	public function __construct(string $table) {
		$this->table = $table;
	}

	public static function setQueryBuilder($builder) {
		static::$builder = $builder;
	}

	public function getQueryBuilder() {
		return static::$builder;
	}

	public function select(array $columns = ['*']) {
		return static::$builder->table($this->table)->select($columns);
	}

	public function create(array $attributes) {
		return static::$builder
			->table($this->table)
			->insert($attributes);
	}

	public function createMany(array $attributes) {
		return static::$builder
			->table($this->table)
			->insert($attributes);
	}

	public function update(int | string $identifier, array $attributes) {
		$query = static::$builder
			->table($this->table)
			->update($attributes)
			->where('id', $identifier)
			->get();
	}

	public function delete($id) {
		return static::$builder
			->table($this->table)
			->delete()
			->where('id', $id)
			->get();
	}

}