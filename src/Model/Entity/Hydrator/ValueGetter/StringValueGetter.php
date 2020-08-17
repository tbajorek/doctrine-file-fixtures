<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;

class StringValueGetter implements ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return string|null
     */
    public function getValue(string $fileColumnName, array $data)
    {
        if (isset($data[$fileColumnName])) {
            return (string)$data[$fileColumnName];
        }
        return null;
    }

    /**
     * @param FieldInterface $field
     * @return string|null
     */
    public function getEmptyValue(FieldInterface $field)
    {
        return $field->isNullable() ? null : '';
    }
}