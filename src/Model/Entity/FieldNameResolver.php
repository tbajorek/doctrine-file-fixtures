<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\BundleConfig;
use Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata\FieldInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class FieldNameResolver
{
    /**
     * @var BundleConfig
     */
    private $bundleConfig;

    public function __construct(BundleConfig $bundleConfig)
    {
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * @param FieldInterface $field
     * @return string
     * @throws FixturesException
     */
    public function resolveFileColumn(FieldInterface $field): string
    {
        $namesType = $this->bundleConfig->getNamesType();
        switch ($namesType) {
            case BundleConfig::NAMES_TYPE_COLUMN:
                return $field->getColumnName();
            case BundleConfig::NAMES_TYPE_FIELD:
                return $field->getFieldName();
            default:
                throw new FixturesException("Wrong value in config for source name type is set");
        }
    }
}