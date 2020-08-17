<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Adapter\Xlsx;

use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Exception\SheetNotExist;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

interface ExcelAdapterInterface
{
    public function getFileName(): string;

    public function getActiveSheet(): ?string;

    public function getRows(): int;

    public function open(string $fileName): void;

    /**
     * Load specified sheet; create it if not exist yet
     *
     * @param string $sheetName
     * @param bool $createIfNotExists
     * @throws FixturesException
     * @throws SheetNotExist
     */
    public function loadSheet(string $sheetName, bool $createIfNotExists = false): void;

    public function getRowValue(int $rowNumber): array;

    public function setRowValue(int $rowNumber, array $rowData): void;

    public function getCellValue(string $columnName, int $rowNumber);

    public function setCellValue(string $columnName, int $rowNumber, string $value): void;

    public function getColumnNames(): array;

    public function setColumnNames(array $columnNames): void;

    public function saveChanges(): void;

    public function clearSheet(): void;

    public function removeFile(): void;

    public function close(): void;
}