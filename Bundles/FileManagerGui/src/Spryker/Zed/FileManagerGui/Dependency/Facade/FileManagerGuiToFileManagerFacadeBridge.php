<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\Facade;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;

class FileManagerGuiToFileManagerFacadeBridge implements FileManagerGuiToFileManagerFacadeInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    protected $fileManagerFacade;

    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     */
    public function __construct($fileManagerFacade)
    {
        $this->fileManagerFacade = $fileManagerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return int
     */
    public function saveFile(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        return $this->fileManagerFacade->saveFile($fileManagerDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->fileManagerFacade->saveDirectory($fileDirectoryTransfer);
    }

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function deleteFile($idFile)
    {
        return $this->fileManagerFacade->deleteFile($idFile);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo)
    {
        return $this->fileManagerFacade->deleteFileInfo($idFileInfo);
    }

    /**
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteFileDirectory($idFileDirectory)
    {
        return $this->fileManagerFacade->deleteFileDirectory($idFileDirectory);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollbackFile($idFileInfo)
    {
        $this->fileManagerFacade->rollbackFile($idFileInfo);
    }

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFile($idFile)
    {
        return $this->fileManagerFacade->findFileByIdFile($idFile);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFileInfo($idFileInfo)
    {
        return $this->fileManagerFacade->findFileByIdFileInfo($idFileInfo);
    }

    /**
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer|null
     */
    public function findFileDirectory($idFileDirectory)
    {
        return $this->fileManagerFacade->findFileDirectory($idFileDirectory);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileDirectoryTree()
    {
        return $this->fileManagerFacade->findFileDirectoryTree();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer)
    {
        return $this->fileManagerFacade->updateFileDirectoryTreeHierarchy($fileDirectoryTreeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateMimeTypeSettings(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer)
    {
        $this->fileManagerFacade->updateMimeTypeSettings($mimeTypeCollectionTransfer);
    }

    /**
     * @api
     *
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function findMimeType($idMimeType)
    {
        return $this->fileManagerFacade->findMimeType($idMimeType);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->fileManagerFacade->saveMimeType($mimeTypeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function deleteMimeType(MimeTypeTransfer $mimeTypeTransfer)
    {
        return $this->fileManagerFacade->deleteMimeType($mimeTypeTransfer);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes()
    {
        return $this->fileManagerFacade->findAllowedMimeTypes();
    }
}
