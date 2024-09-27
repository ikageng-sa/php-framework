<?php

namespace Qanna\Auth;

/**
 * @package Qamma\Auth
 */
interface UserInterface {
	public function getName(): ?string;

	public function getPassword(): ?string;
}