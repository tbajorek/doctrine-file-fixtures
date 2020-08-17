<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;

class IntegerValueGetter implements ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return int|null
     */
    public function getValue(string $fileColumnName, array $data)
    {
        if (isset($data[$fileColumnName])) {
            return (int)$data[$fileColumnName];
        }
        return null;
    }

    /**
     * @param FieldInterface $field
     * @return int|null
     */
    public function getEmptyValue(FieldInterface $field)
    {
        return $field->isNullable() ? null : 0;
    }
}