<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
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
    public function saveFile(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileManagerDataTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->getFactory()->createFileDirectorySaver()->save($fileDirectoryTransfer);
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
    public function deleteFile($idFile)
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
    public function rollbackFile($idFile, $idFileInfo)
    {
        $this->getFactory()->createFileRollback()->rollback($idFile, $idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function readFile($idFileInfo)
    {
        return $this->getFactory()->createFileReader()->read($idFileInfo);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function findFileDirectoryTree(?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createFileDirectoryTreeReader()
            ->findFileDirectoryTree($localeTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        $this->getFactory()
            ->createFileDirectoryTreeHierarchyUpdater()
            ->updateFileDirectoryTreeHierarchy($fileDirectoryTreeTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function deleteFileDirectory($idFileDirectory)
    {
        $this->getFactory()
            ->createFileDirectoryRemover()
            ->delete($idFileDirectory);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function updateMimeTypeSettings(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        $this->getFactory()
            ->createMimeTypeSaver()
            ->updateIsAllowed($mimeTypeCollectionTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->getFactory()
            ->createMimeTypeSaver()
            ->saveMimeType($mimeTypeTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function deleteMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->getFactory()
            ->createMimeTypeRemover()
            ->deleteMimeType($mimeTypeTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function findAllowedMimeTypes()
    {
        return $this->getFactory()
            ->createMimeTypeReader()
            ->findAllowedMimeTypes();
    }
}
