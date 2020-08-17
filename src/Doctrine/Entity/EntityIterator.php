<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\EntityIteratorInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Iterator\Paginator;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class EntityIterator implements EntityIteratorInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var array
     */
    private $resultsCache;

    /**
     * EntityIterator constructor.
     * @param string $className
     * @param BundleConfig $bundleConfig
     * @param EntityManagerInterface $entityManager
     * @throws FixturesException
     */
    public function __construct(string $className, BundleConfig $bundleConfig, EntityManagerInterface $entityManager)
    {
        $this->className = $className;
        $this->paginator = new Paginator($bundleConfig->getEntityPageSize(), $this->getAllResultsNumber());
        $this->entityManager = $entityManager;
        $this->resultsCache = [];
    }

    public function getNextEntity(): ?object
    {
        if ($this->paginator->isEndOfSet()) {
            return null;
        }
        if ($this->paginator->isEndOfPage()) {
            $this->resultsCache = $this->getNewBatch();
            $this->paginator->nextPosition();
        }
        return $this->resultsCache[$this->paginator->getPosition()];
    }

    /**
     * @return int
     * @throws FixturesException
     */
    public function getAllResultsNumber(): int
    {
        $queryBuilder = $this->prepareBasicQueryBuilder();
        try {
            return $queryBuilder->select('count(e.*)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (UnexpectedResultException $e) {
            throw new FixturesException("Number of data for entity $this->className can not be counted");
        }
    }

    /**
     * @return array
     */
    public function getNewBatch(): array
    {
        return $this->prepareBasicQueryBuilder()
            ->select('e.*')
            ->setFirstResult($this->paginator->getPageNumber() * $this->getPageSize())
            ->setMaxResults($this->paginator->getPageSize())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    private function prepareBasicQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->from($this->className, 'e');
    }
}