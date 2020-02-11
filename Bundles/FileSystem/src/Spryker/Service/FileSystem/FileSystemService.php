<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

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
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\FileSystem\FileSystemConfig getConfig()
 * @method \Spryker\Service\FileSystem\FileSystemServiceFactory getFactory()
 */
class FileSystemService extends AbstractService implements FileSystemServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->getMetadata($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->getMimeType($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return int|null
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->getTimestamp($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return int
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->getSize($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->isPrivate($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->markAsPrivate($fileSystemVisibilityTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPublic(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->markAsPublic($fileSystemVisibilityTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->createDirectory($fileSystemCreateDirectoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function deleteDirectory(FileSystemDeleteDirectoryTransfer $fileSystemDeleteDirectoryTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->deleteDirectory($fileSystemDeleteDirectoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->copy($fileSystemCopyTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemDeleteTransfer $fileSystemDeleteTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete(FileSystemDeleteTransfer $fileSystemDeleteTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->delete($fileSystemDeleteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->put($fileSystemContentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->read($fileSystemQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->rename($fileSystemRenameTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function update(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->update($fileSystemContentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        $this->getFactory()
            ->createFileSystemWriter()
            ->write($fileSystemContentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        $this->getFactory()
            ->createFileSystemStream()
            ->putStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer)
    {
        return $this->getFactory()
            ->createFileSystemStream()
            ->readStream($fileSystemStreamTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        $this->getFactory()
            ->createFileSystemStream()
            ->updateStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        $this->getFactory()
            ->createFileSystemStream()
            ->writeStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->listContents($fileSystemListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getFactory()
            ->createFileSystemReader()
            ->has($fileSystemQueryTransfer);
    }
}
