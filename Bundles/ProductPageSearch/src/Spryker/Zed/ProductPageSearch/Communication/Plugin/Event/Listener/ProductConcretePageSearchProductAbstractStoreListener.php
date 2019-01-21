<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFacade()
 */
class ProductConcretePageSearchProductAbstractStoreListener extends AbstractProductConcretePageSearchListener
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->preventTransaction();

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_DELETE) {
            $this->processProductAbstractStoreDeleteEvent($eventTransfers);
        }

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_CREATE
            || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_UPDATE
        ) {
            $this->processProductAbstractStoreUpdateAndCreateEvent($eventTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    protected function processProductAbstractStoreDeleteEvent(array $eventTransfers): void
    {
        $storesPerAbstractProducts = $this->getFactory()->getEventBehaviorFacade()->getGroupedEventTransferRelatedForeignKeys(
            $eventTransfers,
            SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT,
            SpyProductAbstractStoreTableMap::COL_FK_STORE
        );
        $this->convertStoresPerAbstractProductsToStoreNames($storesPerAbstractProducts);

        $this->getFacade()->unpublishProductConcretesByAbstractProductsAndStores($storesPerAbstractProducts);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    protected function processProductAbstractStoreUpdateAndCreateEvent(array $eventTransfers): void
    {
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys(
            $eventTransfers,
            SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT
        );
        $productIds = $this->getProductIds($productAbstractIds);

        $this->publish($productIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function getProductIds(array $productAbstractIds): array
    {
        $productIds = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productIds = array_merge(
                $productIds,
                $this->getFactory()
                    ->getProductFacade()
                    ->findProductConcreteIdsByAbstractProductId($idProductAbstract)
            );
        }

        return $productIds;
    }

    /**
     * @param array $storesPerAbstractProducts
     *
     * @return array
     */
    protected function getStoresPerConcreteProducts(array $storesPerAbstractProducts): array
    {
        $storesPerConcreteProducts = [];
        foreach ($storesPerAbstractProducts as $idProductAbstract => $stores) {
            $productConcreteIds = $this->getFactory()
                ->getProductFacade()
                ->findProductConcreteIdsByAbstractProductId($idProductAbstract);

            foreach ($productConcreteIds as $productConcreteId) {
                $storesPerConcreteProducts[$productConcreteId] = $stores;
            }
        }

        return $storesPerConcreteProducts;
    }

    /**
     * @param array $storesPerConcreteProducts
     *
     * @return int[]
     */
    protected function getProductIdsFromStoresPerConcreteProducts(array $storesPerConcreteProducts): array
    {
        return array_keys($storesPerConcreteProducts);
    }

    /**
     * @param array $storesPerAbstractProducts
     *
     * @return void
     */
    protected function convertStoresPerAbstractProductsToStoreNames(array &$storesPerAbstractProducts): void
    {
        $storeNameByIdMap = $this->getStoreNameByIdMap();

        foreach ($storesPerAbstractProducts as &$storesPerAbstractProducts) {
            foreach ($storesPerAbstractProducts as &$store) {
                $store = $storeNameByIdMap[$store];
            }
        }
    }

    /**
     * @return array
     */
    protected function getStoreNameByIdMap(): array
    {
        $storeTransfers = $this->getFactory()->getStoreFacade()->getAllStores();

        $idStoreMap = [];
        foreach ($storeTransfers as $storeTransfer) {
            $idStoreMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $idStoreMap;
    }
}
