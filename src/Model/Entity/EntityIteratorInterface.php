<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

interface EntityIteratorInterface
{
    /**
     * Return next entity object if exists or null if it's the last entity
     *
     * @return object|null
     */
    public function getNextEntity(): ?object;

    /**
     * Return number of all entities saved already in database
     *
     * @return int
     */
    public function getAllResultsNumber(): int;
}