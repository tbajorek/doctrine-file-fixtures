<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile;

use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

interface FixtureFileInterface
{
    /**
     * Initialize fixture file and open it
     *
     * @param string $fileName
     * @param string $className
     * @throws FixturesException
     */
    public function init(string $fileName, string $className): void;

    /**
     * Load next fixture from file and return it. If there is no more fixtures, return null.
     *
     * @return Fixture|null
     */
    public function loadNextFixture(): ?Fixture;

    /**
     * Save the file with data passed in argument
     *
     * @param Fixture[] $data
     */
    public function save(array $data): void;

    /**
     * Remain the file but make it empty
     */
    public function clean(): void;

    /**
     * Close the opened file
     */
    public function close(): void;
}