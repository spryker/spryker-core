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
use Spryker\Service\FileSystem\Dependency\Plugin\FileSystemWriterPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceInterface getService
 */
class FileSystemWriterPlugin extends AbstractPlugin implements FileSystemWriterPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        return $this->getService()->markAsPrivate(
            $fileSystemVisibilityTransfer->getFileSystemName(),
            $fileSystemVisibilityTransfer->getPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        return $this->getService()->markAsPublic(
            $fileSystemVisibilityTransfer->getFileSystemName(),
            $fileSystemVisibilityTransfer->getPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return bool
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        return $this->getService()->createDir(
            $fileSystemCreateDirectoryTransfer->getFileSystemName(),
            $fileSystemCreateDirectoryTransfer->getPath(),
            $fileSystemCreateDirectoryTransfer->getConfig()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @return bool
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer)
    {
        return $this->getService()->deleteDir(
            $fileSystemDeleteDirectoryTransfer->getFileSystemName(),
            $fileSystemDeleteDirectoryTransfer->getPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return bool
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        return $this->getService()->copy(
            $fileSystemCopyTransfer->getFileSystemName(),
            $fileSystemCopyTransfer->getSourcePath(),
            $fileSystemCopyTransfer->getDestinationPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return bool
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        return $this->getService()->delete(
            $fileSystemDeleteTransfer->getFileSystemName(),
            $fileSystemDeleteTransfer->getPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->getService()->put(
            $fileSystemContentTransfer->getFileSystemName(),
            $fileSystemContentTransfer->getPath(),
            $fileSystemContentTransfer->getContent(),
            $fileSystemContentTransfer->getConfig()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return bool
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        return $this->getService()->rename(
            $fileSystemRenameTransfer->getFileSystemName(),
            $fileSystemRenameTransfer->getPath(),
            $fileSystemRenameTransfer->getNewPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->getService()->update(
            $fileSystemContentTransfer->getFileSystemName(),
            $fileSystemContentTransfer->getPath(),
            $fileSystemContentTransfer->getContent(),
            $fileSystemContentTransfer->getConfig()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->getService()->write(
            $fileSystemContentTransfer->getFileSystemName(),
            $fileSystemContentTransfer->getPath(),
            $fileSystemContentTransfer->getContent(),
            $fileSystemContentTransfer->getConfig()
        );
    }

}
