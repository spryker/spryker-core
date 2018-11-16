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

interface FileManagerGuiToFileManagerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function saveFile(FileManagerDataTransfer $fileManagerDataTransfer);

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
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function findFileByIdFile($idFile);

    /**
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
     * @param int $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer|null
     */
    public function findFileDirectory($idFileDirectory);

    /**
     * Specification:
     * - Finds a directory tree
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree();

    /**
     * Specification:
     * - Updates file directory tree hierarchy
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return void
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer);

    /**
     * Specification:
     * - Updates file types is_allowed field
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
