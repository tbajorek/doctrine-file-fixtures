<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;

interface MetadataProviderInterface
{
    /**
     * Return array of class names for all available entities
     *
     * @return array
     */
    public function getAllClassNames(): array;

    /**
     * Return array of entity metadata classes for all available entities
     *
     * @return Entity[]
     */
    public function getAllEntities(): array;

    /**
     * Create entity metadata for the given class name
     *
     * @param string $className
     * @return Entity
     */
    public function getEntityMetadata(string $className): Entity;
}