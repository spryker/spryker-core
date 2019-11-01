<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer;

interface CmsSlotStorageToCmsSlotFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCriteriaFilter(CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer): array;
}
