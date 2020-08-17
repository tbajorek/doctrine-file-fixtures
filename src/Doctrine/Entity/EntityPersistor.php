<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityPersistorInterface;

class EntityPersistor implements EntityPersistorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function contains(object $entity): bool
    {
        return $this->entityManager->contains($entity);
    }

    public function save(): void
    {
        $this->entityManager->flush();
    }
}