<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

interface EntityCleanerInterface
{
    /**
     * Clean all entities for provided metadata classes; tables are truncated in database
     *
     * @param Entity[] $entitiesMetadata
     * @throws FixturesException
     */
    public function resetEntitySets(array $entitiesMetadata): void;
}