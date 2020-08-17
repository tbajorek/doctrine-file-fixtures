<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;

class DefaultValueGetter implements ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return mixed|null
     */
    public function getValue(string $fileColumnName, array $data)
    {
        if (isset($data[$fileColumnName])) {
            return $data[$fileColumnName];
        }
        return null;
    }

    /**
     * @param FieldInterface $field
     * @return null
     */
    public function getEmptyValue(FieldInterface $field)
    {
        return null;
    }
}