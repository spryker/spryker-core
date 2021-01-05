<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business\Writer;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface;
use Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface;

class MerchantStorageWriter implements MerchantStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\MerchantCategory\Persistence\Map\SpyMerchantCategoryTableMap::COL_FK_MERCHANT
     */
    protected const FK_CATEGORY_MERCHANT = 'spy_merchant_category.fk_merchant';

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToStoreFacadeInterface
     */
    protected $storeFacade;

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
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface $merchantStorageEntityManager
     * @param \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface $merchantStorageRepository
     */
    public function __construct(
        MerchantStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantStorageToMerchantFacadeInterface $merchantFacade,
        MerchantStorageToStoreFacadeInterface $storeFacade,
        MerchantStorageEntityManagerInterface $merchantStorageEntityManager,
        MerchantStorageRepositoryInterface $merchantStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantFacade = $merchantFacade;
        $this->storeFacade = $storeFacade;
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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantCategoryEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, static::FK_CATEGORY_MERCHANT);

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
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setMerchantIds($merchantIds);

        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            foreach ($storeTransfers as $storeTransfer) {
                if ($merchantTransfer->getIsActive() && $this->isMerchantAvailableInStore($merchantTransfer, $storeTransfer)) {
                    $this->merchantStorageEntityManager->saveMerchantStorage(
                        $this->mapMerchantTransferToStorageTransfer($merchantTransfer, new MerchantStorageTransfer()),
                        $storeTransfer
                    );

                    continue;
                }

                $this->merchantStorageEntityManager->deleteMerchantStorage($merchantTransfer->getIdMerchant(), $storeTransfer->getName());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isMerchantAvailableInStore(MerchantTransfer $merchantTransfer, StoreTransfer $storeTransfer): bool
    {
        foreach ($merchantTransfer->getStoreRelation()->getStores() as $merchantStoreTransfer) {
            if ($merchantStoreTransfer->getName() === $storeTransfer->getName()) {
                return true;
            }
        }

        return false;
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
        $merchantStorageTransfer->getMerchantProfile()->getAddressCollection()->exchangeArray(
            $merchantTransfer->getMerchantProfile()->getAddressCollection()->modifiedToArray()
        );

        return $merchantStorageTransfer;
    }
}
