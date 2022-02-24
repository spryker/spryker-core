<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade;

class MerchantProductOfferStorageToProductOfferStorageFacadeBridge implements MerchantProductOfferStorageToProductOfferStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface
     */
    protected $productOfferStorageFacade;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface $productOfferStorageFacade
     */
    public function __construct($productOfferStorageFacade)
    {
        $this->productOfferStorageFacade = $productOfferStorageFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void
    {
        $this->productOfferStorageFacade->writeProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $this->productOfferStorageFacade->writeProductOfferStorageCollectionByProductOfferEvents($eventTransfers);
    }
}
