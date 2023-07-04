<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
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
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
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
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()->createProductOfferStorageWriter()->writeCollectionByMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function filterProductOfferServices(array $productOfferServicesTransfers): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferServiceStorageFilter()
            ->filterProductOfferServices($productOfferServicesTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function filterProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        return $this->getFactory()
            ->createMerchantProductOfferShipmentTypeStorageFilter()
            ->filterProductOfferShipmentTypeCollection($productOfferShipmentTypeCollectionTransfer);
    }
}
