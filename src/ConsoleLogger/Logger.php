<?php

namespace Tbajorek\DoctrineFileFixturesBundle\ConsoleLogger;

use Symfony\Component\Console\Style\SymfonyStyle;
use Tbajorek\DoctrineFileFixturesBundle\Model\LoggerInterface;

class Logger implements LoggerInterface
{
    /**
     * @var OutputInterface
     */
    private $outputLogger;

    public function __construct(SymfonyStyle $outputLogger)
    {
        $this->outputLogger = $outputLogger;
    }

    public function error(string $message): void
    {
        $this->outputLogger->error($message);
    }

    public function info(string $message): void
    {
        $this->outputLogger->text($message);
    }

    public function success(string $message): void
    {
        $this->outputLogger->success($message);
    }
}