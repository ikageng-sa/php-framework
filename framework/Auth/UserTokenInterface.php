<?php

namespace Qanna\Auth;

/**
 * @package Qamma\Auth\Token
 */
interface UserTokenInterface {

	const DEFAULT_PREFIX_KEY = '_token';

	public function user(): UserInterface;

	public function serialize(): string;
}