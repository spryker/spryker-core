<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface getEntityManager()
 */
class ProductOfferShipmentTypeStorageFacade extends AbstractFacade implements ProductOfferShipmentTypeStorageFacadeInterface
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
    public function writeCollectionByProductOfferShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeStorageWriter()
            ->writeCollectionByProductOfferShipmentTypeEvents($eventEntityTransfers);
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
    public function writeCollectionByProductOfferEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeStorageWriter()
            ->writeCollectionByProductOfferEvents($eventEntityTransfers);
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
    public function writeCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeStorageWriter()
            ->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);
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
    public function writeCollectionByShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeStorageWriter()
            ->writeCollectionByShipmentTypeEvents($eventEntityTransfers);
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
    public function writeCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeStorageWriter()
            ->writeCollectionByShipmentTypeStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferIds = []
    ): array {
        return $this->getFactory()
            ->createProductOfferShipmentTypeStorageReader()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                $filterTransfer,
                $productOfferIds,
            );
    }
}
