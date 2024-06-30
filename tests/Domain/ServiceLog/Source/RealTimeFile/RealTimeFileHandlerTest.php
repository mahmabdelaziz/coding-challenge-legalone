<?php

namespace App\Tests\Domain\ServiceLog\Source\RealTimeFile;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\DTO\LogLineDtoFactory;
use App\Domain\ServiceLog\Source\RealTimeFile\RealTimeFileHandler;
use App\Domain\ServiceLog\Source\RealTimeFile\LineParser;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class RealTimeFileHandlerTest extends TestCase
{
    private $filesystem;
    private $lineParser;
    private $logLineDtoFactory;
    private $logFilePath;
    private $logFileLastPositionPath;
    private $realTimeFileHandler;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(FilesystemOperator::class);
        $this->lineParser = $this->createMock(LineParser::class);
        $this->logLineDtoFactory = $this->createMock(LogLineDtoFactory::class);
        $this->logFilePath = 'path/to/logfile.log';
        $this->logFileLastPositionPath = $this->logFilePath. '.last_position';

        $this->realTimeFileHandler = new RealTimeFileHandler(
            $this->logFilePath,
            $this->filesystem,
            $this->lineParser,
            $this->logLineDtoFactory
        );
    }

    public function testGetLogLines()
    {
        ///  fake file in memory // line parser will be tested in isolation
        $fileStream = fopen('php://memory', 'r+');
        fwrite($fileStream, "line1\nline2\nline3\n");
        rewind($fileStream);

        $this->filesystem->method('fileExists')
            ->willReturnCallback(function ($file) {
                return true;
            });

        $this->filesystem->method('readStream')
            ->with($this->logFilePath)
            ->willReturn($fileStream);

        $this->lineParser->expects($this->exactly(3))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(
                ['service' => 'test_service1', 'requested_at' => '27/Jun/2023:12:34:56 +0000', 'request' => 'request1', 'response_code' => 200],
                ['service' => 'test_service2', 'requested_at' => '27/Jun/2023:12:35:56 +0000', 'request' => 'request2', 'response_code' => 200],
                false);

        $logLineDTO1 = $this->createMock(LogLineDTO::class);
        $logLineDTO2 = $this->createMock(LogLineDTO::class);

        $this->logLineDtoFactory->method('createFromLineArray')
            ->willReturnOnConsecutiveCalls($logLineDTO1, $logLineDTO2);

        $writtenPositions = [];
        $this->filesystem->method('write')
            ->willReturnCallback(function ($path, $position) use (&$writtenPositions) {
                $writtenPositions[] = [$path, $position];
            });

        $logLines = iterator_to_array($this->realTimeFileHandler->getLogLines());

        $this->assertCount(2, $logLines);
        $this->assertSame($logLineDTO1, $logLines[0]);
        $this->assertSame($logLineDTO2, $logLines[1]);

        $this->assertEquals([$this->logFileLastPositionPath, 6], $writtenPositions[0]);
        $this->assertEquals([$this->logFileLastPositionPath, 12], $writtenPositions[1]);

    }

    public function testGetLogLinesWithLastPosition()
    {
        ///  fake file in memory // line parser will be tested in isolation
        $fileStream = fopen('php://memory', 'r+');
        fwrite($fileStream, "line1\nline2\nline3\n");
        rewind($fileStream);

        $this->filesystem->method('fileExists')
            ->willReturn(true);

        $this->filesystem->method('readStream')
            ->with($this->logFilePath)
            ->willReturn($fileStream);

        $this->filesystem->method('read')
            ->with($this->logFileLastPositionPath)
            ->willReturn('6');

        $this->lineParser->expects($this->exactly(2))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(
                ['service' => 'test_service2', 'requested_at' => '27/Jun/2023:12:35:56 +0000', 'request' => 'request2', 'response_code' => 200],
                false);

        $logLineDTO1 = $this->createMock(LogLineDTO::class);

        $this->logLineDtoFactory->method('createFromLineArray')
            ->willReturn($logLineDTO1);

        $this->filesystem->expects($this->once())->method('write')
            ->with($this->logFileLastPositionPath, 12);

        $logLines = iterator_to_array($this->realTimeFileHandler->getLogLines());

        $this->assertCount(1, $logLines);
        $this->assertSame($logLineDTO1, $logLines[0]);
    }

    public function testFailsOnFileNotFound(){

        $this->expectException(FileNotFoundException::class);

        $this->filesystem->method('fileExists')
            ->with($this->logFilePath)
            ->willReturn(false);

        iterator_to_array($this->realTimeFileHandler->getLogLines());
    }
}
