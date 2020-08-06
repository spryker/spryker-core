<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchPersistenceFactory getFactory()
 */
class MerchantProductSearchRepository extends AbstractRepository implements MerchantProductSearchRepositoryInterface
{
    protected const KEY_PRODUCT_ABSTRACT_ID = 'id_product_abstract';
    protected const KEY_MERCHANT_NAME = 'merchant_name';
    protected const KEY_MERCHANT_NAMES = 'merchant_names';
    protected const KEY_STORE_NAME = 'store_name';

    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        $merchantProductAbstractPropelQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->filterByFkMerchant_In($merchantIds);

        return $merchantProductAbstractPropelQuery
            ->select([SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }

    /**
     * @param int[] $merchantProductAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantProductAbstractIds(array $merchantProductAbstractIds): array
    {
        $merchantProductAbstractPropelQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->filterByIdMerchantProductAbstract_In($merchantProductAbstractIds);

        return $merchantProductAbstractPropelQuery
            ->select([SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT])
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
                    ->joinSpyStore()
                ->endUse()
                ->filterByIsActive(true)
            ->endUse();

        $merchantData = $merchantProductAbstractPropelQuery
            ->select([static::KEY_PRODUCT_ABSTRACT_ID, static::KEY_MERCHANT_NAME])
            ->withColumn(SpyMerchantTableMap::COL_NAME, static::KEY_MERCHANT_NAME)
            ->withColumn(SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT, static::KEY_PRODUCT_ABSTRACT_ID)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::KEY_STORE_NAME)
            ->orderBy(static::KEY_PRODUCT_ABSTRACT_ID)
            ->find()
            ->getData();

        return $this->getFactory()
            ->createMerchantProductAbstractMapper()
            ->mapProductAbstractMerchantDataToProductAbstractMerchantTransfers($merchantData);
    }
}
