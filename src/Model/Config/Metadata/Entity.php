<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Config\Metadata;

class Entity
{
    private $className;
    private $table;
    private $identifier;
    private $fields;
    private $mappings;

    /**
     * Entity constructor.
     * @param string $className
     * @param string $table
     * @param Identifier $identifier
     * @param Field[] $fields
     * @param Mapping[] $mappings
     */
    public function __construct(string $className, string $table, Identifier $identifier, array $fields, array $mappings = [])
    {
        $this->className = $className;
        $this->table = $table;
        $this->identifier = $identifier;
        $this->fields = $fields;
        $this->mappings = $mappings;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return Identifier
     */
    public function getIdentifier(): Identifier
    {
        return $this->identifier;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return Mapping[]
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    public function getDependencies(): array
    {
        return array_keys($this->mappings);
    }

    /**
     * @return FieldInterface[]
     */
    public function getAllFields(): array
    {
        return array_values($this->getFields() + $this->getMappings());
    }
}