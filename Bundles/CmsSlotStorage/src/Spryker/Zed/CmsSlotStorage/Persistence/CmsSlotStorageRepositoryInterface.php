<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotStorageRepositoryInterface
{
    /**
     * @param string[] $cmsSlotKeys
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[]
     */
    public function getCmsSlotStorageEntitiesByCmsSlotKeys(array $cmsSlotKeys): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotIds
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[]
     */
    public function getFilteredCmsSlotStorageEntities(FilterTransfer $filterTransfer, array $cmsSlotIds): array;
}
