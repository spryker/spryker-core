<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Dependency\Facade;

class StoreContextStorageToStoreStorageFacadeBridge implements StoreContextStorageToStoreStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface
     */
    protected $storeStorageFacade;

    /**
     * @param \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface $storeStorageFacade
     */
    public function __construct($storeStorageFacade)
    {
        $this->storeStorageFacade = $storeStorageFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStoreEvents(array $eventTransfers): void
    {
        $this->storeStorageFacade->writeCollectionByStoreEvents($eventTransfers);
    }
}
