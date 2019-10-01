<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;

interface CmsSlotStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotStorageTransfer $cmsSlotStorageTransfer
     *
     * @return void
     */
    public function saveCmsSlotStorage(CmsSlotStorageTransfer $cmsSlotStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CmsSlotStorageTransfer $cmsSlotStorageTransfer
     *
     * @return void
     */
    public function deleteCmsSlotStorage(CmsSlotStorageTransfer $cmsSlotStorageTransfer): void;
}
