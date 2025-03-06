<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileContent;

use Generated\Shared\Transfer\FileTransfer;

interface FileContentInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    public function save(FileTransfer $fileTransfer);

    /**
     * @param string $fileName
     * @param string|null $storageName
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete($fileName, ?string $storageName = null);

    /**
     * @param string $fileName
     * @param string|null $storageName
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read($fileName, ?string $storageName = null);
}
