<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile;

interface FixtureFileResolverInterface
{
    /**
     * Return path to file where fixtures for the given entity are stored
     *
     * @param string $className
     * @return string
     */
    public function resolve(string $className): string;
}