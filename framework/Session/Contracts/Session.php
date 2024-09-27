<?php

namespace Qanna\Session\Contracts;

interface Session {

	public function start(): bool;

	public function regenerate(): bool;

	public function has(string $key): bool;

	public function push(string $key, $value = null): mixed;

	public function get(string $key, $default = null): mixed;

	public function forget(string $key);

	public function flash($key, $value): mixed;

	public function getFlash($key): mixed;

	public function reflash();

	public function setOld($key, $value): mixed;

	public function getOld($key): mixed;

	public function flush(): mixed;

}