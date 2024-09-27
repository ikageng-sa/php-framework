<?php

namespace Qanna\Session;

use Qanna\Session\Contracts\Session;
use Qanna\Session\Handlers\SessionInterface;

class Store implements Session {

	protected SessionInterface $handler;

	protected bool $reflash = false;

	protected $skippable = [
		'password',
	];

	public function __construct(SessionInterface $handler) {
		$this->handler = $handler;
	}

	public function start(): bool {
		return $this->handler->open();
	}

	public function regenerate(): bool {
		return $this->handler->regenerate();
	}

	public function has(string $key): bool {
		return $this->handler->has($key);
	}

	public function push(string $key, $value = null): mixed {
		return $this->handler->write($key, $value);
	}

	public function get(string $key, $default = null): mixed {
		return $this->handler->read($key, $default);
	}

	public function forget(string $key) {
		return $this->handler->destroy($key);
	}

	public function flash($key, $value): mixed {
		$key = SessionInterface::SESSION_FLASH . ".$key";
		return $this->push($key, $value);
	}

	public function getFlash($key): mixed {
		$key = SessionInterface::SESSION_FLASH . ".$key";

		$value = $this->get($key, null);

		if ($value !== null) {
			$this->forget($key);
		}

		return $value;
	}

	public function reflash() {
		$this->reflash = true;
	}

	public function setOld($key, $value): mixed {
		$key = SessionInterface::SESSION_OLD_INPUT . ".$key";
		return $this->push($key, $value);
	}

	public function getOld($key): mixed {
		$key = SessionInterface::SESSION_OLD_INPUT . ".$key";

		$value = $this->get($key, null);

		if ($value && !$this->reflash) {
			$this->forget($key);
		}

		return $value;
	}

	public function flush(): mixed {
		return $this->handler->destroy();
	}

}