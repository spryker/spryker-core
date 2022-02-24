<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface getEntityManager()
 */
class ProductOfferStorageFacade extends AbstractFacade implements ProductOfferStorageFacadeInterface
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
    public function writeProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStorageWriter()
            ->writeProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfers);
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
    public function deleteProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStorageDeleter()
            ->deleteProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfers);
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
    public function writeProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferStorageWriter()
            ->writeProductOfferStorageCollectionByProductOfferEvents($eventTransfers);
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
    public function deleteProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferStorageDeleter()
            ->deleteProductOfferStorageCollectionByProductOfferEvents($eventTransfers);
    }
}
