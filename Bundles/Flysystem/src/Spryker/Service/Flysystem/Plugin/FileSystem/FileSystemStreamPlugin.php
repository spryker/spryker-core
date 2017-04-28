<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Plugin\FileSystem;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\Dependency\Plugin\FileSystemStreamPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceInterface getService
 */
class FileSystemStreamPlugin extends AbstractPlugin implements FileSystemStreamPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @return bool
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream)
    {
        return $this->getService()->putStream(
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
        return $this->getService()->readStream(
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
        return $this->getService()->updateStream(
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
        return $this->getService()->writeStream(
            $fileSystemStreamTransfer->getFileSystemName(),
            $fileSystemStreamTransfer->getPath(),
            $stream,
            $fileSystemStreamTransfer->getConfig()
        );
    }

}
