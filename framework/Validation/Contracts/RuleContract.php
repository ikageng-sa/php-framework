<?php

namespace Qanna\Validation\Contracts;

interface RuleContract {
	public function passes(): bool;

	public function message(): string;
}