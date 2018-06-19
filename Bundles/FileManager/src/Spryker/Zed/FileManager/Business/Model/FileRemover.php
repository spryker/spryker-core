<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileRemover implements FileRemoverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

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
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     */
    public function __construct(FileManagerRepositoryInterface $repository, FileManagerEntityManagerInterface $entityManager, FileContentInterface $fileContent)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->fileContent = $fileContent;
    }

    /**
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo(int $idFileInfo)
    {
        $fileInfoTransfer = (new FileInfoTransfer())->setIdFileInfo($idFileInfo);

        return $this->getTransactionHandler()->handleTransaction(function () use ($fileInfoTransfer) {
            return $this->executeDeleteFileInfoTransaction($fileInfoTransfer);
        });
    }

    /**
     * @param int $idFile
     *
     * @return bool
     */
    public function delete(int $idFile)
    {
        $fileTransfer = (new FileTransfer())->setIdFile($idFile);

        return $this->getTransactionHandler()->handleTransaction(function () use ($fileTransfer) {
            return $this->executeDeleteFileTransaction($fileTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return bool
     */
    protected function executeDeleteFileTransaction(FileTransfer $fileTransfer)
    {
        $fileTransfer = $this->repository->getFileByIdFile($fileTransfer->getIdFile());

        foreach ($fileTransfer->getFileInfo() as $fileInfoTransfer) {
            $this->fileContent->delete($fileInfoTransfer->getStorageFileName());
            $this->entityManager->deleteFileInfo($fileInfoTransfer);
        }

        return $this->entityManager->deleteFile($fileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return bool
     */
    protected function executeDeleteFileInfoTransaction(FileInfoTransfer $fileInfoTransfer)
    {
        $this->fileContent->delete($fileInfoTransfer->getStorageFileName());

        return $this->entityManager->deleteFileInfo($fileInfoTransfer);
    }
}
