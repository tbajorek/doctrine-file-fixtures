<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;

interface RepositoryInterface
{
    /**
     * Return entity of class described in metadata and given id
     *
     * @param Entity $metadata
     * @param string|int $id
     * @return object
     * @throws NotFoundException
     */
    public function getEntity(Entity $metadata, $id): object;
}