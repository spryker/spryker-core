<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Dependency;

class CmsSlotGuiToCmsSlotFacadeBridge implements CmsSlotGuiToCmsSlotFacadeInterface
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
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function activateByIdCmsSlot(int $idCmsSlot): void
    {
        $this->cmsSlotFacade->activateByIdCmsSlot($idCmsSlot);
    }

    /**
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function deactivateByIdCmsSlot(int $idCmsSlot): void
    {
        $this->cmsSlotFacade->deactivateByIdCmsSlot($idCmsSlot);
    }
}
