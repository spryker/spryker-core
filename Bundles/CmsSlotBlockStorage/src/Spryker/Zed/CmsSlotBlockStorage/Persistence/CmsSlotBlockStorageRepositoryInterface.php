<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotBlockStorageRepositoryInterface
{
    /**
     * @param string[] $cmsSlotBlockIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer[]
     */
    public function getCmsSlotBlockStorageTransfersByCmsSlotBlockIds(array $cmsSlotBlockIds): array;

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
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer
     */
    public function getCmsSlotBlockStorageDataTransferByCmsSlotBlockStorageTransfer(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): CmsSlotBlockStorageDataTransfer;
}
