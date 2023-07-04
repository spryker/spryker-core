<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Business\ProductOfferServicePointStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface getRepository()
 */
class ProductOfferServicePointStorageFacade extends AbstractFacade implements ProductOfferServicePointStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServiceEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByProductOfferServiceEventsWriter()
            ->writeProductOfferServiceStorageCollectionByProductOfferServiceEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServicePublishEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByProductOfferServiceEventsWriter()
            ->writeProductOfferServiceStorageCollectionByProductOfferServicePublishEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByProductOfferEventsWriter()
            ->writeProductOfferServiceStorageCollectionByProductOfferEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByProductOfferEventsWriter()
            ->writeProductOfferServiceStorageCollectionByProductOfferStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServiceEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByServicePointEventsWriter()
            ->writeProductOfferServiceStorageCollectionByServiceEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByServicePointEventsWriter()
            ->writeProductOfferServiceStorageCollectionByServicePointEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferServiceStorageByServicePointEventsWriter()
            ->writeProductOfferServiceStorageCollectionByServicePointStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferServiceIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferServiceStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $productOfferServiceIds = []): array
    {
        return $this->getFactory()
            ->createProductOfferServiceStorageReader()
            ->getProductOfferServiceStorageSynchronizationDataTransfers($filterTransfer, $productOfferServiceIds);
    }
}
