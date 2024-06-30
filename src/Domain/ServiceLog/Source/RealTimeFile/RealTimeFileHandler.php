<?php

namespace App\Domain\ServiceLog\Source\RealTimeFile;

use App\Domain\ServiceLog\DTO\LogLineDTO;
use App\Domain\ServiceLog\DTO\LogLineDtoFactory;
use App\Domain\ServiceLog\Source\SourceInterface;
use Iterator;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class RealTimeFileHandler implements SourceInterface
{
    private FilesystemOperator $filesystem;

    public function __construct(
        private readonly string $logFilePath,
        FilesystemOperator $defaultStorage,
        private readonly LineParser $lineParser,
        private readonly LogLineDtoFactory $logLineDtoFactory
    )
    {
        $this->filesystem = $defaultStorage;
    }

    /**
     * @return Iterator<LogLineDTO>
     * @throws \League\Flysystem\FilesystemException
     */
    public function getLogLines(): Iterator
    {
       if (!$this->filesystem->fileExists($this->logFilePath)){
           throw new FileNotFoundException();
       }

       $fileStream = $this->filesystem->readStream($this->logFilePath);
       $position = $this->getLastPosition();
       fseek($fileStream, $position);
       $count = 0;
        while (($line = fgets($fileStream)) !== false){
            $count++;
            if(!$parsedLine = $this->lineParser->parse($line)){continue;}
            $this->updateLastPosition(ftell($fileStream));
            yield $this->logLineDtoFactory->createFromLineArray($parsedLine);
        }
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    private function getLastPosition(): int
    {
        $lastPositionFileName = $this->getLastPositionFileName();
        $lastPositionFileExists = $this->filesystem->fileExists($lastPositionFileName);
        if ($lastPositionFileExists){
            return (int) $this->filesystem->read($lastPositionFileName);
        }

        return 0;
    }

    private function getLastPositionFileName(): string
    {
        return $this->logFilePath.'.last_position';
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    private function updateLastPosition(int $position)
    {
        $lastPositionFileName = $this->getLastPositionFileName();
        $this->filesystem->write($lastPositionFileName, $position);
    }
}
