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

    public function addDefinitions(array $newDefinitions)
    {
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$className} is not instantiable.");
        }

        $contructor = $reflectionClass->getConstructor();
        if (!$contructor) {
            return new $className();
        }

        $params = $contructor->getParameters();
        if (count($params) === 0) {
            return new $className();
        }

        $dependencies = [];
        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException("Failed to resolve class {$className} because parameter {$name} has no type hint.");
            }

            if (!($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
                throw new ContainerException("Failed to resolve class {$className} because parameter {$name} has invalid type.");
            }

            $dependencies[] = $this->getDependency($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    protected function getDependency(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerException("Class {$id} does not exist in the container.");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factoryFunction = $this->definitions[$id];
        $dependency = $factoryFunction();

        $this->resolved[$id] = $dependency;

        return $dependency;
    }
}
