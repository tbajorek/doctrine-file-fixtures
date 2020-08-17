<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Data;

class ClassSet
{
    private $classes = [];

    public function addClass(string $className): self
    {
        $this->classes[$className] = 1;
        return $this;
    }

    public function hasClass(string $className): bool
    {
        return isset($this->classes[$className]);
    }

    public function removeClass(string $className): self
    {
        unset($this->classes[$className]);
        return $this;
    }

    public function getClasses(): array
    {
        return array_keys($this->classes);
    }
}