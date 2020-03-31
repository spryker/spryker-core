<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business\Writer;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface;
use Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface;

class MerchantStorageWriter implements MerchantStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface
     */
    protected $merchantStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface
     */
    protected $merchantStorageRepository;

    /**
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface $merchantStorageEntityManager
     * @param \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface $merchantStorageRepository
     */
    public function __construct(
        MerchantStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantStorageToMerchantFacadeInterface $merchantFacade,
        MerchantStorageEntityManagerInterface $merchantStorageEntityManager,
        MerchantStorageRepositoryInterface $merchantStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantFacade = $merchantFacade;
        $this->merchantStorageEntityManager = $merchantStorageEntityManager;
        $this->merchantStorageRepository = $merchantStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$merchantIds) {
            return;
        }

        $this->writeCollectionByMerchantIds($merchantIds);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    protected function writeCollectionByMerchantIds(array $merchantIds): void
    {
        $merchantCriteriaFilterTransfer = (new MerchantCriteriaFilterTransfer())->setMerchantIds($merchantIds);

        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaFilterTransfer);

        $merchantIdsToRemove = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            if (!$merchantTransfer->getIsActive()) {
                $merchantIdsToRemove[] = $merchantTransfer->getIdMerchant();

                continue;
            }

            $this->merchantStorageEntityManager->saveMerchantStorage(
                $this->mapMerchantTransferToStorageTransfer($merchantTransfer, new MerchantStorageTransfer())
            );
        }

        $this->merchantStorageEntityManager->deleteMerchantStorageByMerchantIds($merchantIdsToRemove);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    protected function mapMerchantTransferToStorageTransfer(
        MerchantTransfer $merchantTransfer,
        MerchantStorageTransfer $merchantStorageTransfer
    ): MerchantStorageTransfer {
        $merchantStorageTransfer = $merchantStorageTransfer->fromArray($merchantTransfer->modifiedToArray(), true);

        return $merchantStorageTransfer;
    }
}
