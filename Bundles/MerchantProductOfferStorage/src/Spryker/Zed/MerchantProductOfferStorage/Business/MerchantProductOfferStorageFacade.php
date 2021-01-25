<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface getEntityManager()
 */
class MerchantProductOfferStorageFacade extends AbstractFacade implements MerchantProductOfferStorageFacadeInterface
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
    public function writeProductConcreteProductOffersStorageCollectionByMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStorageWriter()
            ->writeCollectionByMerchantEvents($eventTransfers);
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
    public function writeProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStorageWriter()
            ->writeCollectionByProductSkuEvents($eventTransfers);
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
    public function deleteProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConcreteProductOffersStorageDeleter()
            ->deleteCollectionByProductSkuEvents($eventTransfers);
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
    public function writeProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferStorageWriter()
            ->writeCollectionByProductOfferReferenceEvents($eventTransfers);
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
    public function deleteProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductOfferStorageDeleter()
            ->deleteCollectionByProductOfferReferenceEvents($eventTransfers);
    }
}
