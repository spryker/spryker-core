<?php

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerConfig;

class FileContent implements FileContentInterface
{
    /**
     * @var FileSystemServiceInterface
     */
    protected $fileSystemService;
    /**
     * @var FileManagerConfig
     */
    private $config;


    /**
     * @param FileSystemServiceInterface $fileSystemService
     * @param FileManagerConfig $config
     */
    public function __construct(FileSystemServiceInterface $fileSystemService, FileManagerConfig $config)
    {
        $this->fileSystemService = $fileSystemService;
        $this->config = $config;
    }

    /**
     * @param string $currentFilePathName
     * @param string $fileName
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     */
    public function save(string $currentFilePathName, string $fileName)
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemStreamTransfer->setPath($fileName);

        $stream = fopen($currentFilePathName, 'r+');

        try {
            $this->fileSystemService->putStream($fileSystemStreamTransfer, $stream);
        } catch (FileSystemStreamException $exception) {
            $this->closeStream($stream);
            throw $exception;
        }

        $this->closeStream($stream);
    }

    /**
     * @param string $fileName
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     */
    public function delete(string $fileName)
    {
        $fileSystemDeleteTransfer = new FileSystemDeleteTransfer();
        $fileSystemDeleteTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemDeleteTransfer->setPath($fileName);

        $this->fileSystemService->delete($fileSystemDeleteTransfer);
    }

    /**
     * @param string $fileName
     * @return string
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     */
    public function read(string $fileName)
    {
        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemQueryTransfer->setPath($fileName);

        return $this->fileSystemService->read($fileSystemQueryTransfer);
    }

    /**
     * @param $stream
     */
    protected function closeStream($stream)
    {
        if (is_resource($stream)) {
            fclose($stream);
        }
    }

}
