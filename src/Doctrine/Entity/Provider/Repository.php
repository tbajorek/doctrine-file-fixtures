<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider\NotFoundException;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Provider\RepositoryInterface;

class Repository implements RepositoryInterface
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
     * @param Entity $metadata
     * @param string|int $id
     * @return object
     * @throws NotFoundException
     */
    public function getEntity(Entity $metadata, $id): object
    {
        $className = $metadata->getClassName();
        $result = $this->entityManager->find($className, $id);
        if ($result === null) {
            throw new NotFoundException(sprintf('Entity %s with id %s not found', $className, $id));
        }
        return $result;
    }
}