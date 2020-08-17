<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Hydrator\ValueGetter\ValueGetterInterface;

class ValueGetterFactory
{
    private $container;

    private $defaultValueGetter;

    private $getters;

    private $instances = [];

    /**
     * ValueGetterFactory constructor.
     * @param ContainerInterface $container
     * @param ValueGetterInterface $defaultValueGetter
     * @param array $getters
     */
    public function __construct(
        ContainerInterface $container,
        ValueGetterInterface $defaultValueGetter,
        array $getters = []
    ) {
        $this->container = $container;
        $this->defaultValueGetter = $defaultValueGetter;
        $this->getters = $getters;
    }

    /**
     * @param string $fieldType
     * @return ValueGetterInterface
     */
    public function create(string $fieldType): ValueGetterInterface
    {
        if (!isset($this->instances[$fieldType])) {
            if (isset($this->getters[$fieldType])) {
                $this->instances[$fieldType] = $this->container->get($this->getters[$fieldType]);
            } else {
                $this->instances[$fieldType] = $this->defaultValueGetter;
            }
        }
        return $this->instances[$fieldType];
    }
}