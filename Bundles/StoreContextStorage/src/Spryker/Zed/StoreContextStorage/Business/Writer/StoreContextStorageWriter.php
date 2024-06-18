<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Business\Writer;

use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface;

class StoreContextStorageWriter implements StoreContextStorageWriterInterface
{
    /**
     * @see \Orm\Zed\StoreContext\Persistence\Map\SpyStoreContextTableMap::COL_FK_STORE
     *
     * @var string
     */
    protected const COL_FK_STORE = 'spy_store_context.fk_store';

    /**
     * @var \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface
     */
    protected StoreContextStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface
     */
    protected StoreContextStorageToStoreStorageFacadeInterface $storeStorageFacade;

    /**
     * @param \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface $storeStorageFacade
     */
    public function __construct(
        StoreContextStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        StoreContextStorageToStoreStorageFacadeInterface $storeStorageFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->storeStorageFacade = $storeStorageFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeStoreContextStorageCollectionByStoreEvents(array $eventEntityTransfers): void
    {
        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $storeId = $this->eventBehaviorFacade->getEventTransferForeignKeys(
                [$eventEntityTransfer],
                static::COL_FK_STORE,
            )[0];

            $eventEntityTransfer->setId($storeId);
        }

        $this->storeStorageFacade->writeCollectionByStoreEvents($eventEntityTransfers);
    }
}
