<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchPersistenceFactory getFactory()
 */
class MerchantProductSearchRepository extends AbstractRepository implements MerchantProductSearchRepositoryInterface
{
    protected const KEY_ABSTRACT_PRODUCT_ID = 'id';
    protected const KEY_MERCHANT_NAME = 'name';
    protected const KEY_MERCHANT_NAMES = 'names';
    protected const KEY_MERCHANT_REFERENCES = 'references';
    protected const KEY_STORE_NAME = 'storeName';

    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        $merchantProductAbstractPropelQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->useMerchantQuery()
                ->filterByIsActive(true)
            ->endUse()
            ->filterByFkMerchant_In($merchantIds);

        return $merchantProductAbstractPropelQuery
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }

    /**
     * @module Store
     * @module Merchant
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        $merchantProductAbstractPropelQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->useMerchantQuery()
                ->useSpyMerchantStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
                ->filterByIsActive(true)
            ->endUse();

        $merchantData = $merchantProductAbstractPropelQuery
            ->select([static::KEY_ABSTRACT_PRODUCT_ID, static::KEY_MERCHANT_NAME])
            ->withColumn(SpyMerchantTableMap::COL_NAME, static::KEY_MERCHANT_NAME)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::KEY_ABSTRACT_PRODUCT_ID)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::KEY_STORE_NAME)
            ->orderBy(static::KEY_ABSTRACT_PRODUCT_ID)
            ->find()
            ->getData();

        $groupedMerchantDataByProductAbstractId = $this->groupMerchantDataByProductAbstractId($merchantData);
        $productAbstractMerchantTransfers = [];

        foreach ($groupedMerchantDataByProductAbstractId as $idProductAbstract => $productAbstractMerchantData) {
            $productAbstractMerchantTransfers[] = $this->getFactory()
                ->createMerchantProductAbstractMapper()
                ->mapProductAbstractMerchantDataToProductAbstractMerchantTransfer(
                    $productAbstractMerchantData,
                    new ProductAbstractMerchantTransfer()
                );
        }

        return $productAbstractMerchantTransfers;
    }

    /**
     * @phpstan-param array<int, mixed> $merchantData
     *
     * @phpstan-return array<int, mixed>
     *
     * @param array $merchantData
     *
     * @return array
     */
    protected function groupMerchantDataByProductAbstractId(array $merchantData): array
    {
        $groupedProductAbstractMerchantData = [];

        foreach ($merchantData as $productAbstractMerchant) {
            $idProductAbstract = $productAbstractMerchant[static::KEY_ABSTRACT_PRODUCT_ID];
            $merchantName = $productAbstractMerchant[static::KEY_MERCHANT_NAME];
            $storeName = $productAbstractMerchant[static::KEY_STORE_NAME];

            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_NAMES][$storeName][] = $merchantName;
            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_ABSTRACT_PRODUCT_ID] = $idProductAbstract;
        }

        return $groupedProductAbstractMerchantData;
    }
}
