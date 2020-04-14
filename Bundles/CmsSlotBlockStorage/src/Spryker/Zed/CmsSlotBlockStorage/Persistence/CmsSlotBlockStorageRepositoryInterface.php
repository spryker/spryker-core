<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotBlockStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer[]
     */
    public function getCmsSlotBlockStorageTransfersByCmsSlotBlocks(array $cmsSlotBlockTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotBlockStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByCmsSlotBlockStorageIds(
        FilterTransfer $filterTransfer,
        array $cmsSlotBlockStorageIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollectionByCmsSlotBlockStorageTransfer(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): CmsSlotBlockCollectionTransfer;
}
