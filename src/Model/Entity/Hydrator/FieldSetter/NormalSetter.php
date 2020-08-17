<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\FieldSetter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Field;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\NonAccessibleFieldHelper;

class NormalSetter
{
    /**
     * @var NonAccessibleFieldHelper
     */
    private $nonAccessibleFieldHelper;

    public function __construct(NonAccessibleFieldHelper $nonAccessibleFieldHelper)
    {
        $this->nonAccessibleFieldHelper = $nonAccessibleFieldHelper;
    }

    public function setField(object $entity, string $className, Field $field, $value): void
    {
        $this->nonAccessibleFieldHelper->setNonAccessibleField($entity, $className, $field->getFieldName(), $value);
    }
}