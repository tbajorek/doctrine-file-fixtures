<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Excel;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Adapter\Xlsx\ExcelAdapterInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Exception\SheetNotExist;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class ExcelAdapter implements ExcelAdapterInterface
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $activeSheet;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var array
     */
    private $headers = [];

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getActiveSheet(): ?string
    {
        return $this->activeSheet;
    }

    public function getRows(): int
    {
        if ($this->activeSheet === null) {
            return 0;
        }
        return $this->spreadsheet->getActiveSheet()->getHighestRow();
    }

    /**
     * Open file with given path
     *
     * @param string $fileName
     */
    public function open(string $fileName): void
    {
        if ($this->fileName !== $fileName) {
            $reader = new XlsxReader();
            $this->spreadsheet = $reader->load($fileName);
            unset($reader);
            $this->fileName = $fileName;
        }
    }

    /**
     * Load specified sheet; create it if not exist yet
     *
     * @param string $sheetName
     * @param bool $createIfNotExists
     * @throws FixturesException
     * @throws SheetNotExist
     */
    public function loadSheet(string $sheetName, bool $createIfNotExists = false): void
    {
        if ($this->activeSheet !== $sheetName) {
            $sheetNames = $this->spreadsheet->getSheetNames();
            if (!in_array($sheetName, $sheetNames, true)) {
                if ($createIfNotExists) {
                    $this->spreadsheet->createSheet()->setTitle($sheetName);
                } else {
                    throw new SheetNotExist("Sheet $sheetName is not available");
                }
            }
            try {
                $this->spreadsheet->setActiveSheetIndexByName($sheetName);
                $this->headers = $this->getColumnNames();
                $this->activeSheet = $sheetName;
            } catch (Exception $e) {
                throw new FixturesException("Sheet $sheetName can not be loaded");
            }
        }
    }

    /**
     * Return all cells mapped by headers
     *
     * @param int $rowNumber
     * @return array
     * @throws FixturesException
     */
    public function getRowValue(int $rowNumber): array
    {
        $values = [];
        foreach ($this->headers as $header) {
            $values[$header] = $this->getCellValue($header, $rowNumber);
        }
        return $values;
    }

    /**
     * Set all cells for row mapped by column names
     *
     * @param int $rowNumber
     * @param array $rowData
     */
    public function setRowValue(int $rowNumber, array $rowData): void
    {
        foreach ($rowData as $header => $value) {
            $this->setCellValue($header, $rowNumber, $value);
        }
    }

    /**
     * Get cell value for column name and row number
     *
     * @param string $columnName
     * @param int $rowNumber
     * @return mixed
     * @throws FixturesException
     */
    public function getCellValue(string $columnName, int $rowNumber)
    {
        $columnIndex = $this->getColumnIndexByName($columnName);
        $cell = $this->spreadsheet->getActiveSheet()->getCellByColumnAndRow($columnIndex, $rowNumber);
        if ($cell === null) {
            throw new FixturesException("No cell exists for column $columnName and row $rowNumber");
        }
        return $cell->getFormattedValue();
    }

    public function setCellValue(string $columnName, int $rowNumber, string $value): void
    {
        $columnIndex = $this->getColumnIndexByName($columnName);
        $this->spreadsheet->getActiveSheet()->setCellByColumnAndRow($columnIndex, $rowNumber, $value);
    }

    /**
     * Return column names for current sheet
     *
     * @return array
     */
    public function getColumnNames(): array
    {
        $headers = [];
        $columnNumber = $this->getHeadersNumber();
        for ($columnIndex = 1; $columnIndex <= $columnNumber; ++$columnIndex) {
            $headers[] = $this->spreadsheet->getActiveSheet()->getCellByColumnAndRow($columnIndex, 1)->getValue();
        }
        return $headers;
    }

    /**
     * Set column names for current sheet
     *
     * @param array $columnNames
     */
    public function setColumnNames(array $columnNames): void
    {
        $columnIndex = 0;
        foreach ($columnNames as $columnName) {
            $this->spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columnIndex++, 1, $columnName);
        }
        $this->headers = $columnNames;
    }

    /**
     * Save changes in file
     * @throws FixturesException
     */
    public function saveChanges(): void
    {
        $writer = new XlsxWriter($this->spreadsheet);
        try {
            $writer->save($this->fileName);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            throw new FixturesException("Changes can not be saved in $this->fileName file");
        }
    }

    public function clearSheet(): void
    {
        $highestColumm = $this->spreadsheet->getActiveSheet()->getHighestColumn();
        $highestRow = $this->spreadsheet->getActiveSheet()->getHighestRow();
        for($columnIndex = 'A'; $columnIndex <= $highestColumm; ++$columnIndex) {
            for ($rowIndex = 1; $rowIndex <= $highestRow; ++$rowIndex) {
                $this->spreadsheet->getActiveSheet()->setCellValue($columnIndex . $rowIndex, '');
            }
        }
    }

    /**
     * Remove file
     */
    public function removeFile(): void
    {
        if ($this->spreadsheet !== null) {
            $this->close();
        }
        unlink($this->fileName);
    }

    /**
     * Close the file
     */
    public function close(): void
    {
        unset($this->spreadsheet, $this->activeSheet);
        $this->spreadsheet = $this->activeSheet = null;
    }

    /**
     * Return column index for the header name
     *
     * @param string $columnName
     * @return int|null
     * @throws FixturesException
     */
    private function getColumnIndexByName(string $columnName): ?int
    {
        $index = array_search($columnName, $this->headers, true);
        if ($index === false) {
            throw new FixturesException("Column name $columnName doesnt exist in sheet $this->activeSheet");
        }
        return $index + 1;
    }

    private function getHeadersNumber(): int
    {
        $number = 0;
        while($this->spreadsheet->getActiveSheet()->getCellByColumnAndRow($number + 1, 1, false) !== null) {
            ++$number;
        }
        return $number;
    }
}