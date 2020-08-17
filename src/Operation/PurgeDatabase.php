<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Operation;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityCleanerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;
use Tbajorek\DoctrineFileFixturesBundle\Model\LoggerInterface;

class PurgeDatabase implements OperationInterface
{
    /**
     * @var EntityCleanerInterface
     */
    private $entityCleaner;
    /**
     * @var MetadataProviderInterface
     */
    private $metadataProvider;

    public function __construct(EntityCleanerInterface $entityCleaner, MetadataProviderInterface $metadataProvider)
    {
        $this->entityCleaner = $entityCleaner;
        $this->metadataProvider = $metadataProvider;
    }

    public function perform(LoggerInterface $logger): void
    {
        $allEntityClasses = $this->metadataProvider->getAllEntities();
        try {
            $logger->info('Cleaning process has been started');
            $this->entityCleaner->resetEntitySets($allEntityClasses);
            $logger->info('Cleaning process is finished');
        } catch (FixturesException $e) {
            $logger->error($e->getMessage());
        }
    }
}