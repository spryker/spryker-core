<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Business\Writer;

use Generated\Shared\Transfer\StoreConditionsTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryStoreTableMap;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyStoreTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleStoreTableMap;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface;
use Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface;

class StoreStorageWriter implements StoreStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface
     */
    protected $storeStorageEntityManager;

    /**
     * @param \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface $storeStorageEntityManager
     */
    public function __construct(
        StoreStorageToStoreFacadeInterface $storeFacade,
        StoreStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        StoreStorageEntityManagerInterface $storeStorageEntityManager
    ) {
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->storeStorageEntityManager = $storeStorageEntityManager;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStoreEvents(array $eventTransfers): void
    {
        $storeIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$storeIds) {
            return;
        }

        $this->writeCollectionByStoreIds($storeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByLocaleStoreEvents(array $eventTransfers): void
    {
        $storeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            SpyLocaleStoreTableMap::COL_FK_STORE,
        );

        if (!$storeIds) {
            return;
        }

        $this->writeCollectionByStoreIds($storeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByCurrencyStoreEvents(array $eventTransfers): void
    {
        $storeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            SpyCurrencyStoreTableMap::COL_FK_STORE,
        );

        if (!$storeIds) {
            return;
        }

        $this->writeCollectionByStoreIds($storeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByCountryStoreEvents(array $eventTransfers): void
    {
        $storeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            SpyCountryStoreTableMap::COL_FK_STORE,
        );

        if (!$storeIds) {
            return;
        }

        $this->writeCollectionByStoreIds($storeIds);
    }

    /**
     * @param array<int> $storeIds
     *
     * @return void
     */
    protected function writeCollectionByStoreIds(array $storeIds): void
    {
        $storeCriteriaTransfer = (new StoreCriteriaTransfer())
            ->setStoreConditions(
                (new StoreConditionsTransfer())
                    ->setStoreIds($storeIds),
            );

        $storeCollectionTransfer = $this->storeFacade->getStoreCollection($storeCriteriaTransfer);

        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeStorageTransfer = (new StoreStorageTransfer())->fromArray($storeTransfer->toArray(), true);
            $this->storeStorageEntityManager->updateStoreStorage($storeStorageTransfer);
        }
        $this->updateStoreListStorage();
    }

    /**
     * @return void
     */
    protected function updateStoreListStorage(): void
    {
        $storeNames = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getNameOrFail();
        }

        $this->storeStorageEntityManager->updateStoreList($storeNames);
    }
}
