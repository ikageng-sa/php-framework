<?php

namespace App\Models;

use Qanna\Support\Model;

class User extends Model {

	protected $hidden = [
		'password',
	];

}