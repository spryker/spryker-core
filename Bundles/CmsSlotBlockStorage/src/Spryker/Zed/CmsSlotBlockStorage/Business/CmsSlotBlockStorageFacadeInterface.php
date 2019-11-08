<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Business;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotBlockStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries CMS slot blocks for the given cmsSlotBlockIds.
     * - Stores data as JSON encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param string[] $cmsSlotBlockIds
     *
     * @return void
     */
    public function publishByCmsSlotBlockIds(array $cmsSlotBlockIds): void;

    /**
     * Specification:
     * - Retrieves CMS slot block transfers filtered by provided FilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer;

    /**
     * Specification:
     * - Queries CMS slot blocks storage entities for the given cmsSlotBlockStorageIds filtered by provided FilterTransfer.
     * - Returns a collection of synchronization data transfers mapped from received storage entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotBlockStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByCmsSlotBlockStorageIds(
        FilterTransfer $filterTransfer,
        array $cmsSlotBlockStorageIds
    ): array;
}
