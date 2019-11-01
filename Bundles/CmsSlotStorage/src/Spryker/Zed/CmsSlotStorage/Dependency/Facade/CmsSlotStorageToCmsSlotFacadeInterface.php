<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Dependency\Facade;

use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotStorageToCmsSlotFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getFilteredCmsSlots(FilterTransfer $filterTransfer): array;

    /**
     * @param int[] $cmsSlotIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCmsSlotIds(array $cmsSlotIds): array;
}
