<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model\Entity\Exception;

use Tbajorek\DoctrineFileFixturesBundle\Model\Data\ClassSet;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;

class CycleException extends FixturesException
{
    private $stack;

    public function __construct(string $className, ClassSet $stack)
    {
        parent::__construct("Class $className causes cycle. It's not possible to deduct order of fixtures");
        $this->stack = $stack;
    }

    public function getStack(): ClassSet
    {
        return $this->stack;
    }
}