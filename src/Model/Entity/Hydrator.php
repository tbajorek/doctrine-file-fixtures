<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\FieldSetter;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Hydrator
{
    /**
     * @var FieldNameResolver
     */
    private $fieldNameResolver;
    /**
     * @var FieldSetter
     */
    private $fieldSetter;

    /**
     * Hydrator constructor.
     * @param FieldNameResolver $fieldNameResolver
     * @param FieldSetter $fieldSetter
     */
    public function __construct(FieldNameResolver $fieldNameResolver, FieldSetter $fieldSetter)
    {
        $this->fieldNameResolver = $fieldNameResolver;
        $this->fieldSetter = $fieldSetter;
    }

    /**
     * @param Entity $metadata
     * @param object $entity
     * @param Fixture $fixture
     * @return object
     * @throws FixturesException
     */
    public function hydrate(Entity $metadata, object $entity, Fixture $fixture): object
    {
        $data = $fixture->getData();
        foreach ($metadata->getAllFields() as $field) {
            $fileColumnName = $this->fieldNameResolver->resolveFileColumn($field);
            $this->fieldSetter->setField($entity, $metadata, $field, $fileColumnName, $data);
        }
        return $entity;
    }

    /**
     * @param Entity $metadata
     * @param object $entity
     * @return Fixture
     * @throws FixturesException
     */
    public function dehydrate(Entity $metadata, object $entity): Fixture
    {
        $data = [];
        foreach ($metadata->getAllFields() as $field) {
            $methodName = $this->getGetterName($field->getFieldName());
            $fileColumnName = $this->fieldNameResolver->resolveFileColumn($field);
            $data[$fileColumnName] = $entity->$methodName();
        }
        return new Fixture($data);
    }

    public function getGetterName(string $fieldName): string
    {
        return 'get' . ucfirst($fieldName);
    }
}