<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotTransfer;

interface CmsSlotStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return void
     */
    public function saveCmsSlotStorage(CmsSlotTransfer $cmsSlotTransfer): void;

    /**
     * @param int $idCmsSlotStorage
     *
     * @return void
     */
    public function deleteCmsSlotStorageById(int $idCmsSlotStorage): void;
}
