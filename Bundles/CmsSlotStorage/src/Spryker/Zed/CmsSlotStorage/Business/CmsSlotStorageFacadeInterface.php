<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries CMS slots with the given CMS slot ids.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function publishCmsSlots(array $cmsSlotIds): void;

    /**
     * Specification:
     * - Returns list of SynchronizationData transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationTransferCollection(
        FilterTransfer $filterTransfer,
        array $cmsSlotStorageIds
    ): array;
}
