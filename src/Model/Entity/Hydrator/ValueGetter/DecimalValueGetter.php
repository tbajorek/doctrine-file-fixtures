<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;

class DecimalValueGetter implements ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return string|null
     */
    public function getValue(string $fileColumnName, array $data)
    {
        if (isset($data[$fileColumnName])) {
            return $this->formatValue($data[$fileColumnName]) ?: null;
        }
        return null;
    }

    /**
     * @param FieldInterface $field
     * @return string|null
     */
    public function getEmptyValue(FieldInterface $field)
    {
        return $field->isNullable() ? null : 0.0;
    }

    /**
     * Convert decimal number format to accepted by database
     *
     * @param string $originalValue
     * @return string
     */
    private function formatValue(string $originalValue): string
    {
        return str_replace(',', '.', $originalValue);
    }
}