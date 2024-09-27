<?php

namespace Qanna\Support;

use JsonSerializable;
use Qanna\Database\Queryable;

/**
 * Class Model
 *
 * A base model class that implements dynamic attribute handling, hiding specific
 * fields, and the ability to convert the model instance into JSON. It also provides
 * access to querying capabilities through the IsQueryable trait.
 */
class Model implements JsonSerializable {

	use Queryable;

	/**
	 * @var array List of attributes that should be hidden from JSON serialization
	 * or when debugging the model.
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * @var array Holds the attributes of the model as an associative array where
	 * keys are attribute names and values are the attribute values.
	 */
	protected $attributes = [];

	/**
	 * Model constructor.
	 *
	 * Initializes the model with a set of attributes.
	 *
	 * @param array $attributes Associative array of model attributes (optional).
	 */
	public function __construct($attributes = []) {
		$this->attributes = $this->setAttributes($attributes);

		if ($this->table == null) {
			$this->setTable(static::tryResolvingTableName());
		}
	}

	/**
	 * Sets a single attribute for the model.
	 *
	 * @param string $key The name of the attribute to set.
	 * @param mixed $value The value to assign to the attribute.
	 * @return void
	 */
	public function setAttribute(string $key, $value) {
		$this->attributes[$key] = $value;
	}

	/**
	 * Sets the attributes for the model.
	 *
	 * Converts the provided array into the internal attributes array.
	 *
	 * @param array $attributes Associative array of attributes to set (optional).
	 * @return array The attributes that have been set.
	 */
	private function setAttributes($attributes = []) {
		$result = [];
		foreach ($attributes as $key => $value) {
			$result[$key] = $value;
		}

		return $this->attributes = $result;
	}

	/**
	 * Retrieves the value of a specific attribute.
	 *
	 * @param string $key The name of the attribute.
	 * @return mixed|null The value of the attribute or null if it doesn't exist.
	 */
	public function getAttribute(string $key) {
		return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
	}

	/**
	 * Returns all attributes of the model.
	 *
	 * @return array The model's attributes.
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * Checks if a given attribute should be hidden.
	 *
	 * @param string $name The name of the attribute.
	 * @return bool True if the attribute is in the hidden array, false otherwise.
	 */
	public function shouldBeHidden(string $name) {
		return in_array($name, $this->hidden);
	}

	/**
	 * Custom debug information.
	 *
	 * Overrides the default debug behavior to hide certain attributes. Only the
	 * visible attributes are shown during debugging.
	 *
	 * @return array The visible attributes of the model.
	 */
	public function __debugInfo() {
		$visibleAttributes = [];

		foreach ($this->attributes as $key => $value) {
			if ($this->shouldBeHidden($key)) {
				continue;
			}

			$visibleAttributes[$key] = $value;
		}

		$this->attributes = $visibleAttributes;
		$this->table = $this->getTable() ?? static::tryResolvingTableName();
	}

	/**
	 * Prepares the model for JSON serialization.
	 *
	 * Only the visible attributes are included in the serialized output.
	 *
	 * @return array The array of visible attributes.
	 */
	public function jsonSerialize(): mixed {
		$attributes = [];

		foreach ($this->attributes as $key => $value) {
			if ($this->shouldBeHidden($key)) {
				continue;
			}

			$attributes[$key] = $value;
		}

		return $attributes;
	}

	/**
	 * Magic getter for attributes.
	 *
	 * Allows access to the model's attributes dynamically using the `$model->attribute` syntax.
	 *
	 * @param string $name The name of the attribute.
	 * @return mixed|null The value of the attribute or null if it doesn't exist.
	 */
	public function __get(string $name) {
		return $this->attributes[$name] ?? null;
	}

	/**
	 * Magic setter for attributes.
	 *
	 * Allows setting the model's attributes dynamically using the `$model->attribute = value` syntax.
	 *
	 * @param string $name The name of the attribute.
	 * @param mixed $value The value to set for the attribute.
	 * @return mixed The newly set value.
	 */
	public function __set(string $name, mixed $value) {
		return $this->attributes[$name] = $value;
	}

	/**
	 * Magic static method handler for querying.
	 *
	 * Delegates static method calls to the query builder via the IsQueryable trait.
	 * This allows methods such as `User::find($id)` to be dynamically routed to the query builder.
	 *
	 * @param string $name The name of the method.
	 * @param array $arguments The arguments passed to the static method.
	 * @return mixed The result of the query or method.
	 */
	public static function __callStatic($name, $arguments) {
		return call_user_func_array([static::query(), $name], $arguments);
	}
}
