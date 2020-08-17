<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Resolver;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileResolverInterface;

class SingleFileResolver implements FixtureFileResolverInterface
{
    /**
     * @var BundleConfig
     */
    private $bundleConfig;

    /**
     * SingleFileResolver constructor.
     * @param BundleConfig $bundleConfig
     */
    public function __construct(BundleConfig $bundleConfig)
    {
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * @param string $entityName
     * @return string
     */
    public function resolve(string $entityName): string
    {
        return $this->bundleConfig->getFixturesDirectory() .
            $this->bundleConfig->getSingleFile() . '.' .
            $this->bundleConfig->getFixtureFileExtension();
    }
}