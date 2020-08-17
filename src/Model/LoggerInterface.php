<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Model;

interface LoggerInterface
{
    public function error(string $message): void;

    public function info(string $message): void;

    public function success(string $message): void;
}