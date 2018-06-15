<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileDirectorySaver implements FileDirectorySaverInterface
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
     * @var \Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaverInterface
     */
    protected $attributesSaver;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaverInterface $attributesSaver
     */
    public function __construct(
        FileManagerEntityManagerInterface $entityManager,
        FileManagerRepositoryInterface $repository,
        FileDirectoryLocalizedAttributesSaverInterface $attributesSaver
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->attributesSaver = $attributesSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($fileDirectoryTransfer) {
            return $this->executeSaveTransaction($fileDirectoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function executeSaveTransaction(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $this->entityManager->saveFileDirectory($fileDirectoryTransfer);
        $this->attributesSaver->save($fileDirectoryTransfer);

        return $fileDirectoryTransfer->getIdFileDirectory();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function saveFileDirectory(SpyFileDirectory $fileDirectory, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->handleDatabaseTransaction(
            function () use ($fileDirectory, $fileDirectoryTransfer) {
                $fileDirectory->fromArray($fileDirectoryTransfer->toArray());

                $fileDirectory->save();
                $idFileDirectory = $fileDirectory->getIdFileDirectory();
                $this->attributesSaver->saveFileLocalizedAttributes($fileDirectory, $fileDirectoryTransfer);

                return $idFileDirectory;
            },
            $this->queryContainer->getConnection()
        );
    }
}
