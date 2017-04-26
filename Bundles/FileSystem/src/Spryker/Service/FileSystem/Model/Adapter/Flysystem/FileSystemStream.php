<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Adapter\Flysystem;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemInterface;
use Spryker\Service\FileSystem\Model\FileSystemStreamInterface;

class FileSystemStream implements FileSystemStreamInterface
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
     * @return mixed|false
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

}
