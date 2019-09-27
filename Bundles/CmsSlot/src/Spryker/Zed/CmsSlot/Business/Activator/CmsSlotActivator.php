<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Activator;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotException;
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
        $cmsSlotTransfer = $this->getCmsSlotById($idCmsSlot);

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
        $cmsSlotTransfer = $this->getCmsSlotById($idCmsSlot);

        $cmsSlotTransfer->setIsActive(false);

        $this->cmsSlotEntityManager->updateCmsSlot($cmsSlotTransfer);
    }

    /**
     * @param int $idCmsSlot
     *
     * @throws \Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotException
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    protected function getCmsSlotById(int $idCmsSlot): CmsSlotTransfer
    {
        $cmsSlotTransfer = $this->cmsSlotRepository->findCmsSlotById($idCmsSlot);

        if (!$cmsSlotTransfer) {
            throw new MissingCmsSlotException(
                sprintf(
                    'CMS Slot with id "%d" not found.',
                    $idCmsSlot
                )
            );
        }

        return $cmsSlotTransfer;
    }
}
