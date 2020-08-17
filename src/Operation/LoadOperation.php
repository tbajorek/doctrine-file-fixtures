<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Operation;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\DependencyResolver;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityPersistorInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\SharedRepository;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FileResolverFactory;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureAdapterFactory;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;
use Tbajorek\DoctrineFileFixturesBundle\Model\LoggerInterface;

class LoadOperation implements OperationInterface
{
    private $metadataProvider;
    private $dependencyResolver;
    private $resolverFactory;
    private $adapterFactory;
    private $entityProvider;
    private $hydrator;
    private $entityPersistor;
    private $sharedRepository;

    public function __construct(
        MetadataProviderInterface $metadataProvider,
        DependencyResolver $dependencyResolver,
        FileResolverFactory $resolverFactory,
        FixtureAdapterFactory $adapterFactory,
        Provider $entityProvider,
        Hydrator $hydrator,
        EntityPersistorInterface $entityPersistor,
        SharedRepository $sharedRepository
    ) {
        $this->metadataProvider = $metadataProvider;
        $this->dependencyResolver = $dependencyResolver;
        $this->resolverFactory = $resolverFactory;
        $this->adapterFactory = $adapterFactory;
        $this->entityProvider = $entityProvider;
        $this->hydrator = $hydrator;
        $this->entityPersistor = $entityPersistor;
        $this->sharedRepository = $sharedRepository;
    }

    public function perform(LoggerInterface $logger): void
    {
        $allEntityClasses = $this->metadataProvider->getAllEntities();
        $entitiesNumber = count($allEntityClasses);
        $logger->info("Found $entitiesNumber entities");
        $entities = $this->dependencyResolver->resolveEntitiesOrder($allEntityClasses);
        $fileResolver = $this->resolverFactory->create();
        $adapter = $this->adapterFactory->create();
        foreach ($entities->getClasses() as $entityClass) {
            $filePath = $fileResolver->resolve($entityClass);
            try {
                $adapter->init($filePath, $entityClass);
            } catch(FixturesException $e) {
                $logger->info($e->getMessage());
                continue;
            }

            $entityMetadata = $this->metadataProvider->getEntityMetadata($entityClass);
            while ($fixture = $adapter->loadNextFixture()) {
                $entityObject = $this->entityProvider->getEntity($entityMetadata, $fixture);
                $entityObject = $this->hydrator->hydrate($entityMetadata, $entityObject, $fixture);
                if (!$this->entityPersistor->contains($entityObject)) {
                    $this->entityPersistor->add($entityObject);
                }
                $this->addToSharedRepository($entityMetadata, $entityObject, $fixture);
            }
            $logger->info("Entity $entityClass has been loaded from fixture file");
        }
        $adapter->close();
        $this->entityPersistor->save();
        $logger->info("Fixtures are saved to database");
    }

    /**
     * @param Entity $entityMetadata
     * @param object $entityObject
     * @param Fixture $fixture
     */
    private function addToSharedRepository(Entity $entityMetadata, object $entityObject, Fixture $fixture): void
    {
        $identifierValue = $fixture->getData()[$entityMetadata->getIdentifier()->getColumnName()] ?? null;
        if ($identifierValue !== null && $this->dependencyResolver->isInDependences($entityMetadata->getClassName())) {
            $this->sharedRepository->addEntity($identifierValue, $entityObject);
        }
    }
}