<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Dependency\Service;

use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;

interface FileManagerToFileSystemServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read(FileSystemQueryTransfer $fileSystemQueryTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer): mixed;

    /**
     * @param \Generated\Shared\Transfer\FileSystemQueryTransfer $fileSystemQueryTransfer
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getMimeType(FileSystemQueryTransfer $fileSystemQueryTransfer): string;
}
