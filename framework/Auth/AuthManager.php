<?php

namespace Qanna\Auth;

use Qanna\Auth\Traits\HashesPassword;
use Qanna\Database\Query\Builder;
use Qanna\Session\Contracts\Session;

class AuthManager implements AuthManagerInterface {

	use HashesPassword;

	protected Session $session;

	protected Builder $builder;

	public function __construct(Session $session, Builder $builder) {
		$this->builder = $builder;
		$this->session = $session;
		$session->start();
	}

	public function userToken(): UserTokenInterface {
		$userToken = null;

		if ($this->hasToken()) {
			$userToken = unserialize($this->session->get(UserTokenInterface::DEFAULT_PREFIX_KEY));
		}

		return $userToken;
	}

	public function hasToken(): bool {
		return ($userToken = $this->session->get(UserTokenInterface::DEFAULT_PREFIX_KEY)) && (unserialize($userToken) !== false);
	}

	public function createUserToken(UserInterface $user): UserTokenInterface {
		$userToken = new UserToken($user);

		$this->session->push(UserTokenInterface::DEFAULT_PREFIX_KEY, $userToken->serialize());

		return $userToken;
	}

	public function user() {
		$user = null;
		if ($this->hasToken()) {
			$userToken = $this->userToken();
			$user = $userToken->user();
		}

		return $user;
	}

	public function check(): bool {
		return $this->hasToken();
	}

	public function attempt($attributes): bool {

		$validatedUser = $this->validateCredentials($attributes);

		if (!$validatedUser) {
			return false;
		}

		return $this->hasToken();
	}

	protected function validateCredentials($attributes): bool | UserInterface {
		$user = $this->builder
			->table('users')
			->select('*')
			->where('email', $attributes['email'])
			->first();

		if (!$user) {
			return false;
		}

		if ($this->passwordConfirmed($user = new GenericUser($user), $attributes['password'])) {
			$this->createUserToken($user);
			return true;
		} else {
			$this->session->flash('errors.email', 'Incorrect credentials, please try again.');
		}

		return false;
	}

	public function login($credentials) {
		return $this->attempt($credentials);
	}

	public function logout(): void {
		if ($this->hasToken()) {
			$this->session->forget(UserTokenInterface::DEFAULT_PREFIX_KEY);
		}
	}
}