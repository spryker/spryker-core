<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence;

use Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;

interface CmsSlotRepositoryInterface
{
    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotById(int $idCmsSlot): ?CmsSlotTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCriteriaFilter(CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer): array;
}
