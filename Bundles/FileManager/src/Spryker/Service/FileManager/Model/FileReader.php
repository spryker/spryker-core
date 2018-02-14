<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridgeInterface;
use Spryker\Service\FileManager\FileManagerServiceConfig;

class FileReader implements FileReaderInterface
{
    /**
     * @var \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridgeInterface
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
     * @param \Spryker\Service\FileManager\FileManagerServiceConfig $config
     */
    public function __construct(FileManagerToFileSystemBridgeInterface $fileSystem, FileManagerServiceConfig $config)
    {
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
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
