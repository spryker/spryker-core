<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileContent\FileContentInterface;
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
     * @var \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
     */
    protected $fileContent;

    /**
     * @var list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface>
     */
    protected array $filePreDeletePlugins;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface $fileContent
     * @param list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface> $filePreDeletePlugins
     */
    public function __construct(
        FileManagerRepositoryInterface $repository,
        FileManagerEntityManagerInterface $entityManager,
        FileContentInterface $fileContent,
        array $filePreDeletePlugins
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->fileContent = $fileContent;
        $this->filePreDeletePlugins = $filePreDeletePlugins;
    }

    /**
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo(int $idFileInfo)
    {
        $fileInfoTransfer = $this->repository->getFileInfo($idFileInfo);

        if ($fileInfoTransfer === null) {
            return false;
        }

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
        $fileTransfer = $this->repository->getFileByIdFile($fileTransfer->getIdFileOrFail());

        if ($fileTransfer === null) {
            return false;
        }

        $fileCollectionTransfer = $this->executeFilePreDeletePlugins($fileTransfer);
        $fileTransfer = $fileCollectionTransfer->getFiles()->offsetGet(0);

        foreach ($fileTransfer->getFileInfo() as $fileInfoTransfer) {
            $this->fileContent->delete($fileInfoTransfer->getStorageFileNameOrFail(), $fileInfoTransfer->getStorageName());
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
        $this->fileContent->delete($fileInfoTransfer->getStorageFileNameOrFail(), $fileInfoTransfer->getStorageName());

        return $this->entityManager->deleteFileInfo($fileInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    protected function executeFilePreDeletePlugins(FileTransfer $fileTransfer): FileCollectionTransfer
    {
        $fileCollectionTransfer = new FileCollectionTransfer();
        $fileCollectionTransfer->addFile($fileTransfer);

        foreach ($this->filePreDeletePlugins as $filePreDeletePlugin) {
            $fileCollectionTransfer = $filePreDeletePlugin->preDelete($fileCollectionTransfer);
        }

        return $fileCollectionTransfer;
    }
}
