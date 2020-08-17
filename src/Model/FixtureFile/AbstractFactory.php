<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

abstract class AbstractFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var BundleConfig
     */
    private $bundleConfig;
    /**
     * @var array
     */
    private $extensionMapping;

    public function __construct(ContainerInterface $container, BundleConfig $bundleConfig, array $extensionMapping = [])
    {
        $this->container = $container;
        $this->bundleConfig = $bundleConfig;
        $this->extensionMapping = $extensionMapping;
    }

    /**
     * @return string
     * @throws FixturesException
     */
    protected function getClassName(): string
    {
        $fileType = $this->bundleConfig->getFixtureFileExtension();
        if (!isset($this->extensionMapping[$fileType])) {
            throw new FixturesException("Extension type $fileType does not have assigned class");
        }
        return $this->extensionMapping[$fileType];
    }

    /**
     * @return object
     * @throws FixturesException
     */
    protected function getObject(): object
    {
        $className = $this->getClassName();
        if (!$this->container->has($className)) {
            throw new FixturesException("$className is not available in services");
        }
        return $this->container->get($className);
    }
}