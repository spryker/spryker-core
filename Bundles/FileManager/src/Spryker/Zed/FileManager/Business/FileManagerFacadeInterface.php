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
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function saveFile(FileManagerDataTransfer $fileManagerDataTransfer);

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
     * - Finds latest file version
     * - Returns a file meta info and a file content
     *
     * @api
     *
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestFileVersion($idFile);

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
    public function deleteFile($idFile);

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
     * - Deletes only file info version with a given id
     *
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return bool
     */
    public function deleteFileDirectory($idFileDirectory);

    /**
     * Specification:
     * - Creates a new file info version based on a given file info id
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollbackFile($idFileInfo);

    /**
     * Specification:
     * - Finds a file by idFile
     * - Returns file with localized attributes
     *
     * @api
     *
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFile($idFile);

    /**
     * Specification:
     * - Finds a file info by idFileInfo
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFileInfo($idFileInfo);

    /**
     * Specification:
     * - Finds a directory by idFileDirectory
     *
     * @api
     *
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer|null
     */
    public function findFileDirectory($idFileDirectory);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree();

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @return void
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer);

    /**
     * Specification:
     * - Updates mime types is_allowed field
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateMimeTypeSettings(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer);

    /**
     * Specification:
     * - Finds a mime type
     *
     * @api
     *
     * @param int $idMimeType
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function findMimeType($idMimeType);

    /**
     * Specification:
     * - Creates or updates mime type
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function saveMimeType(MimeTypeTransfer $mimeTypeTransfer);

    /**
     * Specification:
     * - Deletes mime type
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MimeTypeTransfer $mimeTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    public function deleteMimeType(MimeTypeTransfer $mimeTypeTransfer);

    /**
     * Specification:
     * - Finds allowed mime types
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    public function findAllowedMimeTypes();
}
