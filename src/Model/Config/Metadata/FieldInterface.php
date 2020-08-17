<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata;

interface FieldInterface
{
    public const TYPE_FIELD = 'field';
    public const TYPE_RELATION = 'relation';

    public const FIELD_TYPE_INTEGER = 'integer';
    public const FIELD_TYPE_STRING = 'string';
    public const FIELD_TYPE_DECIMAL = 'decimal';
    public const FIELD_TYPE_DATETIME = 'datetime';

    /**
     * @return string
     */
    public function getColumnName(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return string
     */
    public function getFieldType(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isNullable(): bool;
}
