<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator;

use ReflectionProperty;

class NonAccessibleFieldHelper
{
    public function setNonAccessibleField(object $entity, string $className, string $field, $value): void
    {
        $reflectionProperty = new ReflectionProperty($className, $field);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, $value);
    }

    public function getNonAccessibleField(object $entity, string $className, string $field)
    {
        $reflectionProperty = new ReflectionProperty($className, $field);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($entity);
    }
}