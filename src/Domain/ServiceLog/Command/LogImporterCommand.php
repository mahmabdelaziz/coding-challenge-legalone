<?php

namespace App\Domain\ServiceLog\Command;

use App\Domain\ServiceLog\Service\LogImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'log:import',
    description: 'Add a short description for your command',
)]
class LogImporterCommand extends Command
{
    public function __construct(
        private readonly LogImportService $logImportService
    )
    {
        parent::__construct();
    }


    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $this->logImportService->handle();

            $io->success('All log lines are sent to the import queue, please watch it to see progress.');

            return Command::SUCCESS;
        }catch (\Exception $exception){
            $io->error(['Something went wrong', $exception]);
            return Command::FAILURE;
        }

    }
}
