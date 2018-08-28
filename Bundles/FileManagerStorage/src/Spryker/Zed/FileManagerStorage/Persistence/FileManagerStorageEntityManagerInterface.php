<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Generated\Shared\Transfer\FileStorageTransfer;

interface FileManagerStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return void
     */
    public function saveFileStorage(FileStorageTransfer $fileStorageTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return bool
     */
    public function deleteFileStorage(FileStorageTransfer $fileStorageTransfer);
}
