<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Reader;

use Generated\Shared\Transfer\PaginationTransfer;

interface CustomerStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param array<int, int> $customerIds
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransferCollection(
        PaginationTransfer $paginationTransfer,
        array $customerIds
    ): array;
}
