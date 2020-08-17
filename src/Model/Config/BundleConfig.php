<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config;

class BundleConfig
{
    public const NAMES_TYPE_COLUMN = 'column';
    public const NAMES_TYPE_FIELD = 'field';

    public const STRATEGY_INSERT = 'insert';
    public const STRATEGY_UPSERT = 'upsert';

    private $configArray;
    private $projectDir;

    public function __construct(array $configArray, string $projectDir)
    {
        $this->configArray = $configArray;
        $this->projectDir = $projectDir;
    }

    public function getIdentifierName(): string
    {
        return $this->configArray['identifier']['name'];
    }

    public function isIdentifierPersistent(): bool
    {
        return $this->configArray['identifier']['persistent'];
    }

    public function getFixturesDirectory(): string
    {
        return $this->projectDir . DIRECTORY_SEPARATOR . $this->configArray['source']['directory'];
    }

    public function getFixtureFileExtension(): string
    {
        return $this->configArray['source']['file_extension'];
    }

    public function getSingleFile(): string
    {
        return $this->configArray['source']['single_file'];
    }

    public function getNamesType(): string
    {
        return $this->configArray['source']['names_type'];
    }

    public function getEntityPageSize(): string
    {
        return $this->configArray['entity_page_size'];
    }

    public function getStrategy(): string
    {
        return $this->configArray['strategy'];
    }
}