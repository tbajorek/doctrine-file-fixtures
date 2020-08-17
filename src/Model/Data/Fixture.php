<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Data;

class Fixture
{
    private $headers;
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->headers = array_keys($data);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $field
     * @return mixed|null
     */
    public function getValue(string $field)
    {
        return $this->data[$field] ?? null;
    }
}