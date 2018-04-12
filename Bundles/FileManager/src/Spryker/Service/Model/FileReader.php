<?php

namespace Spryker\Service\FileManager\Model;


use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileManager\FileManagerServiceConfig;
use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;

class FileReader implements FileReaderInterface
{

    /**
     * @var FileManagerToFileSystemServiceInterface
     */
    protected $fileSystem;

    /**
     * @var FileManagerServiceConfig
     */
    protected $config;

    /**
     * FileReader constructor.
     * @param FileManagerToFileSystemServiceInterface $fileSystem
     * @param FileManagerServiceConfig $config
     */
    public function __construct(FileManagerToFileSystemServiceInterface $fileSystem, FileManagerServiceConfig $config)
    {
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * @param $fileName
     * @return FileManagerReadResponseTransfer
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     */
    public function read($fileName)
    {
        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemQueryTransfer->setPath($fileName);

        $fileManagerReadResponse = new FileManagerReadResponseTransfer();
        $fileContent = $this->fileSystem->read($fileSystemQueryTransfer);
        $fileManagerReadResponse->setContent($fileContent);

        $fileManagerReadResponse->setFile($this->createFileTransfer($fileName));
        $fileManagerReadResponse->setFileInfo($this->createFileInfoTransfer($fileSystemQueryTransfer));

        return $fileManagerReadResponse;
    }

    /**
     * @param $fileName
     * @return FileTransfer
     */
    protected function createFileTransfer($fileName)
    {
        $file = new FileTransfer();
        $file->setFileName($fileName);

        return $file;
    }

    /**
     * @param FileSystemQueryTransfer $fileSystemQueryTransfer
     * @return FileInfoTransfer
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     */
    protected function createFileInfoTransfer(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $fileInfo = new FileInfoTransfer();
        $fileInfo->setType($this->fileSystem->getMimeType($fileSystemQueryTransfer));

        return $fileInfo;
    }

}
