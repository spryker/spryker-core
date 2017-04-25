<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business\Model\Adapter\Flysystem;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;
use Spryker\Zed\FileSystem\Business\Model\FileSystemWriterInterface;
use Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface;

class FileSystemWriter implements FileSystemWriterInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface $flysystemService
     */
    public function __construct(FileSystemToFlysystemInterface $flysystemService)
    {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        return $this->flysystemService
            ->markAsPrivate(
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
        return $this->flysystemService
            ->markAsPublic(
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
        return $this->flysystemService
            ->createDir(
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
        return $this->flysystemService
            ->deleteDir(
                $fileSystemDeleteDirectoryTransfer->getFileSystemName(),
                $fileSystemDeleteDirectoryTransfer->getPath()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return false|string
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        return $this->flysystemService
            ->copy(
                $fileSystemCopyTransfer->getFileSystemName(),
                $fileSystemCopyTransfer->getPath(),
                $fileSystemCopyTransfer->getNewPath()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return false|string
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        return $this->flysystemService
            ->delete(
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
        return $this->flysystemService
            ->put(
                $fileSystemContentTransfer->getFileSystemName(),
                $fileSystemContentTransfer->getPath(),
                $fileSystemContentTransfer->getContent(),
                $fileSystemContentTransfer->getConfig()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return false|string
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        return $this->flysystemService
            ->rename(
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
        return $this->flysystemService
            ->update(
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
        return $this->flysystemService
            ->write(
                $fileSystemContentTransfer->getFileSystemName(),
                $fileSystemContentTransfer->getPath(),
                $fileSystemContentTransfer->getContent(),
                $fileSystemContentTransfer->getConfig()
            );
    }

}
