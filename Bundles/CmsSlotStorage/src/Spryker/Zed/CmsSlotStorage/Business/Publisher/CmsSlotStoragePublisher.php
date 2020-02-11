<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Publisher;

use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface;
use Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface;

class CmsSlotStoragePublisher implements CmsSlotStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface
     */
    protected $cmsSlotStorageEntityManager;

    /**
     * @param \Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface $cmsSlotFacade
     * @param \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface $cmsSlotStorageEntityManager
     */
    public function __construct(
        CmsSlotStorageToCmsSlotFacadeInterface $cmsSlotFacade,
        CmsSlotStorageEntityManagerInterface $cmsSlotStorageEntityManager
    ) {
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->cmsSlotStorageEntityManager = $cmsSlotStorageEntityManager;
    }

    /**
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function publishByCmsSlotIds(array $cmsSlotIds): void
    {
        $cmsSlotTransfers = $this->cmsSlotFacade->getCmsSlotsByCriteria(
            (new CmsSlotCriteriaTransfer())->setCmsSlotIds($cmsSlotIds)
        );

        $this->saveCmsSlots($cmsSlotTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer[] $cmsSlotTransfers
     *
     * @return void
     */
    protected function saveCmsSlots(array $cmsSlotTransfers): void
    {
        foreach ($cmsSlotTransfers as $cmsSlotTransfer) {
            $this->cmsSlotStorageEntityManager->saveCmsSlotStorage(
                (new CmsSlotStorageTransfer())->fromArray($cmsSlotTransfer->toArray(), true)
            );
        }
    }
}
