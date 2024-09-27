<?php

use Qanna\Foundation\Http\Kernel;
use Qanna\Http\Request;

require '../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
	Kernel::class
);

$response = $kernel->handle(
	Request::capture()
)->send();
