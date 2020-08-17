<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Operation;

use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;
use Tbajorek\DoctrineFileFixturesBundle\Model\LoggerInterface;

interface OperationInterface
{
    /**
     * @throws FixturesException
     */
    public function perform(LoggerInterface $logger): void;
}