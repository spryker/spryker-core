<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\Facade;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTypeCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

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
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollbackFile($idFile, $idFileInfo)
    {
        $this->fileManagerFacade->rollbackFile($idFile, $idFileInfo);
    }

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFile($idFileInfo)
    {
        return $this->fileManagerFacade->readFile($idFileInfo);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileDirectoryTree(LocaleTransfer $localeTransfer = null)
    {
        return $this->fileManagerFacade->findFileDirectoryTree($localeTransfer);
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
     * @param \Generated\Shared\Transfer\FileTypeCollectionTransfer $fileTypeCollectionTransfer
     *
     * @return void
     */
    public function updateFileTypeSettings(FileTypeCollectionTransfer $fileTypeCollectionTransfer)
    {
        $this->fileManagerFacade->updateFileTypeSettings($fileTypeCollectionTransfer);
    }
}
