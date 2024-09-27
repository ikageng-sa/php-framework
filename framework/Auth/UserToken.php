<?php

namespace Qanna\Auth;

class UserToken implements UserTokenInterface {

	/**
	 * @var UserInterface
	 */
	private $user;

	public function __construct(UserInterface $user) {
		$this->user = $user;
	}

	public function user(): UserInterface {
		return $this->user;
	}

	public function serialize(): string {
		return serialize($this);
	}

}