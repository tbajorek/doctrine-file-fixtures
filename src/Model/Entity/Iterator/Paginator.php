<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Iterator;

class Paginator
{
    private $position;

    private $pageSize;

    private $pageNumber;

    private $numberOfPages;

    private $numberOfElements;

    public function __construct(int $pageSize, int $numberOfElements)
    {
        $this->position = 0;
        $this->pageSize = $pageSize;
        $this->pageNumber = 0;
        $this->numberOfElements = $numberOfElements;
        $this->numberOfPages = (int)floor($numberOfElements / $pageSize);
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * @return int
     */
    public function getNumberOfElements(): int
    {
        return $this->numberOfElements;
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function nextPosition(): bool
    {
        if ($this->isEndOfSet()) {
            return false;
        }
        if ($this->isEndOfPage()) {
            $this->position = 0;
            ++$this->pageNumber;
            return true;
        }
        ++$this->position;
        return true;
    }

    public function isEndOfPage(): bool
    {
        return $this->position >= $this->pageSize;
    }

    public function isEndOfSet(): bool
    {
        return $this->pageSize * $this->pageNumber + $this->position >= $this->numberOfElements;
    }
}