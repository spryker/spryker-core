<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\Model\FileSystemStreamInterface;
use Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemStreamPluginInterface;

class FileSystemStream implements FileSystemStreamInterface
{
    /**
     * @var \Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemStreamPluginInterface
     */
    protected $fileSystemStream;

    /**
     * @param \Spryker\Service\FileSystemExtension\Dependency\Plugin\FileSystemStreamPluginInterface $fileSystemStreamPlugin
     */
    public function __construct(FileSystemStreamPluginInterface $fileSystemStreamPlugin)
    {
        $this->fileSystemStream = $fileSystemStreamPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer)
    {
        return $this->fileSystemStream->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return void
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        $this->fileSystemStream->writeStream($fileSystemStreamTransfer, $stream);
    }
}
