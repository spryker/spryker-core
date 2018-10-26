<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectoryTree;

use Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Spryker\Zed\FileManager\Exception\FileDirectoryNotFoundException;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileDirectoryTreeHierarchyUpdater implements FileDirectoryTreeHierarchyUpdaterInterface
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
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     */
    public function __construct(FileManagerEntityManagerInterface $entityManager, FileManagerRepositoryInterface $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $this->assertFileDirectoryTreeForUpdate($fileDirectoryTreeTransfer);

        $this->getTransactionHandler()->handleTransaction(
            function () use ($fileDirectoryTreeTransfer) {
                return $this->executeUpdateFileDirectoryTreeHierarchyTransaction($fileDirectoryTreeTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return bool
     */
    protected function executeUpdateFileDirectoryTreeHierarchyTransaction(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        foreach ($fileDirectoryTreeTransfer->getNodes() as $fileDirectoryTreeNodeTransfer) {
            $this->persistFileDirectoryTreeNodeRecursively($fileDirectoryTreeNodeTransfer);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    protected function assertFileDirectoryTreeForUpdate(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $fileDirectoryTreeTransfer->requireNodes();

        foreach ($fileDirectoryTreeTransfer->getNodes() as $fileDirectoryTreeNodeTransfer) {
            $this->assertFileDirectoryTreeNodeRecursively($fileDirectoryTreeNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer
     *
     * @return void
     */
    protected function assertFileDirectoryTreeNodeRecursively(FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer)
    {
        $fileDirectoryTreeNodeTransfer
            ->requireFileDirectory()
            ->getFileDirectory()
            ->requireIdFileDirectory();

        foreach ($fileDirectoryTreeNodeTransfer->getChildren() as $childFileDirectoryTreeNodeTransfer) {
            $this->assertFileDirectoryTreeNodeRecursively($childFileDirectoryTreeNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer
     * @param int|null $fkParentFileDirectory
     *
     * @return void
     */
    protected function persistFileDirectoryTreeNodeRecursively(FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer, ?int $fkParentFileDirectory = null)
    {
        $fileDirectoryTransfer = $this->getFileDirectoryTransfer(
            $fileDirectoryTreeNodeTransfer->getFileDirectory()->getIdFileDirectory()
        );
        $fileDirectoryTransfer->setPosition($fileDirectoryTransfer->getPosition());
        $fileDirectoryTransfer->setFkParentFileDirectory($fkParentFileDirectory);

        $this->entityManager->saveFileDirectory($fileDirectoryTransfer);

        foreach ($fileDirectoryTreeNodeTransfer->getChildren() as $childFileDirectoryTreeNodeTransfer) {
            $this->persistFileDirectoryTreeNodeRecursively($childFileDirectoryTreeNodeTransfer, $fileDirectoryTransfer->getIdFileDirectory());
        }
    }

    /**
     * @param int $idFileDirectory
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileDirectoryNotFoundException
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    protected function getFileDirectoryTransfer(int $idFileDirectory)
    {
        $fileDirectoryTransfer = $this->repository->getFileDirectory($idFileDirectory);

        if ($fileDirectoryTransfer === null) {
            throw new FileDirectoryNotFoundException(
                sprintf(
                    'File directory entity not found with ID %d.',
                    $idFileDirectory
                )
            );
        }

        return $fileDirectoryTransfer;
    }
}
