<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Adapter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Config\MetadataProviderInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Adapter\Xlsx\ExcelAdapterInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Exception\SheetNotExist;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Xlsx implements FixtureFileInterface
{
    /**
     * @var ExcelAdapterInterface
     */
    private $excelAdapter;

    /**
     * @var MetadataProviderInterface
     */
    private $metadataProvider;

    /** @var int */
    private $activeRow;

    public function __construct(ExcelAdapterInterface $excelAdapter, MetadataProviderInterface $metadataProvider)
    {
        $this->excelAdapter = $excelAdapter;
        $this->metadataProvider = $metadataProvider;
        $this->activeRow = 0;
    }

    /**
     * @param string $fileName
     * @param string $className
     * @throws SheetNotExist
     * @throws FixturesException
     */
    public function init(string $fileName, string $className): void
    {
        $this->excelAdapter->open($fileName);
        $sheetName = $this->metadataProvider->getEntityMetadata($className)->getTable();
        $this->excelAdapter->loadSheet($sheetName, false);
        $this->activeRow = 2;
    }

    /**
     * @return Fixture|null
     */
    public function loadNextFixture(): ?Fixture
    {
        if ($this->activeRow > $this->excelAdapter->getRows()) {
            return null;
        }
        return new Fixture($this->excelAdapter->getRowValue($this->activeRow++));
    }

    /**
     * @param Fixture[] $data
     */
    public function save(array $data): void
    {
        if (empty($data)|| !isset($data[0])) {
            return;
        }
        $this->excelAdapter->setColumnNames($data[0]->getHeaders());
        $activeRow = 1;
        foreach ($data as $row) {
            $this->excelAdapter->setRowValue($activeRow, $row->getData());
        }
        $this->excelAdapter->saveChanges();
    }

    public function clean(): void
    {
        $this->excelAdapter->clearSheet();
    }

    public function close(): void
    {
        $this->excelAdapter->close();
    }
}