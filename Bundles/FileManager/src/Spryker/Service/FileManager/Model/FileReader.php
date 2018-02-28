<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridgeInterface;
use Spryker\Service\FileManager\FileManagerServiceConfig;

class FileReader implements FileReaderInterface
{
    const FILE_SYSTEM_DOCUMENT = 'fileSystem';

    /**
     * @var \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Service\FileManager\FileManagerServiceConfig
     */
    protected $config;

    /**
     * FileReader constructor.
     *
     * @param \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridgeInterface $fileSystem
     * @param \Spryker\Service\FileManager\FileManagerServiceConfig                                  $config
     */
    public function __construct(FileManagerToFileSystemBridgeInterface $fileSystem, FileManagerServiceConfig $config)
    {
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $fileName)
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
     * {@inheritdoc}
     */
    public function readStream(string $fileName)
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer($fileName);

        return $this->fileSystem->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileSystemStreamTransfer
     */
    protected function createStreamTransfer(string $fileName)
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemStreamTransfer->setPath($fileName);

        return $fileSystemStreamTransfer;
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(string $fileName)
    {
        $file = new FileTransfer();
        $file->setFileName($fileName);

        return $file;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $fileInfo = new FileInfoTransfer();
        $fileInfo->setType($this->fileSystem->getMimeType($fileSystemQueryTransfer));

        return $fileInfo;
    }
}
