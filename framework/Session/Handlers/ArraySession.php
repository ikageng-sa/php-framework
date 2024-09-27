<?php

namespace Qanna\Session\Handlers;

class ArraySession implements SessionInterface {

	protected bool $isOpened = false;

	protected $lifetime;

	public function __construct(array $settings = []) {
		session_cache_limiter('public');
		session_name($settings['cookie'] ?? "_session");
		session_save_path($settings['files']);
		session_set_cookie_params([
			'path' => $settings['path'] ?? '/',
			'domain' => $settings['domain'] ?? '',
			'secure' => $settings['secure'] ?? false,
			'httponly' => $settings['http_only'] ?? true,
			'samesite' => $settings['same_site'] ?? 'lax',
		]);

		$this->lifetime = (int) $settings['lifetime'];
	}

	public function regenerate(): bool {
		return session_regenerate_id(true);
	}

	public function isOpened(): bool {
		return $this->isOpened = session_status() === PHP_SESSION_ACTIVE;
	}

	public function open(): bool {

		if ($this->isOpened()) {
			return true;
		}

		session_start();

		if (!$this->has(self::SESSION_CREATED_AT)) {
			$this->write(self::SESSION_CREATED_AT, time());
		}
		if ($this->hasExpired()) {
			$this->regenerate();
			$this->write(self::SESSION_CREATED_AT, time());
		}

		return true;
	}

	public function write($key, $value = null): mixed {
		$keys = explode('.', $key);
		$session = &$_SESSION;

		foreach ($keys as $key) {
			if (!isset($session[$key])) {
				$session[$key] = [];
			}

			$session = &$session[$key];
		}
		$session = $value;
		return $value;
	}

	public function read($key, $default = null): mixed {
		$keys = explode('.', $key);
		$session = $_SESSION;

		foreach ($keys as $key) {
			if (!isset($session[$key])) {
				return $default;
			}

			$session = $session[$key];
		}

		return $session;
	}

	public function close(): bool {
		$this->isOpened = false;
		return true;
	}

	public function destroy(string $key = null) {
		if (!$key) {
			session_unset();
			return session_destroy();
		}

		$keys = explode('.', $key);
		$session = &$_SESSION;

		foreach ($keys as $i => $key) {
			if (!isset($session[$key])) {
				return;
			}

			if ($i === count($keys) - 1) {
				unset($session[$key]);
				return;
			}

			$session = &$session[$key];
		}
		return true;
	}

	public function has(string $key): bool {
		$keys = explode('.', $key);
		$session = $_SESSION;
		foreach ($keys as $key) {
			if (!isset($session[$key])) {
				return false;
			}
			$session = $session[$key];
		}

		return true;
	}

	public function hasExpired() {
		$lifetime = $this->lifetime;

		if ($this->has('_created') && (time() - $this->read('_created')) > $lifetime) {
			return true;
		}

		return false;
	}

	public function gc($lifetime): bool {
		$files = glob(session_save_path() . '/sess_*');

		$count = 0;

		foreach ($files as $file) {
			$count++;
			if (filemtime($file) + $lifetime < time()) {
				unlink($file);
			}
		}

		return $count;
	}

}