<?php

namespace Qanna\Database;

use Qanna\Database\Query;

/**
 * Trait IsQueryable
 *
 * Provides querying capabilities to the models that use this trait. This includes
 * methods for interacting with the database, such as finding, creating, updating,
 * and deleting records.
 */
trait Queryable {

	/**
	 * @var string The name of the table associated with the model.
	 */
	protected $table;

	/**
	 * Sets the table name for the model.
	 *
	 * @param string $name The name of the table to be set.
	 * @return void
	 */
	protected function setTable($name) {
		$this->table = $name;
	}

	/**
	 * Retrieves the table name for the model.
	 *
	 * @return string The name of the table.
	 */
	protected function getTable() {
		return $this->table;
	}

	/**
	 * Attempts to resolve the table name automatically based on the class name.
	 *
	 * Converts the class name into snake_case and appends 's' to form the table name.
	 *
	 * Example: App\Models\User -> users
	 *
	 * @return string The resolved table name.
	 */
	private static function tryResolvingTableName() {
		$className = static::class;
		$baseClassName = basename(str_replace('\\', '/', $className));

		// Converts PascalCase to snake_case and appends 's'
		$tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $baseClassName));
		$tableName .= 's';

		return $tableName;
	}

	/**
	 * Returns a new query builder instance for the model's table.
	 *
	 * If the table name is not manually set, it attempts to resolve it automatically.
	 *
	 * @return Query A new instance of the Query class for the model's table.
	 */
	protected static function query() {
		return new Query((new static())->getTable() ?? static::tryResolvingTableName());
	}

	/**
	 * Finds a record by its primary key (ID).
	 *
	 * @param int $id The ID of the record to find.
	 * @return static An instance of the model populated with the found data.
	 */
	public static function find($id) {
		$model = static::query()
			->getQueryBuilder()
			->select('*')
			->from(static::tryResolvingTableName())
			->where('id', $id)
			->get();

		if ($model) {
			$model = new static($model);
		}

		return $model;
	}

	/**
	 * Updates the current model instance with new attributes.
	 *
	 * @param array $attributes The array of attributes to update.
	 * @return bool True on successful update, false otherwise.
	 */
	public function update(array $attributes) {
		return static::query()->update($this->id, $attributes);
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @param array $attributes The attributes for the new record.
	 * @return mixed The result of the creation process (usually the created model).
	 */
	public static function create(array $attributes) {
		return static::query()->create($attributes);
	}

	/**
	 * Creates multiple records in the database.
	 *
	 * @param array $attributes An array of attributes, where each element represents a new record.
	 * @return mixed The last created record, or the result of the creation.
	 */
	public static function createMany(array $attributes) {
		static::query()->createMany($attributes);
	}

	/**
	 * Saves the current model instance by updating its attributes in the database.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function save() {
		if ($this->id) {
			return $this->update($this->attributes);
		}

		return $this->query()->create($this->attributes);
	}

	/**
	 * Deletes the current model instance from the database.
	 *
	 * @return bool True on successful deletion, false otherwise.
	 */
	public function delete() {
		if ($this->id) {
			return static::query()->delete($this->id);
		}

		return false;
	}

	/**
	 * Retrieves all records from the model's table.
	 *
	 * @return array|null An array of model instances, or null if no records are found.
	 */
	public static function all() {
		$results = static::query()
			->getQueryBuilder()
			->select('*')
			->from(static::tryResolvingTableName())
			->get();

		return static::collection($results, static::class);
	}

	/**
	 * Converts query results into a collection of model instances.
	 *
	 * @param array $results The query results as an array of attributes.
	 * @param string $className The class name of the model to instantiate.
	 * @return array An array of model instances.
	 */
	protected static function collection($results, $className) {
		$collection = [];

		foreach ($results as $attributes) {
			$collection[] = (new $className($attributes));
		}

		return $collection;
	}
}
