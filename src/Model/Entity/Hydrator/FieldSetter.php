<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Entity;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Field;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Mapping;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\FieldSetter\NormalSetter;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\FieldSetter\RelationSetter;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class FieldSetter
{
    /**
     * @var NormalSetter
     */
    private $normalSetter;
    /**
     * @var RelationSetter
     */
    private $relationSetter;
    /**
     * @var BundleConfig
     */
    private $bundleConfig;
    /**
     * @var ValueGetterFactory
     */
    private $valueGetterFactory;

    public function __construct(NormalSetter $normalSetter, RelationSetter $relationSetter, BundleConfig $bundleConfig, ValueGetterFactory $valueGetterFactory)
    {
        $this->normalSetter = $normalSetter;
        $this->relationSetter = $relationSetter;
        $this->bundleConfig = $bundleConfig;
        $this->valueGetterFactory = $valueGetterFactory;
    }

    /**
     * @param object $entity
     * @param Entity $metadata
     * @param FieldInterface $field
     * @param string $fileColumnName
     * @param array $data
     * @throws FixturesException
     */
    public function setField(object $entity, Entity $metadata, FieldInterface $field, string $fileColumnName, array $data): void
    {
        if (
            $this->bundleConfig->getIdentifierName() === $fileColumnName &&
            !$this->bundleConfig->isIdentifierPersistent() &&
            $metadata->getIdentifier()->getColumnName() === $fileColumnName
        ) {
            return;
        }
        $fieldType = $field->getType();
        $valueGetter = $this->valueGetterFactory->create($field->getFieldType());
        $value = $valueGetter->getValue($fileColumnName, $data) ?? $valueGetter->getEmptyValue($field);
        switch ($fieldType) {
            case FieldInterface::TYPE_FIELD:
                /** @var Field $field */
                $this->normalSetter->setField($entity, $metadata->getClassName(), $field, $value);
                break;
            case FieldInterface::TYPE_RELATION:
                /** @var Mapping $field */
                $this->relationSetter->setField($entity, $metadata->getClassName(), $field, $value);
                break;
            default:
                throw new FixturesException("Wrong field type: $fieldType");
        }
    }
}