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

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerBusinessFactory getFactory()
 */
interface FileManagerFacadeInterface
{
    /**
     * Specification:
     * - Saves file info
     * - Uploads file content
     * - Creates a new file version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer);

    /**
     * Specification:
     * - Saves file directory info
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function saveDirectory(FileDirectoryTransfer $fileDirectoryTransfer);

    /**
     * Specification:
     * - Deletes all file info
     * - Deletes all file versions and content
     *
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function delete($idFile);

    /**
     * Specification:
     * - Deletes only file info version with a given id
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo);

    /**
     * Specification:
     * - Rolls back latest file info version to a specified file info version
     * - Creates a copy of a specified file info version
     *
     * @api
     *
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollback($idFile, $idFileInfo);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($idFileInfo);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
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
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer);
}
