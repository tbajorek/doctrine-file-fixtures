<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Factory;

use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\AbstractFactory;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class FixtureAdapterFactory extends AbstractFactory
{
    /**
     * @return FixtureFileInterface
     * @throws FixturesException
     */
    public function create(): FixtureFileInterface
    {
        $fileAdapter = $this->getObject();
        if (!($fileAdapter instanceof FixtureFileInterface)) {
            $className = $this->getClassName();
            throw new FixturesException("$className must implement FixtureFileInterface interface");
        }
        return $fileAdapter;
    }
}