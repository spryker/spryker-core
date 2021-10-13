<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Plugin\FileSystem;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemWriterPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceInterface getService()
 */
class FileSystemWriterPlugin extends AbstractPlugin implements FileSystemWriterPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return void
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->getService()->markAsPrivate(
            $fileSystemVisibilityTransfer->getFileSystemNameOrFail(),
            $fileSystemVisibilityTransfer->getPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return void
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->getService()->markAsPublic(
            $fileSystemVisibilityTransfer->getFileSystemNameOrFail(),
            $fileSystemVisibilityTransfer->getPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return void
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        $this->getService()->createDir(
            $fileSystemCreateDirectoryTransfer->getFileSystemNameOrFail(),
            $fileSystemCreateDirectoryTransfer->getPathOrFail(),
            $fileSystemCreateDirectoryTransfer->getConfig()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @return void
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer)
    {
        $this->getService()->deleteDir(
            $fileSystemDeleteDirectoryTransfer->getFileSystemNameOrFail(),
            $fileSystemDeleteDirectoryTransfer->getPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return void
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        $this->getService()->copy(
            $fileSystemCopyTransfer->getFileSystemNameOrFail(),
            $fileSystemCopyTransfer->getSourcePathOrFail(),
            $fileSystemCopyTransfer->getDestinationPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return void
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        $this->getService()->delete(
            $fileSystemDeleteTransfer->getFileSystemNameOrFail(),
            $fileSystemDeleteTransfer->getPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return void
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        $this->getService()->rename(
            $fileSystemRenameTransfer->getFileSystemNameOrFail(),
            $fileSystemRenameTransfer->getPathOrFail(),
            $fileSystemRenameTransfer->getNewPathOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return void
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->getService()->write(
            $fileSystemContentTransfer->getFileSystemNameOrFail(),
            $fileSystemContentTransfer->getPathOrFail(),
            $fileSystemContentTransfer->getContentOrFail(),
            $fileSystemContentTransfer->getConfig()
        );
    }
}
