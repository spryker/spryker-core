<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Tree;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManager\Exception\FileDirectoryNotFoundException;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileDirectoryTreeHierarchyUpdater implements FileDirectoryTreeHierarchyUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $fileManagerQueryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $fileManagerQueryContainer
     */
    public function __construct(FileManagerQueryContainerInterface $fileManagerQueryContainer)
    {
        $this->fileManagerQueryContainer = $fileManagerQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $this->assertFileDirectoryTreeForUpdate($fileDirectoryTreeTransfer);

        $this->handleDatabaseTransaction(
            function () use ($fileDirectoryTreeTransfer) {
                $this->executeUpdateFileDirectoryTreeHierarchyTransaction($fileDirectoryTreeTransfer);
            }
        );
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
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    protected function executeUpdateFileDirectoryTreeHierarchyTransaction(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $this->persistFileManagerTree($fileDirectoryTreeTransfer);
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
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     * @param int|null                                             $fkParentFileDirectory
     *
     * @return void
     */
    protected function persistFileManagerTree(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer, $fkParentFileDirectory = null)
    {
        foreach ($fileDirectoryTreeTransfer->getNodes() as $fileDirectoryTreeNodeTransfer) {
            $this->persistFileDirectoryTreeNodeRecursively($fileDirectoryTreeNodeTransfer, $fkParentFileDirectory);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer
     * @param int                                                      $fkParentFileDirectory
     *
     * @return void
     */
    protected function persistFileDirectoryTreeNodeRecursively(FileDirectoryTreeNodeTransfer $fileDirectoryTreeNodeTransfer, $fkParentFileDirectory)
    {
        $fileDirectoryTransfer = $fileDirectoryTreeNodeTransfer->getFileDirectory();
        $fileDirectoryEntity = $this->getFileDirectoryEntity($fileDirectoryTransfer);
        $fileDirectoryEntity = $this->setFileDirectoryEntityChanges($fileDirectoryEntity, $fileDirectoryTransfer, $fkParentFileDirectory);
        $fileDirectoryEntity->save();

        foreach ($fileDirectoryTreeNodeTransfer->getChildren() as $childFileDirectoryTreeNodeTransfer) {
            $this->persistFileDirectoryTreeNodeRecursively($childFileDirectoryTreeNodeTransfer, $fileDirectoryEntity->getIdFileDirectory());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileDirectoryNotFoundException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    protected function getFileDirectoryEntity(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectoryEntity = $this->fileManagerQueryContainer
            ->queryFileDirectoryById($fileDirectoryTransfer->getIdFileDirectory())
            ->findOne();

        if (!$fileDirectoryEntity) {
            throw new FileDirectoryNotFoundException(
                sprintf(
                    'File directory entity not found with ID %d.',
                    $fileDirectoryTransfer->getIdFileDirectory()
                )
            );
        }

        return $fileDirectoryEntity;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $fileDirectoryEntity
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     * @param int $fkParentNavigationNode
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectoryEntity
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer  $fileDirectoryTransfer
     * @param int                                               $fkParentFileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    protected function setFileDirectoryEntityChanges(SpyFileDirectory $fileDirectoryEntity, FileDirectoryTransfer $fileDirectoryTransfer, $fkParentFileDirectory)
    {
        $fileDirectoryEntity
            ->setPosition($fileDirectoryTransfer->getPosition())
            ->setFkParentFileDirectory($fkParentFileDirectory);

        return $fileDirectoryEntity;
    }
}
