<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider\EntityFactory;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider\NotFoundException;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider\RepositoryInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Provider
{
    /**
     * @var BundleConfig
     */
    private $bundleConfig;
    /**
     * @var Provider\RepositoryInterface
     */
    private $repository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    public function __construct(
        BundleConfig $bundleConfig,
        RepositoryInterface $repository,
        EntityFactory $entityFactory
    ) {
        $this->bundleConfig = $bundleConfig;
        $this->repository = $repository;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param Entity $entityMetadata
     * @param Fixture $fixture
     * @return object
     * @throws FixturesException
     */
    public function getEntity(Entity $entityMetadata, Fixture $fixture): object
    {
        $className = $entityMetadata->getClassName();
        $strategy = $this->bundleConfig->getStrategy();
        switch ($strategy) {
            case BundleConfig::STRATEGY_INSERT:
                return $this->entityFactory->create($className);
            case BundleConfig::STRATEGY_UPSERT:
                try {
                    $idValue = $fixture->getValue($entityMetadata->getIdentifier()->getColumnName());
                    return $this->repository->getEntity($entityMetadata, $idValue);
                } catch (NotFoundException $e) {
                    return $this->entityFactory->create($className);
                }
            default:
                throw new FixturesException(sprintf('Chosen strategy %s is invalid', $strategy));
        }
    }
}