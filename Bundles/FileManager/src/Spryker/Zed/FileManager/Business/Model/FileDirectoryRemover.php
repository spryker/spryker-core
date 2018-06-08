<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileDirectoryRemover implements FileDirectoryRemoverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $fileManagerQueryContainer;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @var \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected $fileSystemService;

    /**
     * @var \Spryker\Zed\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $fileManagerQueryContainer
     * @param \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface $fileLoader
     * @param \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Zed\FileManager\FileManagerConfig $config
     */
    public function __construct(
        FileManagerQueryContainerInterface $fileManagerQueryContainer,
        FileLoaderInterface $fileLoader,
        FileManagerToFileSystemServiceInterface $fileSystemService,
        FileManagerConfig $config
    ) {
        $this->fileManagerQueryContainer = $fileManagerQueryContainer;
        $this->fileLoader = $fileLoader;
        $this->fileSystemService = $fileSystemService;
        $this->config = $config;
    }

    /**
     * @param int $idFileDirectory
     *
     * @return boolean
     */
    public function delete($idFileDirectory)
    {
        $fileDirectory = $this->fileManagerQueryContainer->queryFileDirectoryById($idFileDirectory)->findOne();
        $fileParentDirectory = $fileDirectory->getParentFileDirectory();

        return $this->handleDatabaseTransaction(
            function () use ($fileDirectory, $fileParentDirectory) {
                $fileParentDirectory === null ?
                    $this->deleteDirectoryFiles($fileDirectory) :
                    $this->moveDirectoryFiles($fileDirectory, $fileParentDirectory);

                $fileSystemDeleteDirectoryTransfer = new FileSystemDeleteDirectoryTransfer();
                $fileSystemDeleteDirectoryTransfer->setFileSystemName($this->config->getStorageName());
                $fileSystemDeleteDirectoryTransfer->setPath($fileDirectory->getIdFileDirectory());

                $fileSystemQueryTransfer = new FileSystemQueryTransfer();
                $fileSystemQueryTransfer->setFileSystemName($this->config->getStorageName());
                $fileSystemQueryTransfer->setPath($fileDirectory->getIdFileDirectory());

                if ($this->fileSystemService->has($fileSystemQueryTransfer)) {
                    $this->fileSystemService->deleteDirectory($fileSystemDeleteDirectoryTransfer);
                }

                $fileDirectory->delete();
            },
            $this->fileManagerQueryContainer->getConnection()
        );
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     *
     * @return void
     */
    protected function deleteDirectoryFiles(SpyFileDirectory $fileDirectory)
    {
        foreach ($fileDirectory->getSpyFiles() as $file) {
            $file->delete();
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileParentDirectory
     *
     * @return void
     */
    protected function moveDirectoryFiles(SpyFileDirectory $fileDirectory, SpyFileDirectory $fileParentDirectory)
    {
        foreach ($fileDirectory->getSpyFiles() as $file) {
            foreach ($file->getSpyFileInfos() as $fileInfo) {
                $fileSystemRenameTransfer = new FileSystemRenameTransfer();
                $fileSystemRenameTransfer->setFileSystemName($this->config->getStorageName());
                $fileSystemRenameTransfer->setPath($fileInfo->getStorageFileName());
                $fileInfo->getFile()->setFileDirectory($fileParentDirectory);
                $newPath = $this->fileLoader->buildFilename($fileInfo);
                $fileSystemRenameTransfer->setNewPath($newPath);
                $fileInfo->setStorageFileName($newPath);

                $this->fileSystemService->rename($fileSystemRenameTransfer);
            }

            $file->setFileDirectory($fileParentDirectory);
            $file->save();
        }
    }
}
