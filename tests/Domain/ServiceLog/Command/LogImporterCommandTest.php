<?php

namespace App\Tests\Domain\ServiceLog\Command;

use App\Domain\ServiceLog\Command\LogImporterCommand;
use App\Domain\ServiceLog\Service\LogImportService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class LogImporterCommandTest extends TestCase
{
    private $logImportService;
    private $commandTester;

    protected function setUp(): void
    {
        $this->logImportService = $this->createMock(LogImportService::class);
        $command = new LogImporterCommand($this->logImportService);
        $application = new Application();
        $application->add($command);
        $this->commandTester = new CommandTester($application->find('log:import'));
    }

    public function testExecuteSuccessful(): void
    {
        $this->logImportService->expects($this->once())
            ->method('handle')
            ->willReturn(null);

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('All log lines are sent to the import queue, please watch it to see progress.', $output);
        $this->assertEquals(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    public function testExecuteFailure(): void
    {
        $this->logImportService->expects($this->once())
            ->method('handle')
            ->willThrowException(new \Exception('Test exception'));

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Something went wrong', $output);
        $this->assertStringContainsString('Test exception', $output);
        $this->assertEquals(Command::FAILURE, $this->commandTester->getStatusCode());
    }
}
