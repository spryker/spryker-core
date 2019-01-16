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
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys(
            $eventTransfers,
            SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT
        );

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_DELETE) {
            $storesPerAbstractProducts = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeysRelated(
                $eventTransfers,
                SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractStoreTableMap::COL_FK_STORE
            );

            $storeNameByIdMap = $this->getStoreNameByIdMap();
            $this->convertStoresPerAbstractProductsToStoreNames($storesPerAbstractProducts, $storeNameByIdMap);
            $storesPerConcreteProducts = $this->getStoresPerConcreteProducts($storesPerAbstractProducts);
            $productIds = $this->getProductIdsFromStoresPerConcreteProducts($storesPerConcreteProducts);

            $this->unpublish($productIds, $storesPerConcreteProducts);
        }

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_CREATE || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_UPDATE) {
            $productIds = $this->getProductIds($productAbstractIds);

            $this->publish($productIds);
        }
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
     * @param array $storeNameByIdMap
     *
     * @return void
     */
    protected function convertStoresPerAbstractProductsToStoreNames(array &$storesPerAbstractProducts, array $storeNameByIdMap): void
    {
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
