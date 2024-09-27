<?php

namespace Qanna\Session\Handlers;

interface SessionInterface {

	public const SESSION_CREATED_AT = '_created';
	public const SESSION_TOKEN = '_token';
	public const SESSION_FLASH = '_flash';
	public const SESSION_OLD_INPUT = '_old';

	// public function getId(): string | false;

	public function regenerate(): bool;

	public function isOpened(): bool;

	public function open(): bool;

	public function close(): bool;

	public function write(string $key, $data = null): mixed;

	public function read(string $key, $default = null): mixed;

	public function has(string $key): bool;

	public function destroy(string $key = null);

	public function gc($lifetime): bool;
}