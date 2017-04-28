<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\Dependency\Plugin\FileSystemStreamPluginInterface;
use Spryker\Service\FileSystem\Model\FileSystemStreamInterface;

class FileSystemStream implements FileSystemStreamInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemStreamPluginInterface
     */
    protected $fileSystemStreamPlugin;

    /**
     * @param \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemStreamPluginInterface $fileSystemStreamPlugin
     */
    public function __construct(FileSystemStreamPluginInterface $fileSystemStreamPlugin)
    {
        $this->fileSystemStreamPlugin = $fileSystemStreamPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStreamPlugin->putStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed|false
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer)
    {
        return $this->fileSystemStreamPlugin->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStreamPlugin->updateStream($fileSystemStreamTransfer, $stream);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->fileSystemStreamPlugin->writeStream($fileSystemStreamTransfer, $stream);
    }

}
