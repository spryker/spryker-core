<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;

class CmsSlotBlockStorageToCmsSlotBlockFacadeBridge implements CmsSlotBlockStorageToCmsSlotBlockFacadeInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     */
    public function __construct($cmsSlotBlockFacade)
    {
        $this->cmsSlotBlockFacade = $cmsSlotBlockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer {
        return $this->cmsSlotBlockFacade->getCmsSlotBlockCollection($cmsSlotBlockCriteriaTransfer);
    }
}
