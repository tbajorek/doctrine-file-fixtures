<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\Adapter;

use Tbajorek\DoctrineFileFixturesBundle\Model\Data\Fixture;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixtureFile\FixtureFileInterface;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class Csv implements FixtureFileInterface
{
    public const COLUMN_SEPARATOR = ',';
    public const NEW_LINE = '\n';

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var resource
     */
    private $handle;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * @param string $fileName
     * @param string $className
     * @throws FixturesException
     */
    public function init(string $fileName, string $className): void
    {
        if ($this->handle !== null) {
            $this->close();
        }
        $this->fileName = $fileName;
        $this->handle = fopen($fileName, 'a+b');
        if (!$this->handle) {
            throw new FixturesException("File $fileName can not be opened");
        }
        $headers = explode(self::COLUMN_SEPARATOR, fgets($this->handle));
        if (!$headers) {
            throw new FixturesException("File $fileName doesnt have correct header");
        }
        $this->headers = $headers;
    }

    /**
     * @return Fixture|null
     */
    public function loadNextFixture(): ?Fixture
    {
        if (feof($this->handle)) {
            return null;
        }
        $values = explode(self::COLUMN_SEPARATOR, fgets($this->handle));
        return new Fixture(array_combine($this->headers, $values));
    }

    /**
     * @param Fixture[] $data
     * @throws FixturesException
     */
    public function save(array $data): void
    {
        if ($this->handle === null) {
            throw new FixturesException("File is not initialized");
        }
        if (!(count($data) && isset($data[0]))) {
            return;
        }
        $fileLines = [];
        if (fstat($this->handle)['size'] === 0) {
            $fileLines[] = implode(self::COLUMN_SEPARATOR, $this->headers);
        }
        foreach ($data as $lineData) {
            $fileLines[] = implode(self::COLUMN_SEPARATOR, $lineData->getData());
        }
        fwrite($this->handle, implode(self::NEW_LINE, $fileLines));
    }

    public function clean(): void
    {
        ftruncate($this->handle, 0);
    }

    public function remove(): void
    {
        if ($this->handle !== null) {
            $this->close();
        }
        unlink($this->fileName);
    }

    public function close(): void
    {
        fclose($this->handle);
        $this->handle = null;
    }
}