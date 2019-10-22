<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;

class CmsSlotBlockGuiToCmsSlotBlockFacadeBridge implements CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
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
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $this->cmsSlotBlockFacade->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer
    {
        return $this->cmsSlotBlockFacade->getCmsSlotBlockCollection($idCmsSlotTemplate, $idCmsSlot);
    }
}
