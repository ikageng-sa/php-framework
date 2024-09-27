<?php

namespace Qanna\Auth;

interface AuthManagerInterface {
	public function userToken(): UserTokenInterface;

	public function hasToken(): bool;

	public function createUserToken(UserInterface $user): UserTokenInterface;

	public function logout(): void;

	public function hashPassword(string $password): string;

	public function passwordConfirmed(UserInterface $user, string $password): bool;
}