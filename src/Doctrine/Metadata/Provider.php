<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Doctrine\Metadata;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\ORMException;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Field;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Identifier;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Mapping;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Provider implements MetadataProviderInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Entity[] */
    private $entities;

    /**
     * MetadataProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = [];
    }

    /**
     * Return array of class names for all available entities
     *
     * @return array
     */
    public function getAllClassNames(): array
    {
        try {
            $metadataDriverImplementation = $this->entityManager->getConfiguration()->getMetadataDriverImpl();
            if ($metadataDriverImplementation !== null) {
                return $metadataDriverImplementation->getAllClassNames();
            }
            return [];
        } catch (ORMException $e) {
            return [];
        }
    }

    /**
     * Return array of entity metadata classes for all available entities
     *
     * @return Entity[]
     * @throws FixturesException
     * @throws MappingException
     */
    public function getAllEntities(): array
    {
        $entities = [];
        foreach ($this->getAllClassNames() as $className) {
            $entities[] = $this->getEntityMetadata($className);
        }
        return $entities;
    }

    /**
     * Create entity metadata for the given class name
     *
     * @param string $className
     * @return Entity
     * @throws FixturesException
     * @throws MappingException
     */
    public function getEntityMetadata(string $className): Entity
    {
        if (!isset($this->entities[$className])) {
            $originalMetadata = $this->getMetadata($className);
            $fields = $this->getFields($className);
            $identifier = $this->getIdentifier($className, $fields);
            $mappings = $this->getMappings($className);
            $this->entities[$className] = new Entity(
                $className,
                $originalMetadata->getTableName(),
                $identifier,
                $fields,
                $mappings
            );
        }
        return $this->entities[$className];
    }

    /**
     * @param string $className
     * @param Field[] $fields
     * @return Identifier
     * @throws FixturesException
     * @throws MappingException
     */
    public function getIdentifier(string $className, array $fields): Identifier
    {
        $originalMetadata = $this->getMetadata($className);
        $fieldName = $originalMetadata->getSingleIdentifierFieldName();
        $foundFields = array_filter(
            array_values($fields),
            static function (FieldInterface $field) use ($fieldName) {return $field->getFieldName() === $fieldName;}
        );
        if ($foundFields === []) {
            throw new FixturesException('Wrong entity identifier ' . $fieldName . ' in class ' . $className);
        }
        return new Identifier($fieldName, $foundFields[0]->getColumnName());
    }

    /**
     * Return Doctrine metadata class
     *
     * @param string $className
     * @return ClassMetadata
     */
    protected function getMetadata(string $className): ClassMetadata
    {
        return $this->entityManager->getClassMetadata($className);
    }

    /**
     * Create fields array for the given class name
     *
     * @param string $className
     * @return Field[]
     */
    private function getFields(string $className): array
    {
        $fields = [];
        $metadata = $this->getMetadata($className);
        if (!is_array($metadata->fieldNames)) {
            return $fields;
        }
        foreach ($metadata->fieldNames as $columnName => $fieldName) {
            $fields[$columnName] = new Field(
                $columnName,
                $fieldName,
                $metadata->fieldMappings[$fieldName]['type'],
                $metadata->fieldMappings[$fieldName]['nullable']
            );
        }
        return $fields;
    }

    /**
     * Create mappings array for the given class name
     *
     * @param string $className
     * @return Mapping[]
     * @throws FixturesException
     */
    private function getMappings(string $className): array
    {
        $mappings = [];
        $metadata = $this->getMetadata($className);
        if (!is_array($metadata->associationMappings)) {
            return $mappings;
        }
        foreach ($metadata->associationMappings as $mappingData) {
            // include only these relations which the class is owning, not just being a target for something else
            if (!$mappingData['isOwningSide']) {
                continue;
            }
            $fieldName = $mappingData['fieldName'];
            if (!isset($mappingData['joinColumns'][0])) {
                throw new FixturesException("Field $fieldName does not have column name");
            }
            $columnData = $mappingData['joinColumns'][0];
            $mappings[$mappingData['targetEntity']] = new Mapping(
                $mappingData['fieldName'],
                $columnData['name'],
                $mappingData['targetEntity'],
                $columnData['nullable']
            );
        }
        return $mappings;
    }
}
