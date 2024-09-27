<?php

namespace Qanna\Validation\Rules;

use Qanna\Database\DB;
use Qanna\Validation\Contracts\RuleContract;

class Unique implements RuleContract {

	protected $table;

	protected $column;

	protected $attribute;

	public function __construct($table, $attribute, $column) {
		$this->table = $table;
		$this->column = $column;
		$this->attribute = $attribute;
	}

	public function passes(): bool {
		$query = DB::table($this->table)->select()->limit(1);
		$columns = $this->column;
		$attribute = $this->attribute;

		if (is_array($columns)) {
			foreach ((array) $columns as $column) {
				$query->where($column, $this->attribute);
			}
		} else {
			$query->where($columns, $attribute);
		}

		return !(bool) $query->get();
	}

	public function message(): string {
		return ':attribute already exists';
	}
}