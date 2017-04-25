<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business\Model;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemResourceMetadataTransfer;
use Generated\Shared\Transfer\FileSystemResourceTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;
use Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface;

class FileSystemHandler implements FileSystemHandlerInterface
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
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer|null
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $flysystemMetadataTransfer = $this->flysystemService
            ->getMetadata(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
            );

        if (!$flysystemMetadataTransfer) {
            return null;
        }

        $metadataTransfer = new FileSystemResourceMetadataTransfer();
        $metadataTransfer->fromArray($flysystemMetadataTransfer->toArray(), true);

        return $metadataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getMimetype(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->isPrivate(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
            );
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
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getTimestamp(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int|false
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getSize(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
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
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->has(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
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
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->read(
                $fileSystemQueryTransfer->getFileSystemName(),
                $fileSystemQueryTransfer->getPath()
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

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->flysystemService
            ->putStream(
                $fileSystemStreamTransfer->getFileSystemName(),
                $fileSystemStreamTransfer->getPath(),
                $stream,
                $fileSystemStreamTransfer->getConfig()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return false|mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer)
    {
        return $this->flysystemService
            ->readStream(
                $fileSystemStreamTransfer->getFileSystemName(),
                $fileSystemStreamTransfer->getPath()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->flysystemService
            ->updateStream(
                $fileSystemStreamTransfer->getFileSystemName(),
                $fileSystemStreamTransfer->getPath(),
                $stream,
                $fileSystemStreamTransfer->getConfig()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->flysystemService
            ->writeStream(
                $fileSystemStreamTransfer->getFileSystemName(),
                $fileSystemStreamTransfer->getPath(),
                $stream,
                $fileSystemStreamTransfer->getConfig()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer)
    {
        $resourceCollection = $this->flysystemService
            ->listContents(
                $fileSystemListTransfer->getFileSystemName(),
                $fileSystemListTransfer->getPath(),
                $fileSystemListTransfer->getRecursive()
            );

        $results = [];
        foreach ($resourceCollection as $flysystemResourceTransfer) {
            $resourceTransfer = new FileSystemResourceTransfer();
            $resourceTransfer->fromArray($flysystemResourceTransfer->toArray(), true);

            $results[] = $resourceTransfer;
        }

        return $results;
    }

}
