<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductAbstractStoreProductConcretePageSearchUnpublishListener extends AbstractProductConcretePageSearchListener
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
        $foreignKeysPerAbstractProducts = $this->getFactory()->getEventBehaviorFacade()->getGroupedEventTransferForeignKeysByForeignKey(
            $eventTransfers,
            SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT
        );
        $productAbstractStoreMap = $this->filterGroupedForeignKeysByKey(
            $foreignKeysPerAbstractProducts,
            SpyProductAbstractStoreTableMap::COL_FK_STORE
        );
        $productAbstractStoreMap = $this->convertStoresPerAbstractProductsToStoreNames($productAbstractStoreMap);

        $this->getFacade()->unpublishProductConcretePageSearches($productAbstractStoreMap);
    }

    /**
     * @param array $storesPerAbstractProducts
     *
     * @return array
     */
    protected function convertStoresPerAbstractProductsToStoreNames(array $storesPerAbstractProducts): array
    {
        $storeNameByIdMap = $this->getStoreNameByIdMap();

        foreach ($storesPerAbstractProducts as &$storesPerAbstractProduct) {
            foreach ($storesPerAbstractProduct as &$store) {
                if (array_key_exists($store, $storeNameByIdMap)) {
                    $store = $storeNameByIdMap[$store];
                }
            }
        }

        return $storesPerAbstractProducts;
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

    /**
     * @param array $foreignKeys
     * @param string $filterKey
     *
     * @return array ['foreignKey' => ['filterKeyValue1', 'filterKeyValue2', ...]]
     */
    protected function filterGroupedForeignKeysByKey(array $foreignKeys, string $filterKey): array
    {
        $resultForeignKeys = [];
        foreach ($foreignKeys as $foreignKey => $relatedForeignKeys) {
            foreach ($relatedForeignKeys as $relatedForeignKey) {
                $resultForeignKeys[$foreignKey][] = $relatedForeignKey[$filterKey];
            }
        }

        return $resultForeignKeys;
    }
}
