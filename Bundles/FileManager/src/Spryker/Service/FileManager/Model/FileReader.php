<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Service\FileManager\FileManagerConfig;

class FileReader implements FileReaderInterface
{
    const FILE_SYSTEM_DOCUMENT = 'fileSystem';

    /**
     * @var \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Service\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface $fileSystem
     * @param \Spryker\Service\FileManager\FileManagerConfig $config
     */
    public function __construct(FileManagerToFileSystemServiceInterface $fileSystem, FileManagerConfig $config)
    {
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $fileName)
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileSystemQueryTransfer = $this->createFileSystemQueryTransfer($fileName);

        $fileContent = $this->getReadFileContentFromStorage($fileSystemQueryTransfer);
        $fileManagerDataTransfer->setContent($fileContent);

        $fileManagerDataTransfer->setFile($this->createFileTransfer($fileName));
        $fileManagerDataTransfer->setFileInfo($this->createFileInfoTransfer($fileSystemQueryTransfer));

        return $fileManagerDataTransfer;
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
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    protected function getReadFileContentFromStorage(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystem->read($fileSystemQueryTransfer);
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
        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName($fileName);

        return $fileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $fileInfoTransfer = new FileInfoTransfer();
        $fileInfoTransfer->setType($this->fileSystem->getMimeType($fileSystemQueryTransfer));

        return $fileInfoTransfer;
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileSystemQueryTransfer
     */
    protected function createFileSystemQueryTransfer($fileName)
    {
        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemQueryTransfer->setPath($fileName);

        return $fileSystemQueryTransfer;
    }
}
