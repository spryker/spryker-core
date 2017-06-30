<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;
use Spryker\Service\FileSystem\Dependency\Plugin\FileSystemWriterPluginInterface;
use Spryker\Service\FileSystem\Model\FileSystemWriterInterface;

class FileSystemWriter implements FileSystemWriterInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemWriterPluginInterface
     */
    protected $fileSystemWriter;

    /**
     * @param \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemWriterPluginInterface $fileSystemWriterPlugin
     */
    public function __construct(FileSystemWriterPluginInterface $fileSystemWriterPlugin)
    {
        $this->fileSystemWriter = $fileSystemWriterPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return void
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->fileSystemWriter->markAsPrivate($fileSystemVisibilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return void
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->fileSystemWriter->markAsPublic($fileSystemVisibilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return void
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        $this->fileSystemWriter->createDirectory($fileSystemCreateDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @return void
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer)
    {
        $this->fileSystemWriter->deleteDirectory($fileSystemDeleteDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return void
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        $this->fileSystemWriter->copy($fileSystemCopyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @return void
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        $this->fileSystemWriter->delete($fileSystemDeleteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return void
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->fileSystemWriter->put($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return void
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        $this->fileSystemWriter->rename($fileSystemRenameTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return void
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->fileSystemWriter->update($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return void
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->fileSystemWriter->write($fileSystemContentTransfer);
    }

}
