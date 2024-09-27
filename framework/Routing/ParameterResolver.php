<?php

namespace Qanna\Routing;

use Qanna\Foundation\Container;
use Qanna\Http\Request;
use Qanna\Support\Models\Model;
use Qanna\Validation\Validator;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionType;

class ParameterResolver {

	protected ReflectionMethod | ReflectionFunction $reflector;

	protected ?Container $container;

	public function __construct(object | string $classormethod, string $method = null, ?Container $container) {

		$this->container = $container;

		if (is_callable($classormethod)) {
			$this->reflector = new ReflectionFunction($classormethod);
		} else {
			$this->reflector = new ReflectionMethod($classormethod, $method);
		}

	}

	public function getResolvedParameters(Route $route) {
		$parameters = $this->reflector->getParameters();
		$routeParameters = $route->parameters();
		$resolvedParameters = [];

		if (!empty($parameters)) {
			foreach ($parameters as $parameter) {
				$name = $parameter->getName();
				$type = $parameter->getType();

				if (!$this->canAttemptAutoResolving($parameter->getType())) {
					$resolvedParameters[$name] = $routeParameters[$name];
					continue;
				}

				if ($this->isRequest($requestName = $type->getName())) {

					$formRequest = $this->validateRequest($requestName);
					$resolvedParameters[$name] = $formRequest;

				} else {
					$resolvedParameters[$name] = $this->attemptAutoResolving($type, $routeParameters[$name]);
				}
			}
		}

		return $resolvedParameters;
	}

	protected function getValidator(Request $request) {
		return new Validator($request);
	}

	protected function validateRequest($requestName) {
		$sharedRequest = $this->container->make('request');
		$request = new $requestName($sharedRequest);
		if ($request->method() == 'GET') {
			return $request;
		}
		$session = $this->container->make('session');
		$request->setSession($session->driver())
			->validateInput();

		return $request;
	}

	protected function canAttemptAutoResolving(?ReflectionType $type) {
		return ($type instanceof ReflectionType) ? !$type->isBuiltIn() : null;
	}

	protected function isRequest($class) {
		return key_exists(Request::class, class_parents($class));
	}

	protected function attemptAutoResolving(ReflectionType $type, $value) {
		$type = $type->getName();
		if (class_exists($type) && ($model = new $type()) instanceof Model) {
			$model = $model::find($value);
			return ($model->getAttributes() !== null) ? $model : null;
		}

		return $value;
	}
}