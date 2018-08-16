<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectoryTree;

use ArrayObject;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileDirectoryTreeReader implements FileDirectoryTreeReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     */
    public function __construct(FileManagerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree()
    {
        $nodesWithoutPosition = new ArrayObject();
        $fileDirectoryTreeTransfer = new FileDirectoryTreeTransfer();
        $rootFileDirectories = $this->repository->getFileDirectories();

        foreach ($rootFileDirectories as $fileDirectoryTransfer) {
            $fileDirectoryTreeNodeTransfer = $this->getFileDirectoryTreeNodeRecursively($fileDirectoryTransfer);

            if ($fileDirectoryTransfer->getPosition() === null) {
                $nodesWithoutPosition[] = $fileDirectoryTreeNodeTransfer;
                continue;
            }

            $fileDirectoryTreeTransfer->addNode($fileDirectoryTreeNodeTransfer);
        }

        foreach ($nodesWithoutPosition as $item) {
            $fileDirectoryTreeTransfer->getNodes()->append($item);
        }

        return $fileDirectoryTreeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer
     */
    protected function getFileDirectoryTreeNodeRecursively(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectoryTreeNodeTransfer = new FileDirectoryTreeNodeTransfer();
        $fileDirectoryTreeNodeTransfer->setFileDirectory($fileDirectoryTransfer);
        $childrenFileDirectoryEntities = $this->repository->getFileDirectories($fileDirectoryTransfer->getIdFileDirectory());

        foreach ($childrenFileDirectoryEntities as $childrenFileDirectoryEntity) {
            $childrenFileDirectoryTreeNodeTransfer = $this->getFileDirectoryTreeNodeRecursively($childrenFileDirectoryEntity);
            $fileDirectoryTreeNodeTransfer->addChild($childrenFileDirectoryTreeNodeTransfer);
        }

        return $fileDirectoryTreeNodeTransfer;
    }
}
