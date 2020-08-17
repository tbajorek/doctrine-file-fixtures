<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Resolver;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileResolverInterface;

class FilePerEntityResolver implements FixtureFileResolverInterface
{
    /**
     * @var BundleConfig
     */
    private $bundleConfig;
    /**
     * @var MetadataProviderInterface
     */
    private $metadataProvider;

    /**
     * FilePerEntityResolver constructor.
     * @param BundleConfig $bundleConfig
     * @param MetadataProviderInterface $metadataProvider
     */
    public function __construct(BundleConfig $bundleConfig, MetadataProviderInterface $metadataProvider)
    {
        $this->bundleConfig = $bundleConfig;
        $this->metadataProvider = $metadataProvider;
    }

    /**
     * @param string $entityName
     * @return string
     */
    public function resolve(string $entityName): string
    {
        return $this->bundleConfig->getFixturesDirectory() .
            $this->metadataProvider->getEntityMetadata($entityName)->getTable() . '.' .
            $this->bundleConfig->getFixtureFileExtension();
    }
}