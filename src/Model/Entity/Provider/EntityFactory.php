<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class EntityFactory
{
    /**
     * @param string $className
     * @return object
     * @throws FixturesException
     */
    public function create(string $className): object
    {
        $this->validateEntity($className);
        return new $className();
    }

    /**
     * @param string $className
     * @throws FixturesException
     */
    private function validateEntity(string $className): void
    {
        if (!class_exists($className)) {
            throw new FixturesException("Entity $className does not exist");
        }
        try {
            $reflectionClass = new ReflectionClass($className);
            if ($reflectionClass->hasMethod('__construct')) {
                $reflectionConstructor = new ReflectionMethod($className, '__construct');
                if ($reflectionConstructor->getNumberOfRequiredParameters() > 0) {
                    throw new FixturesException("Entity $className with paramerized constructor can not be used in fixtures");
                }
            }
        } catch (ReflectionException $e) {
            throw new FixturesException($e->getMessage());
        }

    }
}