<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileContent\FileContentInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileReader implements FileReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
     */
    protected $fileContent;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface $fileContent
     */
    public function __construct(FileManagerRepositoryInterface $repository, FileContentInterface $fileContent)
    {
        $this->repository = $repository;
        $this->fileContent = $fileContent;
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFileByIdFile(int $idFile)
    {
        $fileTransfer = $this->repository->getFileByIdFile($idFile);

        if ($fileTransfer === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileTransfer);
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFileByIdFileInfo(int $idFileInfo)
    {
        $fileTransfer = $this->repository->getFileByIdFileInfo($idFileInfo);

        if ($fileTransfer === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileTransfer, $idFileInfo);
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestByFileId(int $idFile)
    {
        $fileTransfer = new FileTransfer();
        $fileInfoTransfer = $this->repository->getLatestFileInfoByIdFile($idFile);

        if ($fileInfoTransfer === null) {
            return new FileManagerDataTransfer();
        }

        $fileTransfer->addFileInfo($fileInfoTransfer);

        return $this->createResponseTransfer($fileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int|null $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createResponseTransfer(FileTransfer $fileTransfer, ?int $idFileInfo = null)
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFile($fileTransfer);
        $fileInfoTransfer = $this->getRequestedFileInfo($fileTransfer, $idFileInfo);
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);
        $fileManagerDataTransfer->setContent(
            $this->fileContent->read($fileInfoTransfer->getStorageFileName())
        );

        return $fileManagerDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int|null $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer|null
     */
    protected function getRequestedFileInfo(FileTransfer $fileTransfer, ?int $idFileInfo = null)
    {
        if ($idFileInfo === null) {
            return $fileTransfer->getFileInfo()[0] ?? null;
        }

        foreach ($fileTransfer->getFileInfo() as $fileInfoTransfer) {
            if ($fileInfoTransfer->getIdFileInfo() === $idFileInfo) {
                return $fileInfoTransfer;
            }
        }

        return null;
    }
}
