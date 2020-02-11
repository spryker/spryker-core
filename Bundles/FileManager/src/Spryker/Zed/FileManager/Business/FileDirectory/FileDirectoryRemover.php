<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectory;

use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Spryker\Zed\FileManager\Business\FileName\FileNameResolverTrait;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileDirectoryRemover implements FileDirectoryRemoverInterface
{
    use TransactionTrait;
    use FileNameResolverTrait;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected $fileSystemService;

    /**
     * @var \Spryker\Zed\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Zed\FileManager\FileManagerConfig $config
     */
    public function __construct(
        FileManagerEntityManagerInterface $entityManager,
        FileManagerRepositoryInterface $repository,
        FileManagerToFileSystemServiceInterface $fileSystemService,
        FileManagerConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->fileSystemService = $fileSystemService;
        $this->config = $config;
    }

    /**
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function delete($idFileDirectory)
    {
        $fileDirectoryTransfer = $this->repository->getFileDirectory($idFileDirectory);
        $idFileDirectory = $fileDirectoryTransfer->getIdFileDirectory();
        $idParentFileDirectory = $fileDirectoryTransfer->getFkParentFileDirectory();

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($idFileDirectory, $idParentFileDirectory) {
                return $this->executeDeleteTransaction($idFileDirectory, $idParentFileDirectory);
            }
        );
    }

    /**
     * @param int $idFileDirectory
     * @param int|null $idParentFileDirectory
     *
     * @return bool
     */
    protected function executeDeleteTransaction(int $idFileDirectory, ?int $idParentFileDirectory = null)
    {
        $idParentFileDirectory === null ?
            $this->deleteDirectoryFiles($idFileDirectory) :
            $this->moveDirectoryFiles($idFileDirectory, $idParentFileDirectory);

        $fileSystemDeleteDirectoryTransfer = new FileSystemDeleteDirectoryTransfer();
        $fileSystemDeleteDirectoryTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemDeleteDirectoryTransfer->setPath((string)$idFileDirectory);

        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName($this->config->getStorageName());
        $fileSystemQueryTransfer->setPath((string)$idFileDirectory);

        if ($this->fileSystemService->has($fileSystemQueryTransfer)) {
            $this->fileSystemService->deleteDirectory($fileSystemDeleteDirectoryTransfer);
        }

        return $this->entityManager->deleteDirectory($idFileDirectory);
    }

    /**
     * @param int $idFileDirectory
     *
     * @return void
     */
    protected function deleteDirectoryFiles(int $idFileDirectory)
    {
        $this->performDirectoryFilesDeletion($idFileDirectory);

        foreach ($this->repository->getFileDirectories($idFileDirectory) as $childFileDirectory) {
            $this->deleteDirectoryFiles($childFileDirectory->getIdFileDirectory());
        }
    }

    /**
     * @param int $idFileDirectory
     *
     * @return void
     */
    protected function performDirectoryFilesDeletion(int $idFileDirectory)
    {
        foreach ($this->repository->getDirectoryFiles($idFileDirectory) as $fileTransfer) {
            $this->entityManager->deleteFile($fileTransfer);
        }
    }

    /**
     * @param int $idFileDirectory
     * @param int $idParentFileDirectory
     *
     * @return void
     */
    protected function moveDirectoryFiles(int $idFileDirectory, int $idParentFileDirectory)
    {
        $this->performDirectoryFilesMove($idFileDirectory, $idParentFileDirectory);

        foreach ($this->repository->getFileDirectories($idFileDirectory) as $childFileDirectory) {
            $this->moveDirectoryFiles($childFileDirectory->getIdFileDirectory(), $idParentFileDirectory);
        }
    }

    /**
     * @param int $idFileDirectory
     * @param int $idParentFileDirectory
     *
     * @return void
     */
    protected function performDirectoryFilesMove(int $idFileDirectory, int $idParentFileDirectory)
    {
        $directoryFiles = $this->repository->getDirectoryFiles($idFileDirectory);

        foreach ($directoryFiles as $fileTransfer) {
            foreach ($fileTransfer->getFileInfo() as $fileInfoTransfer) {
                $fileSystemRenameTransfer = new FileSystemRenameTransfer();
                $fileSystemRenameTransfer->setFileSystemName($this->config->getStorageName());
                $fileSystemRenameTransfer->setPath($fileInfoTransfer->getStorageFileName());
                $newPath = $this->buildFilename($fileInfoTransfer, $idParentFileDirectory);
                $fileSystemRenameTransfer->setNewPath($newPath);
                $fileInfoTransfer->setStorageFileName($newPath);

                $this->fileSystemService->rename($fileSystemRenameTransfer);
            }

            $fileTransfer->setFkFileDirectory($idParentFileDirectory);
            $this->entityManager->saveFile($fileTransfer);
        }
    }

    /**
     * @return string
     */
    protected function getFileNameVersionDelimiter()
    {
        return $this->config->getFileNameVersionDelimiter();
    }
}
