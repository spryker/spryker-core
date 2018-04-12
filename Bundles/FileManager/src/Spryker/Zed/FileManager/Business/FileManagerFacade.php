<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerBusinessFactory getFactory()
 */
class FileManagerFacade extends AbstractFacade implements FileManagerFacadeInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function save(FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileManagerSaveRequestTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileDirectoryTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function readLatestFileVersion($idFile)
    {
        return $this->getFactory()->createFileReader()->readLatestByFileId($idFile);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function delete($idFile)
    {
        return $this->getFactory()->createFileRemover()->delete($idFile);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function deleteFileInfo($idFileInfo)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function rollback($idFile, $idFileInfo)
    {
        $this->getFactory()->createFileRollback()->rollback($idFile, $idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function read($idFileInfo)
    {
        return $this->getFactory()->createFileReader()->read($idFileInfo);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer|null
     */
    public function findFileDirectoryTree(LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createFileDirectoryTreeReader()
            ->findFileDirectoryTree($localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $this->getFactory()
            ->createFileDirectoryTreeHierarchyUpdater()
            ->updateFileDirectoryTreeHierarchy($fileDirectoryTreeTransfer);
    }
}
