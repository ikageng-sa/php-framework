<?php

use Qanna\Database\Database;
use Qanna\Foundation\Configurations\App;
use Qanna\Foundation\Http\Kernel;

require '../framework/functions.php';

$app = App::configure(
	path: __DIR__ . '/../'
)
	->withRouting(routesPath: "routes.php")
	->create();

$app->bind(
	Kernel::class,
	fn() => (new Kernel($app))
);

$app->bind(Database::class,
	fn() => (new Database($app))
);

return $app;