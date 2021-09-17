<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface FileManagerStorageRepositoryInterface
{
    /**
     * @param array $fileIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FileTransfer>
     */
    public function findFilesByIds(array $fileIds);

    /**
     * @param array $fileStorageIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FileStorageTransfer>
     */
    public function findFileStoragesByIds(array $fileStorageIds);

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $fileManagerStorageIds
     *
     * @return array<\Generated\Shared\Transfer\FileStorageTransfer>
     */
    public function getFilteredFileStorageTransfers(FilterTransfer $filterTransfer, array $fileManagerStorageIds = []): array;
}
