<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $definitions = [];
    private array $resolved = [];

    public function addDefinitions(array $newDefinitions): void
    {
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$className} is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $className();
        }

        $params = $constructor->getParameters();
        if (count($params) === 0) {
            return new $className();
        }

        $dependencies = [];
        foreach ($params as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType();

            if (!$paramType) {
                throw new ContainerException(
                    "Failed to resolve class {$className} because parameter {$paramName} has no type hint.",
                );
            }

            if (!($paramType instanceof ReflectionNamedType) || $paramType->isBuiltin()) {
                throw new ContainerException(
                    "Failed to resolve class {$className} because parameter {$paramName} has invalid type.",
                );
            }

            $dependencies[] = $this->getDependency($paramType->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function getDependency(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerException("Class {$id} does not exist in the container.");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factoryFunction = $this->definitions[$id];
        $dependency = $factoryFunction($this);

        $this->resolved[$id] = $dependency;

        return $dependency;
    }
}
