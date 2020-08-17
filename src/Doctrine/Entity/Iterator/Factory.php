<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity\Iterator;

use Doctrine\ORM\EntityManagerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity\EntityIterator;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityIteratorInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Iterator\FactoryInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Factory implements FactoryInterface
{
    /**
     * @var BundleConfig
     */
    private $bundleConfig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Factory constructor.
     * @param BundleConfig $bundleConfig
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(BundleConfig $bundleConfig, EntityManagerInterface $entityManager)
    {
        $this->bundleConfig = $bundleConfig;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $className
     * @return EntityIteratorInterface
     * @throws FixturesException
     */
    public function create(string $className): EntityIteratorInterface
    {
        return new EntityIterator($className, $this->bundleConfig, $this->entityManager);
    }
}