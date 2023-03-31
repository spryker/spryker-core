<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Business;

use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageRepositoryInterface getRepository()
 */
class StoreStorageFacade extends AbstractFacade implements StoreStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createStoreStorageWriter()
            ->writeCollectionByStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByLocaleStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createStoreStorageWriter()
            ->writeCollectionByLocaleStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByCurrencyStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createStoreStorageWriter()
            ->writeCollectionByCurrencyStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByCountryStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createStoreStorageWriter()
            ->writeCollectionByCountryStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreStorageSynchronizationDataTransfers(StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer): array
    {
        return $this->getRepository()
            ->getStoreStorageSynchronizationDataTransfers($storeStorageCriteriaTransfer);
    }
}
