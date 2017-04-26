<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter\Flysystem;

use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemResourceMetadataTransfer;
use Generated\Shared\Transfer\FileSystemResourceTransfer;
use Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemInterface;
use Spryker\Service\FileSystem\Model\FileSystemReaderInterface;

class FileSystemReader implements FileSystemReaderInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemInterface $flysystemService
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
     * @return bool
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
