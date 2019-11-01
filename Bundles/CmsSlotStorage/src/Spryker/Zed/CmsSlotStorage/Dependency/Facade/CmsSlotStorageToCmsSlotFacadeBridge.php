<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer;

class CmsSlotStorageToCmsSlotFacadeBridge implements CmsSlotStorageToCmsSlotFacadeInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Business\CmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @param \Spryker\Zed\CmsSlot\Business\CmsSlotFacadeInterface $cmsSlotFacade
     */
    public function __construct($cmsSlotFacade)
    {
        $this->cmsSlotFacade = $cmsSlotFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCriteriaFilter(CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer): array
    {
        return $this->cmsSlotFacade->getCmsSlotsByCriteriaFilter($cmsSlotCriteriaFilterTransfer);
    }
}
