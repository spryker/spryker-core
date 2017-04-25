<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 * @method \Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory getFactory()
 */
interface FileSystemFacadeInterface
{

    /**
     * Specification:
     * - Get resource metadata
     * - Return resource metadata transfer, null on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer|null
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Get resource mime type
     * - Return resource mime type, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string|false
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Get resource timestamp
     * - Return resource timestamp, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string|false
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Get resource size
     * - Return resource size, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int|false
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Check if resource has private access rights
     * - Return true if resource has private access rights
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Mark resource with private access rights
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer);

    /**
     * Specification:
     * - Mark resource with public access rights
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer);

    /**
     * Specification:
     * - Create directory with its path
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return string|false
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer);

    /**
     * Specification:
     * - Delete empty directory
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @return bool
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer);

    /**
     * Specification:
     * - Copy file, the destination must not exist
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return bool
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer);

    /**
     * Specification:
     * - Delete file
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return bool
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer);

    /**
     * Specification:
     * - Create a file or update if exists
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * Specification:
     * - Read file
     * - Return file content, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string|false
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * Specification:
     * - Create a file or update if exists
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return bool
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer);

    /**
     * Specification:
     * - Update an existing file
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * Specification:
     * - Write a new file
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * Specification:
     * - Create a file or update if exists using stream
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * Specification:
     * - Retrieve stream for a file
     * - Return a read-stream for the path, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed|false
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer);

    /**
     * Specification:
     * - Update an existing file using a stream
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * Specification:
     * - Write a new file using a stream
     * - Return true on success, false on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * Specification:
     * - List contents under a path
     * - Return array of FileSystemResourceTransfer objects located under given path
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer);

    /**
     * Specification:
     * - Check if resource exists
     * - Return true if resource exist, false otherwise
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer);

}
