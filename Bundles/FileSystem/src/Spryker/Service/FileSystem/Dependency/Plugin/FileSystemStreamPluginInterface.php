<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Dependency\Plugin;

use Generated\Shared\Transfer\FileSystemStreamTransfer;

interface FileSystemStreamPluginInterface
{

    /**
     * Specification:
     * - Create a file or update if exists using stream
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function putStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * Specification:
     * - Retrieve stream for a file
     * - Return a read-stream for the path, throw exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer);

    /**
     * Specification:
     * - Update an existing file using a stream
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function updateStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

    /**
     * Specification:
     * - Write a new file using a stream
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     * @param mixed $stream
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return void
     */
    public function writeStream(FileSystemStreamTransfer $fileSystemStreamTransfer, $stream);

}
