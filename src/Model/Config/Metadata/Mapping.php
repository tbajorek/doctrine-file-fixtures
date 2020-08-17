<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata;

class Mapping implements FieldInterface
{
    /**
     * @var string
     */
    private $fieldName;
    /**
     * @var string
     */
    private $columnName;

    /**
     * @var string
     */
    private $targetClass;

    /**
     * @var bool
     */
    private $isNullable;

    public function __construct(string $fieldName, string $columnName, string $targetClass, bool $isNullable)
    {
        $this->fieldName = $fieldName;
        $this->columnName = $columnName;
        $this->targetClass = $targetClass;
        $this->isNullable = $isNullable;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getType(): string
    {
        return self::TYPE_RELATION;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return self::FIELD_TYPE_STRING;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }
}