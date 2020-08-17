<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;

interface ValueGetterInterface
{
    /**
     * @param string $fileColumnName
     * @param array $data
     * @return int|string|bool|null
     */
    public function getValue(string $fileColumnName, array $data);

    /**
     * @param FieldInterface $field
     * @return int|string|bool|null
     */
    public function getEmptyValue(FieldInterface $field);
}