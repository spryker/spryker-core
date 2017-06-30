<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter;

use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Spryker\Service\FileSystem\Dependency\Plugin\FileSystemReaderPluginInterface;
use Spryker\Service\FileSystem\Model\FileSystemReaderInterface;

class FileSystemReader implements FileSystemReaderInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemReaderPluginInterface
     */
    protected $fileSystemReader;

    /**
     * @param \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemReaderPluginInterface $fileSystemReaderPlugin
     */
    public function __construct(FileSystemReaderPluginInterface $fileSystemReaderPlugin)
    {
        $this->fileSystemReader = $fileSystemReaderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer
     */
    public function getMetadata(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getMetadata($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getMimetype($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    public function getTimestamp(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getTimestamp($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return int
     */
    public function getSize(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->getSize($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function isPrivate(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->isPrivate($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->read($fileSystemQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemListTransfer $fileSystemListTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer[]
     */
    public function listContents(FileSystemListTransfer $fileSystemListTransfer)
    {
        return $this->fileSystemReader->listContents($fileSystemListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @return bool
     */
    public function has(FileSystemQueryTransfer $fileSystemQueryTransfer)
    {
        return $this->fileSystemReader->has($fileSystemQueryTransfer);
    }

}
