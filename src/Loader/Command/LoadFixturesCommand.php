<?php

namespace Tbajorek\DoctrineFileFixturesBundle\Loader\Command;

use App\Entity\Address;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tbajorek\DoctrineFileFixturesBundle\ConsoleLogger\Logger;
use Tbajorek\DoctrineFileFixturesBundle\Model\FixturesException;
use Tbajorek\DoctrineFileFixturesBundle\Operation\OperationInterface;

class LoadFixturesCommand extends Command
{
    protected static $defaultName = 'doctrine:file-fixtures:load';

    private $loadOperation;
    private $clearOperation;

    public function __construct(OperationInterface $loadOperation, OperationInterface $clearOperation, string $name = null)
    {
        parent::__construct($name);
        $this->loadOperation = $loadOperation;
        $this->clearOperation = $clearOperation;
    }

    protected function configure()
    {
        $this->setDescription('Loads fixtures from files')
            ->addOption('clear', 'c', InputOption::VALUE_OPTIONAL, 'Clear database before loading fixtures', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Doctrine File Fixtures loader');
        $io->newLine();
        $logger = new Logger($io);
        try {
            if($input->getOption('clear') !== false) {
                $this->clearOperation->perform($logger);
            }
            $this->loadOperation->perform($logger);
        } catch (FixturesException $e) {
            $io->error($e->getMessage());
        }
        return Command::SUCCESS;
    }
}