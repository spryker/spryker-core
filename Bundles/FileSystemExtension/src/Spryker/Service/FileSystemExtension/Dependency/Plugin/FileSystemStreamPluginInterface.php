<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystemExtension\Dependency\Plugin;

use Generated\Shared\Transfer\FileSystemStreamTransfer;

interface FileSystemStreamPluginInterface
{
    /**
     * Specification:
     * - Retrieves stream for a file
     * - Returns a read-stream for the path, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer);

    /**
     * Specification:
     * - Writes a new file using a stream
     * - Returns true on success, throws exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);
}
