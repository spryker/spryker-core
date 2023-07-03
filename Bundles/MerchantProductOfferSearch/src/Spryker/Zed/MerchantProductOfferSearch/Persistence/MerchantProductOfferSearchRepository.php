<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence;

use Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantStoreTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchPersistenceFactory getFactory()
 */
class MerchantProductOfferSearchRepository extends AbstractRepository implements MerchantProductOfferSearchRepositoryInterface
{
    /**
     * @var string
     */
    protected const KEY_ABSTRACT_PRODUCT_ID = 'id';

    /**
     * @var string
     */
    protected const KEY_MERCHANT_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCE = 'reference';

    /**
     * @var string
     */
    protected const KEY_MERCHANT_NAMES = 'names';

    /**
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @var string
     */
    protected const KEY_STORE_NAME = 'storeName';

    /**
     * @module Store
     * @module Merchant
     * @module ProductOffer
     *
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer
     */
    public function getProductAbstractMerchantCollection(
        ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
    ): ProductAbstractMerchantCollectionTransfer {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productAbstractMerchantConditionsTransfer = $productAbstractMerchantCriteriaTransfer->getProductAbstractMerchantConditionsOrFail();
        $productOfferPropelQuery
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productOfferPropelQuery->getNewCriterion(SpyMerchantTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL))
            ->addJoin(SpyMerchantTableMap::COL_ID_MERCHANT, SpyMerchantStoreTableMap::COL_FK_MERCHANT, Criteria::INNER_JOIN)
            ->addJoin(SpyMerchantStoreTableMap::COL_FK_STORE, SpyStoreTableMap::COL_ID_STORE, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, Criteria::INNER_JOIN)
            ->addAnd($productOfferPropelQuery->getNewCriterion(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                $productAbstractMerchantConditionsTransfer->getProductAbstractIds(),
                Criteria::IN,
            ));

        $productOfferPropelQuery = $this->applyProductAbstractMerchantFilters(
            $productOfferPropelQuery,
            $productAbstractMerchantConditionsTransfer,
        );

        $merchantData = $productOfferPropelQuery
            ->select([static::KEY_ABSTRACT_PRODUCT_ID, static::KEY_MERCHANT_NAME, static::KEY_MERCHANT_REFERENCE])
            ->withColumn(SpyMerchantTableMap::COL_NAME, static::KEY_MERCHANT_NAME)
            ->withColumn(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, static::KEY_MERCHANT_REFERENCE)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::KEY_ABSTRACT_PRODUCT_ID)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::KEY_STORE_NAME)
            ->orderBy(static::KEY_ABSTRACT_PRODUCT_ID)
            ->find()
            ->getData();

        $groupedMerchantDataByProductAbstractId = $this->groupMerchantDataByProductAbstractId($merchantData);

        $productAbstractMerchantMapper = $this->getFactory()->createProductAbstractMerchantMapper();
        $productAbstractMerchantCollectionTransfer = new ProductAbstractMerchantCollectionTransfer();

        foreach ($groupedMerchantDataByProductAbstractId as $idProductAbstract => $productAbstractMerchantData) {
            $productAbstractMerchantCollectionTransfer->addProductAbstractMerchant(
                $productAbstractMerchantMapper->mapProductAbstractMerchantDataToProductAbstractMerchantTransfer(
                    $productAbstractMerchantData,
                    new ProductAbstractMerchantTransfer(),
                ),
            );
        }

        return $productAbstractMerchantCollectionTransfer;
    }

    /**
     * @param array<int> $merchantIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->innerJoinSpyProduct()
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productAbstractPropelQuery->getNewCriterion(SpyMerchantTableMap::COL_ID_MERCHANT, $merchantIds, Criteria::IN));

        return $productAbstractPropelQuery
            ->where(SpyProductOfferTableMap::COL_IS_ACTIVE, true)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->innerJoinSpyProduct()
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productAbstractPropelQuery->getNewCriterion(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, $productOfferIds, Criteria::IN));

        return $productAbstractPropelQuery
            ->where(SpyMerchantTableMap::COL_IS_ACTIVE, true)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByProductOfferIds(array $productOfferIds): array
    {
        $productAbstractPropelQuery = $this->getFactory()->getProductAbstractPropelQuery();
        $productAbstractPropelQuery
            ->innerJoinSpyProduct()
            ->addJoin(SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productAbstractPropelQuery->getNewCriterion(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, $productOfferIds, Criteria::IN));

        return $productAbstractPropelQuery
            ->where(SpyMerchantTableMap::COL_IS_ACTIVE, true)
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find()
            ->getData();
    }

    /**
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
            $merchantReference = $productAbstractMerchant[static::KEY_MERCHANT_REFERENCE];
            $storeName = $productAbstractMerchant[static::KEY_STORE_NAME];

            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_NAMES][$storeName][] = $merchantName;
            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_REFERENCES][$storeName][] = $merchantReference;
            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_ABSTRACT_PRODUCT_ID] = $idProductAbstract;
        }

        return $groupedProductAbstractMerchantData;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantConditionsTransfer $productAbstractMerchantConditionsTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyProductAbstractMerchantFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductAbstractMerchantConditionsTransfer $productAbstractMerchantConditionsTransfer
    ): SpyProductOfferQuery {
        if ($productAbstractMerchantConditionsTransfer->getIsProductOfferActive() !== null) {
            $productOfferQuery->filterByIsActive($productAbstractMerchantConditionsTransfer->getIsProductOfferActiveOrFail());
        }

        if ($productAbstractMerchantConditionsTransfer->getProductOfferApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productAbstractMerchantConditionsTransfer->getProductOfferApprovalStatuses());
        }

        return $productOfferQuery;
    }
}
