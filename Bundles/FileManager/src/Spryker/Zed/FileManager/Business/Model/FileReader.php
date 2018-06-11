<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SpyFileEntityTransfer;
use Generated\Shared\Transfer\SpyFileInfoEntityTransfer;
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
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function read(int $idFileInfo)
    {
        $fileInfo = $this->repository->getFileInfoById($idFileInfo);

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
     * @param \Generated\Shared\Transfer\SpyFileInfoEntityTransfer $fileInfoEntityTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createResponseTransfer(SpyFileInfoEntityTransfer $fileInfoEntityTransfer)
    {
        $fileTransfer = $this->createFileTransfer($fileInfoEntityTransfer->getFile());
        $fileInfoTransfer = $this->createFileInfoTransfer($fileInfoEntityTransfer);

        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFile($fileTransfer);
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);

        $content = $this->fileContent->read($fileInfoEntityTransfer->getStorageFileName());
        $fileManagerDataTransfer->setContent($content);

        return $fileManagerDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyFileEntityTransfer $fileEntityTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(SpyFileEntityTransfer $fileEntityTransfer)
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->fromArray($fileEntityTransfer->toArray(), true);

        return $fileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyFileInfoEntityTransfer $fileInfoEntityTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(SpyFileInfoEntityTransfer $fileInfoEntityTransfer)
    {
        $fileInfoTransfer = new FileInfoTransfer();
        $fileInfoTransfer->fromArray($fileInfoEntityTransfer->toArray(), true);

        return $fileInfoTransfer;
    }
}
