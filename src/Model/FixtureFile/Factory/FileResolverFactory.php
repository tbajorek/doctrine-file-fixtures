<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Factory;

use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\AbstractFactory;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileResolverInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class FileResolverFactory extends AbstractFactory
{
    /**
     * @return FixtureFileResolverInterface
     * @throws FixturesException
     */
    public function create(): FixtureFileResolverInterface
    {
        $resolver = $this->getObject();
        if (!($resolver instanceof FixtureFileResolverInterface)) {
            $className = $this->getClassName();
            throw new FixturesException("$className must implement FixtureFileResolverInterface interface");
        }
        return $resolver;
    }
}