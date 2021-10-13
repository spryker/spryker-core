<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Reader;

use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $cmsSlotStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationTransferCollection(
        FilterTransfer $filterTransfer,
        array $cmsSlotStorageIds
    ): array;
}
