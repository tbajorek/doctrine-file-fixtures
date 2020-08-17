<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\FieldSetter;

use ReflectionProperty;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\Mapping;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\NonAccessibleFieldHelper;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\SharedRepository;

class RelationSetter
{
    /**
     * @var SharedRepository
     */
    private $sharedRepository;

    /**
     * @var NonAccessibleFieldHelper
     */
    private $nonAccessibleFieldHelper;

    public function __construct(SharedRepository $sharedRepository, NonAccessibleFieldHelper $nonAccessibleFieldHelper)
    {
        $this->sharedRepository = $sharedRepository;
        $this->nonAccessibleFieldHelper = $nonAccessibleFieldHelper;
    }

    public function setField(object $entity, string $className, Mapping $mapping, $value): void
    {
        $this->nonAccessibleFieldHelper->setNonAccessibleField(
            $entity,
            $className,
            $mapping->getFieldName(),
            $this->sharedRepository->getEntity($value, $mapping->getTargetClass())
        );
    }
}