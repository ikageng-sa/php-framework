<?php

return [
	'driver' => 'array',

	'cookie' => 'template_sess',

	'lifetime' => 120,

	'path' => '/',

	'domain' => '',

	'files' => storage_path('framework/sessions'),

	'secure' => false,

	'expire_on_close' => false,

	'http_only' => true,

	'same_site' => 'lax',
];