<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Orm\Zed\OmsProductOfferReservation\Persistence\Map\SpyOmsProductOfferReservationTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper\ProductOfferAvailabilityStorageMapperInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStoragePersistenceFactory getFactory()
 */
class ProductOfferAvailabilityStorageRepository extends AbstractRepository implements ProductOfferAvailabilityStorageRepositoryInterface
{
    /**
     * @param string[] $productOfferStockIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByProductOfferStockIds(array $productOfferStockIds): array
    {
        $productOfferStockPropelQuery = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->filterByIdProductOfferStock_In($productOfferStockIds)
            ->joinSpyProductOffer()
            ->useStockQuery()
                ->useStockStoreQuery()
                    ->joinStore()
                ->endUse()
            ->endUse();

        $productOfferAvailabilityRequestsData = $this->addProductOfferAvailabilityRequestSelectColumns($productOfferStockPropelQuery)
            ->find()
            ->getData();

        return $this->convertProductOfferAvailabilityRequestsDataToTransfers($productOfferAvailabilityRequestsData);
    }

    /**
     * @param int[] $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByProductOfferIds(array $productOfferIds): array
    {
        $productOfferStockPropelQuery = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->filterByFkProductOffer_In($productOfferIds)
            ->joinSpyProductOffer()
            ->useStockQuery()
                ->useStockStoreQuery()
                    ->joinStore()
                ->endUse()
            ->endUse();

        $productOfferAvailabilityRequestsData = $this->addProductOfferAvailabilityRequestSelectColumns($productOfferStockPropelQuery)
            ->find()
            ->getData();

        return $this->convertProductOfferAvailabilityRequestsDataToTransfers($productOfferAvailabilityRequestsData);
    }

    /**
     * @param int[] $omsProductOfferReservationIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByOmsProductOfferReservationIds(array $omsProductOfferReservationIds): array
    {
        $productOfferStockPropelQuery = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->joinSpyProductOffer();

        $productOfferStockPropelQuery->addJoin(SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE, SpyOmsProductOfferReservationTableMap::COL_PRODUCT_OFFER_REFERENCE, Criteria::INNER_JOIN)
            ->addJoin(SpyOmsProductOfferReservationTableMap::COL_FK_STORE, SpyStoreTableMap::COL_ID_STORE, Criteria::INNER_JOIN)
            ->addOr(SpyOmsProductOfferReservationTableMap::COL_ID_OMS_PRODUCT_OFFER_RESERVATION, $omsProductOfferReservationIds, Criteria::IN);

        $productOfferAvailabilityRequestsData = $this->addProductOfferAvailabilityRequestSelectColumns($productOfferStockPropelQuery)
            ->find()
            ->getData();

        return $this->convertProductOfferAvailabilityRequestsDataToTransfers($productOfferAvailabilityRequestsData);
    }

    /**
     * @param string $offerReference
     * @param string $storeName
     *
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage|null
     */
    public function findProductOfferAvailabilityStorageByProductOfferReferenceAndStoreName(
        string $offerReference,
        string $storeName
    ): ?SpyProductOfferAvailabilityStorage {
        return $this->getFactory()
            ->getProductOfferAvailabilityStoragePropelQuery()
            ->filterByProductOfferReference($offerReference)
            ->filterByStore($storeName)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductOfferAvailabilityStorageEntityTransfer[]
     */
    public function getFilteredProductOfferAvailabilityStorageEntityTransfers(FilterTransfer $filterTransfer, array $ids): array
    {
        $productOfferAvailabilityStoragePropelQuery = $this->getFactory()
            ->getProductOfferAvailabilityStoragePropelQuery();

        if ($ids) {
            $productOfferAvailabilityStoragePropelQuery->filterByIdProductOfferAvailabilityStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($productOfferAvailabilityStoragePropelQuery, $filterTransfer)
            ->find();
    }

    /**
     * @phpstan-param array<mixed> $productOfferAvailabilityRequestsData
     *
     * @param array $productOfferAvailabilityRequestsData
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    protected function convertProductOfferAvailabilityRequestsDataToTransfers(array $productOfferAvailabilityRequestsData): array
    {
        $productOfferAvailabilityRequestTransfers = [];

        foreach ($productOfferAvailabilityRequestsData as $productOfferAvailabilityRequestData) {
            $productOfferAvailabilityRequestTransfers[] = $this->getFactory()
                ->createProductOfferAvailabilityStorageMapper()
                ->mapProductOfferAvailabilityRequestDataToRequestTransfer(
                    $productOfferAvailabilityRequestData,
                    new ProductOfferAvailabilityRequestTransfer()
                );
        }

        return $productOfferAvailabilityRequestTransfers;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<mixed>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function addProductOfferAvailabilityRequestSelectColumns(ModelCriteria $query): ModelCriteria
    {
        return $query
            ->withColumn(SpyStoreTableMap::COL_ID_STORE, ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_ID_STORE)
            ->withColumn(SpyStoreTableMap::COL_NAME, ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_STORE_NAME)
            ->withColumn(SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE, ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_PRODUCT_OFFER_REFERENCE)
            ->withColumn(SpyProductOfferTableMap::COL_CONCRETE_SKU, ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_SKU)
            ->select([
                SpyStoreTableMap::COL_NAME => ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_STORE_NAME,
                SpyStoreTableMap::COL_ID_STORE => ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_ID_STORE,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_PRODUCT_OFFER_REFERENCE,
                SpyProductOfferTableMap::COL_CONCRETE_SKU => ProductOfferAvailabilityStorageMapperInterface::COL_ALIAS_SKU,
            ]);
    }
}
