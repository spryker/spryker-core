<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Dependency\Service;

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

interface FileManagerToFileSystemServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return int
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed                                               $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed                                               $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed                                               $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);
}
