<?php

namespace Qanna\Auth\Traits;

use Qanna\Auth\UserInterface;

trait HashesPassword {
	private $cost = 10;

	public function hashPassword(string $password): string {
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => $this->cost,
		]);
	}

	public function passwordConfirmed(UserInterface $user, string $password): bool {
		return password_verify($password, $user->getPassword());
	}

	public function setCost(int $cost) {
		if ($cost < 4 || $cost > 12) {
			throw new \InvalidArgumentException('Cost must be in the reange of 4-31');
		}

		$this->cost = $cost;
	}
}