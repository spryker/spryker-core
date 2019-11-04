<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Reader;

use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;

interface CmsSlotStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer
     * @param int[] $cmsSlotStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationTransferCollection(
        CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer,
        array $cmsSlotStorageIds
    ): array;
}
