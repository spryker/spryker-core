<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Plugin\FileSystem;

use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemResourceTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemReaderPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceInterface getService()
 */
class FileSystemReaderPlugin extends AbstractPlugin implements FileSystemReaderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->getMimeType(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int|null
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->getTimestamp(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->getSize(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->isPrivate(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->has(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->getService()->read(
            $fileSystemQueryTransfer->getFileSystemNameOrFail(),
            $fileSystemQueryTransfer->getPathOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @return array<\Generated\Shared\Transfer\FileSystemResourceTransfer>
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer)
    {
        $flysystemTransferCollection = $this->getService()->listContents(
            $fileSystemListTransfer->getFileSystemNameOrFail(),
            $fileSystemListTransfer->getPathOrFail(),
            $fileSystemListTransfer->getRecursiveOrFail(),
        );

        $collection = [];
        foreach ($flysystemTransferCollection as $flysystemResourceTransfer) {
            $fileSystemResourceTransfer = new FileSystemResourceTransfer();
            $fileSystemResourceTransfer->fromArray($flysystemResourceTransfer->toArray(), true);

            $collection[] = $fileSystemResourceTransfer;
        }

        return $collection;
    }
}
