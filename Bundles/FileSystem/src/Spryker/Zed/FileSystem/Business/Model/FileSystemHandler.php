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
use League\Flysystem\AdapterInterface;
use Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface;

//TODO replace parameters with transfer
//TODO add resource mapper
class FileSystemHandler implements FileSystemHandlerInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface $flysystemService
     */
    public function __construct(
        FileSystemToFlysystemInterface $flysystemService
    ) {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer|null
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $metadata = $this->flysystemService
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->getMetadata($fileSystemQueryTransfer->getPath());

        if (!$metadata) {
            return null;
        }

        $metadataTransfer = new FileSystemResourceMetadataTransfer();
        $metadataTransfer->fromArray($metadata, true);

        $isFile = $this->isFile($metadataTransfer->getType());
        $metadataTransfer->setIsFile($isFile);

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
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->getMimetype($fileSystemQueryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        $visibility = $this->flysystemService
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->getVisibility($fileSystemQueryTransfer->getPath());

        return $visibility === AdapterInterface::VISIBILITY_PRIVATE;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemVisibilityTransfer $fileSystemVisibilityTransfer
     *
     * @return bool
     */
    public function markAsPrivate(FileSystemVisibilityTransfer $fileSystemVisibilityTransfer)
    {
        $visibility = AdapterInterface::VISIBILITY_PUBLIC;
        if ($fileSystemVisibilityTransfer->getIsPrivate()) {
            $visibility = AdapterInterface::VISIBILITY_PRIVATE;
        }

        return $this->flysystemService
            ->getFilesystemByName($fileSystemVisibilityTransfer->getFileSystemName())
            ->setVisibility($fileSystemVisibilityTransfer->getPath(), $visibility);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return false|string
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->getTimestamp($fileSystemQueryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int|false
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->getSize($fileSystemQueryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer
     *
     * @return bool
     */
    public function createDirectory(FileSystemCreateDirectoryTransfer $fileSystemCreateDirectoryTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemCreateDirectoryTransfer->getFileSystemName())
            ->createDir(
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
            ->getFilesystemByName($fileSystemDeleteDirectoryTransfer->getFileSystemName())
            ->deleteDir($fileSystemDeleteDirectoryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemCopyTransfer $fileSystemCopyTransfer
     *
     * @return false|string
     */
    public function copy(FileSystemCopyTransfer $fileSystemCopyTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemCopyTransfer->getFileSystemName())
            ->copy(
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
            ->getFilesystemByName($fileSystemDeleteTransfer->getFileSystemName())
            ->delete($fileSystemDeleteTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->has($fileSystemQueryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return bool
     */
    public function put(FileSystemContentTransfer $fileSystemContentTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemContentTransfer->getFileSystemName())
            ->put(
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
            ->getFilesystemByName($fileSystemQueryTransfer->getFileSystemName())
            ->read($fileSystemQueryTransfer->getPath());
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemRenameTransfer $fileSystemRenameTransfer
     *
     * @return false|string
     */
    public function rename(FileSystemRenameTransfer $fileSystemRenameTransfer)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemRenameTransfer->getFileSystemName())
            ->rename(
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
            ->getFilesystemByName($fileSystemContentTransfer->getFileSystemName())
            ->update(
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
            ->getFilesystemByName($fileSystemContentTransfer->getFileSystemName())
            ->write(
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
            ->getFilesystemByName($fileSystemStreamTransfer->getFileSystemName())
            ->putStream(
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
            ->getFilesystemByName($fileSystemStreamTransfer->getFileSystemName())
            ->readStream($fileSystemStreamTransfer->getPath());
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
            ->getFilesystemByName($fileSystemStreamTransfer->getFileSystemName())
            ->updateStream(
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
            ->getFilesystemByName($fileSystemStreamTransfer->getFileSystemName())
            ->writeStream(
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
            ->getFilesystemByName($fileSystemListTransfer->getFileSystemName())
            ->listContents(
                $fileSystemListTransfer->getPath(),
                $fileSystemListTransfer->getRecursive()
            );

        $results = [];
        foreach ($resourceCollection as $resource) {
            $resourceTransfer = new FileSystemResourceTransfer();
            $resourceTransfer->fromArray($resource);

            $isFile = $this->isFile($resourceTransfer->getType());
            $resourceTransfer->setIsFile($isFile);

            $results[] = $resourceTransfer;
        }

        return $results;
    }

    /**
     * TODO move to adapter
     *
     * @param string $type
     *
     * @return bool
     */
    protected function isFile($type)
    {
        return $type === 'file';
    }

}
