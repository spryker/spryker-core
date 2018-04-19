<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\Facade;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface FileManagerGuiToFileManagerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function saveFile(FileManagerSaveRequestTransfer $saveRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readLatestFileVersion($idFile);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function deleteFile($idFile);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo);

    /**
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteFileDirectory($idFileDirectory);

    /**
     * @api
     *
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollbackFile($idFile, $idFileInfo);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readFile($idFileInfo);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function findFileDirectoryTree(LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer);
}
