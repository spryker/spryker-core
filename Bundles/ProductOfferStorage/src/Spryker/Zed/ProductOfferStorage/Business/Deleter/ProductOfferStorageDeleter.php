<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Deleter;

use Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;

class ProductOfferStorageDeleter implements ProductOfferStorageDeleterInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_REFERENCE = 'spy_product_offer.product_offer_reference';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_STORE
     *
     * @var string
     */
    protected const COL_FK_STORE = 'spy_product_offer_store.fk_store';

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface
     */
    protected $productOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     * @param \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface $productOfferStorageReader
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager,
        ProductOfferStorageReaderInterface $productOfferStorageReader
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
        $this->productOfferStorageReader = $productOfferStorageReader;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues(
            $eventTransfers,
            static::COL_PRODUCT_OFFER_REFERENCE,
        );

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

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductOfferStoreEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_FK_PRODUCT_OFFER,
        );
        $storeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_FK_STORE,
        );

        if (!$productOfferIds || !$storeIds) {
            return;
        }

        $productOfferReferencesGroupedByStore = $this->productOfferStorageReader
            ->getProductOfferReferencesGroupedByStore($productOfferIds, $storeIds);

        foreach ($productOfferReferencesGroupedByStore as $store => $productOfferReferences) {
            $this->productOfferStorageEntityManager->deleteProductOfferStorageEntitiesByProductOfferReferences(
                $productOfferReferences,
                $store,
            );
        }
    }
}
