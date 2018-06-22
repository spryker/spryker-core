<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

interface FileManagerStorageRepositoryInterface
{
    /**
     * @param array $fileIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FileTransfer[]
     */
    public function getFilesByIds(array $fileIds);

    /**
     * @param array $fileStorageIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FileStorageTransfer[]
     */
    public function getFileStoragesByIds(array $fileStorageIds);
}
