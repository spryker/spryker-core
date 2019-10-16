<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Mapper;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;

interface CmsSlotStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotStorageTransfer $cmsSlotStorageTransfer
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    public function mapCmsSlotTransferToCmsSlotStorageTransfer(
        CmsSlotStorageTransfer $cmsSlotStorageTransfer,
        CmsSlotTransfer $cmsSlotTransfer
    ): CmsSlotStorageTransfer;
}
