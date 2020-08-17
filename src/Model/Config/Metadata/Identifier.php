<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata;

class Identifier
{
    /** @var string */
    private $fieldName;

    /** @var string */
    private $columnName;

    /**
     * Identifier constructor.
     * @param string $fieldName
     * @param string $columnName
     */
    public function __construct(string $fieldName, string $columnName)
    {
        $this->fieldName = $fieldName;
        $this->columnName = $columnName;
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
}