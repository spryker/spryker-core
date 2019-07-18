<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerBusinessFactory getFactory()
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface getRepository()
 */
class FileManagerFacade extends AbstractFacade implements FileManagerFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function saveFile(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        return $this->getFactory()->createFileSaver()->save($fileManagerDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->getFactory()->createFileDirectorySaver()->save($fileDirectoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestFileVersion($idFile)
    {
        return $this->getFactory()->createFileReader()->readLatestByFileId($idFile);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function deleteFile($idFile)
    {
        return $this->getFactory()->createFileRemover()->delete($idFile);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo)
    {
        return $this->getFactory()->createFileRemover()->deleteFileInfo($idFileInfo);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollbackFile($idFileInfo)
    {
        $this->getFactory()->createFileRollback()->rollback($idFileInfo);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFile($idFile)
    {
        return $this->getFactory()->createFileReader()->readFileByIdFile($idFile);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFileInfo($idFileInfo)
    {
        return $this->getFactory()->createFileReader()->readFileByIdFileInfo($idFileInfo);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer|null
     */
    public function findFileDirectory($idFileDirectory)
    {
        return $this->getFactory()->createFileDirectoryReader()->getFileDirectory($idFileDirectory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree()
    {
        return $this->getFactory()
            ->createFileDirectoryTreeReader()
            ->findFileDirectoryTree();
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteFileDirectory($idFileDirectory)
    {
        return $this->getFactory()
            ->createFileDirectoryRemover()
            ->delete($idFileDirectory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateMimeTypeSettings(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        $this->getFactory()
            ->createMimeTypeSaver()
            ->updateIsAllowed($mimeTypeCollectionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function findMimeType($idMimeType)
    {
        return $this->getFactory()
            ->createMimeTypeReader()
            ->findMimeType($idMimeType);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->getFactory()
            ->createMimeTypeSaver()
            ->saveMimeType($mimeTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function deleteMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->getFactory()
            ->createMimeTypeRemover()
            ->deleteMimeType($mimeTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes()
    {
        return $this->getFactory()
            ->createMimeTypeReader()
            ->findAllowedMimeTypes();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $idFiles
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer[]
     */
    public function getFilesByIds(array $idFiles): array
    {
        return $this->getFactory()->createFileReader()->getFilesByIds($idFiles);
    }
}
