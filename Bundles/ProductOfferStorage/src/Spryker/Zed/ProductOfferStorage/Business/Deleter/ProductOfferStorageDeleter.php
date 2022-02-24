<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Deleter;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;

class ProductOfferStorageDeleter implements ProductOfferStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface
     */
    protected $productOfferStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);

        if (!$productOfferReferences) {
            return;
        }

        $this->deleteProductOfferStorageEntitiesByProductOfferReferences($productOfferReferences);
    }

    /**
     * @param array<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void
    {
        $this->productOfferStorageEntityManager->deleteProductOfferStorageEntitiesByProductOfferReferences(
            $productOfferReferences,
            $storeName,
        );
    }
}
