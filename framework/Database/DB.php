<?php

namespace Qanna\Database;

class DB {

	public static function table(string $name) {
		$query = new Query($name);
		return $query;
	}

}
