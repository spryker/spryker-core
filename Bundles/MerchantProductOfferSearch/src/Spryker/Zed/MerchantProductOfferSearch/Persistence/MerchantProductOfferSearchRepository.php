<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantProfile\Persistence\Map\SpyMerchantProfileTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchPersistenceFactory getFactory()
 */
class MerchantProductOfferSearchRepository extends AbstractRepository implements MerchantProductOfferSearchRepositoryInterface
{
    public const KEY_ABSTRACT_PRODUCT_ID = 'id';
    public const KEY_MERCHANT_NAME = 'name';

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getMerchantProductAbstractDataByProductAbstractIds(array $productAbstractIds): array
    {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productOfferPropelQuery->useSpyMerchantQuery()
                ->useSpyMerchantProfileQuery()
                    ->filterByIsActive(true)
                ->endUse()
            ->endUse()
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, Criteria::INNER_JOIN);

        return $productOfferPropelQuery
            ->select([static::KEY_ABSTRACT_PRODUCT_ID, static::KEY_MERCHANT_NAME])
            ->where(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' IN (' . implode(',', $productAbstractIds) . ')')
            ->withColumn(SpyMerchantTableMap::COL_NAME, static::KEY_MERCHANT_NAME)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::KEY_ABSTRACT_PRODUCT_ID)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->addJoin(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_FK_MERCHANT, SpyMerchantTableMap::COL_ID_MERCHANT, Criteria::INNER_JOIN);

        $data = $productAbstractPropelQuery
            ->where(SpyMerchantTableMap::COL_ID_MERCHANT . ' IN (' . implode(',', $merchantIds) . ')')
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();

        return array_values($data);
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantProfileIds(array $merchantProfileIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->addJoin(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_FK_MERCHANT, SpyMerchantTableMap::COL_ID_MERCHANT, Criteria::INNER_JOIN)
            ->addJoin(SpyMerchantTableMap::COL_ID_MERCHANT, SpyMerchantProfileTableMap::COL_FK_MERCHANT, Criteria::INNER_JOIN);

        $data = $productAbstractPropelQuery
            ->where(SpyMerchantProfileTableMap::COL_ID_MERCHANT_PROFILE . ' IN (' . implode(',', $merchantProfileIds) . ')')
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();

        return array_values($data);
    }

    /**
     * @param int[] $productOfferIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->addJoin(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN);

        $data = $productAbstractPropelQuery
            ->where(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER . ' IN (' . implode(',', $productOfferIds) . ')')
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();

        return array_values($data);
    }
}
