<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;
use DateTime;

class DatetimeValueGetter implements ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return DateTime|null
     */
    public function getValue(string $fileColumnName, array $data)
    {
        if (isset($data[$fileColumnName])) {
            return new DateTime($data[$fileColumnName]);
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