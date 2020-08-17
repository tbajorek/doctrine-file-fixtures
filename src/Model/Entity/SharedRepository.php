<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

class SharedRepository
{
    /**
     * @var array
     */
    private $entities;

    public function __construct()
    {
        $this->entities = [];
    }

    public function addEntity($identifier, object $entity): void
    {
        $this->entities[get_class($entity) . $identifier] = $entity;
    }

    public function getEntity($identifier, string $className): ?object
    {
        return $this->entities[$className . $identifier] ?? null;
    }

    public function clear(): void
    {
        unset($this->entities);
        $this->entities = [];
    }
}