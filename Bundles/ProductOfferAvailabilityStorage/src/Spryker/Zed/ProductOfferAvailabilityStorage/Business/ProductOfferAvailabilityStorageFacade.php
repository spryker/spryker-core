<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 */
class ProductOfferAvailabilityStorageFacade extends AbstractFacade implements ProductOfferAvailabilityStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByOmsProductOfferReservationIdEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeCollectionByOmsProductOfferReservationIdEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStockIdEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeCollectionByProductOfferStockIdEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferIdEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeCollectionByProductOfferIdEvents($eventTransfers);
    }
}
