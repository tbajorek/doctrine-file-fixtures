<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Iterator;

use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityIteratorInterface;

interface FactoryInterface
{
    /**
     * Return entity iterator object
     *
     * @param string $className
     * @return EntityIteratorInterface
     */
    public function create(string $className): EntityIteratorInterface;
}