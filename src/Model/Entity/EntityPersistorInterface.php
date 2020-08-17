<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

interface EntityPersistorInterface
{
    /**
     * Register entity object in ORM that it can be saved later in database
     *
     * @param object $entity
     */
    public function add(object $entity): void;

    /**
     * Verify if entity object is already registered in ORM to being observed and saved
     *
     * @param object $entity
     * @return bool
     */
    public function contains(object $entity): bool;

    /**
     * Save all entities registered before to database
     */
    public function save(): void;
}