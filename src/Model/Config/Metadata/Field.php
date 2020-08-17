<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata;

class Field implements FieldInterface
{
    private $columnName;
    private $fieldName;
    private $fieldType;
    private $isNullable;

    public function __construct(string $columnName, string $fieldName, string $fieldType, bool $isNullable)
    {
        $this->columnName = $columnName;
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->isNullable = $isNullable;
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
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function getType(): string
    {
        return self::TYPE_FIELD;
    }
}