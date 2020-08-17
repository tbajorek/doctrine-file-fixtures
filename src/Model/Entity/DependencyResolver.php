<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Data\ClassSet;
use Tbajorek\DoctrineFileFixturesBundle\OrderResolver\CycleException;

class DependencyResolver
{
    /**
     * @var MetadataProviderInterface
     */
    private $metadataProvider;

    /**
     * @var string[]|null
     */
    private $dependencies;

    public function __construct(MetadataProviderInterface $metadataProvider)
    {
        $this->metadataProvider = $metadataProvider;
    }

    /**
     * @param Entity[] $entities
     * @return ClassSet
     * @throws CycleException
     */
    public function resolveEntitiesOrder(array $entities): ClassSet
    {
        $resolvedClasses = new ClassSet();
        foreach ($entities as $entity) {
            $resolvedClasses = $this->resolveEntity($entity, $resolvedClasses, new ClassSet());
        }
        return $resolvedClasses;
    }

    public function isInDependences(string $className): bool
    {
        if ($this->dependencies === null) {
            $this->dependencies = [];
            foreach ($this->metadataProvider->getAllEntities() as $entity) {
                $this->dependencies = array_unique(array_merge($this->dependencies, array_keys($entity->getMappings())));
            }
        }
        return in_array($className, $this->dependencies, true);
    }

    /**
     * @param Entity $entity
     * @param ClassSet $resolvedClasses
     * @param ClassSet $stack
     * @return ClassSet
     * @throws CycleException
     */
    private function resolveEntity(Entity $entity, ClassSet $resolvedClasses, ClassSet $stack): ClassSet
    {
        if ($stack->hasClass($entity->getClassName())) {
            throw new CycleException($entity->getClassName(), $stack);
        }
        if ($resolvedClasses->hasClass($entity->getClassName())) {
            return $resolvedClasses;
        }
        if ($entity->getDependencies() === []) {
            return $resolvedClasses->addClass($entity->getClassName());
        }
        $stack->addClass($entity->getClassName());
        foreach ($entity->getDependencies() as $dependencyClassName) {
            $dependency = $this->metadataProvider->getEntityMetadata($dependencyClassName);
            $resolvedClasses = $this->resolveEntity(
                $dependency,
                $resolvedClasses,
                $stack
            );
            $resolvedClasses->addClass($entity->getClassName());
        }
        $stack->removeClass($entity->getClassName());
        return $resolvedClasses;
    }
}