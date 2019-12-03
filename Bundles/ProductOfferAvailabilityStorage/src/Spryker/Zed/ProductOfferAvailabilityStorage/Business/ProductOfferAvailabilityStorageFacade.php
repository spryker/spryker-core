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
    public function writeProductOfferAvailabilityStorageCollectionByOmsProductReservationKeyEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeProductOfferAvailabilityStorageCollectionByOmsProductReservationKeyEvents($eventTransfers);
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
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferStockKeyEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeProductOfferAvailabilityStorageCollectionByProductOfferStockKeyEvents($eventTransfers);
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
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferAvailabilityStorageWriter()
            ->writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents($eventTransfers);
    }
}
