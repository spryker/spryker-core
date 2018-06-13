<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileReader implements FileReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected $fileContent;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
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
        $fileInfo = $this->repository->getFileByIdFileInfo($idFileInfo);

        if ($fileInfo === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileInfo);
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestByFileId(int $idFile)
    {
        $fileInfo = $this->repository->getLatestFileInfoByIdFile($idFile);

        if ($fileInfo === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileInfo);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createResponseTransfer(FileTransfer $fileTransfer)
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFile($fileTransfer);

        $fileInfoTransfer = $fileTransfer->getFileInfo()[0];
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);
        $fileManagerDataTransfer->setContent(
            $this->fileContent->read($fileInfoTransfer->getStorageFileName())
        );

        return $fileManagerDataTransfer;
    }
}
