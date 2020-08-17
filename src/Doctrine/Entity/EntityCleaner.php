<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityCleanerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class EntityCleaner implements EntityCleanerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Entity[] $entitiesMetadata
     * @throws FixturesException
     */
    public function resetEntitySets(array $entitiesMetadata): void
    {
        try {
            $connection = $this->entityManager->getConnection();
            $databasePlatform = $connection->getDatabasePlatform();
            if ($databasePlatform !== null && $databasePlatform->supportsForeignKeyConstraints()) {
                $connection->query('SET FOREIGN_KEY_CHECKS=0');
            }
            foreach ($entitiesMetadata as $entityMetadata) {
                $query = $databasePlatform->getTruncateTableSQL(
                    $entityMetadata->getTable()
                );
                $connection->executeUpdate($query);
            }
            if ($databasePlatform !== null && $databasePlatform->supportsForeignKeyConstraints()) {
                $connection->query('SET FOREIGN_KEY_CHECKS=1');
            }
        } catch (DBALException $e) {
            throw new FixturesException('Error while resetting entity sets');
        }
    }
}