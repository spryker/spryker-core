<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Activator;

use Spryker\Zed\CmsSlot\Persistence\CmsSlotEntityManagerInterface;
use Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface;

class CmsSlotActivator implements CmsSlotActivatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface
     */
    protected $cmsSlotRepository;

    /**
     * @var \Spryker\Zed\CmsSlot\Persistence\CmsSlotEntityManagerInterface
     */
    protected $cmsSlotEntityManager;

    /**
     * @param \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface $cmsSlotRepository
     * @param \Spryker\Zed\CmsSlot\Persistence\CmsSlotEntityManagerInterface $cmsSlotEntityManager
     */
    public function __construct(CmsSlotRepositoryInterface $cmsSlotRepository, CmsSlotEntityManagerInterface $cmsSlotEntityManager)
    {
        $this->cmsSlotRepository = $cmsSlotRepository;
        $this->cmsSlotEntityManager = $cmsSlotEntityManager;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function activateByIdCmsSlot(int $idCmsSlot): void
    {
        $cmsSlotTransfer = $this->cmsSlotRepository->findCmsSlotById($idCmsSlot);

        if (!$cmsSlotTransfer) {
            return;
        }

        $cmsSlotTransfer->setIsActive(true);
        $this->cmsSlotEntityManager->updateCmsSlot($cmsSlotTransfer);
    }

    /**
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function deactivateByIdCmsSlot(int $idCmsSlot): void
    {
        $cmsSlotTransfer = $this->cmsSlotRepository->findCmsSlotById($idCmsSlot);

        if (!$cmsSlotTransfer) {
            return;
        }

        $cmsSlotTransfer->setIsActive(false);
        $this->cmsSlotEntityManager->updateCmsSlot($cmsSlotTransfer);
    }
}
