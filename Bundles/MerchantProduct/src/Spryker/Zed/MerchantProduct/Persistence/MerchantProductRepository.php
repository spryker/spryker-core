<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence;

use Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductPersistenceFactory getFactory()
 */
class MerchantProductRepository extends AbstractRepository implements MerchantProductRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer
    {
        $merchantProductAbstractQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->joinWithMerchant();

        $merchantProductAbstractEntity = $this->applyFilters($merchantProductAbstractQuery, $merchantProductCriteriaTransfer)
            ->findOne();

        if (!$merchantProductAbstractEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($merchantProductAbstractEntity->getMerchant(), new MerchantTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function get(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): MerchantProductCollectionTransfer
    {
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery */
        $merchantProductAbstractQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->leftJoinWithProductAbstract()
            ->useProductAbstractQuery()
                ->leftJoinWithSpyProduct()
            ->endUse();

        $merchantProductAbstractQuery = $this->applyFilters($merchantProductAbstractQuery, $merchantProductCriteriaTransfer);

        $merchantProductAbstractEntities = $merchantProductAbstractQuery->find();

        $merchantProductCollectionTransfer = new MerchantProductCollectionTransfer();
        $merchantProductMapper = $this->getFactory()->createMerchantProductMapper();

        foreach ($merchantProductAbstractEntities as $merchantProductAbstractEntity) {
            $merchantProductCollectionTransfer->addMerchantProduct(
                $merchantProductMapper->mapMerchantProductAbstractEntityToMerchantProductTransfer(
                    $merchantProductAbstractEntity,
                    new MerchantProductTransfer(),
                ),
            );
        }

        return $merchantProductCollectionTransfer;
    }

    /**
     * @param array<string> $concreteSku
     *
     * @return array<string, string>
     */
    public function getConcreteProductSkuMerchantReferenceMap(array $concreteSku): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $concreteProductSkuMerchantReferenceMap */
        $concreteProductSkuMerchantReferenceMap = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->select([SpyMerchantTableMap::COL_MERCHANT_REFERENCE, SpyProductTableMap::COL_SKU])
            ->joinMerchant()
            ->useProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterBySku_In($concreteSku)
                ->endUse()
            ->endUse()
            ->find();

        return $concreteProductSkuMerchantReferenceMap->toKeyValue(SpyProductTableMap::COL_SKU, SpyMerchantTableMap::COL_MERCHANT_REFERENCE);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?MerchantProductTransfer {
        $merchantProductAbstractQuery = $this->getFactory()->getMerchantProductAbstractPropelQuery();
        $merchantProductAbstractQuery = $this->applyFilters($merchantProductAbstractQuery, $merchantProductCriteriaTransfer);

        $merchantProductAbstractEntity = $merchantProductAbstractQuery->findOne();

        if (!$merchantProductAbstractEntity) {
            return null;
        }

        return $this->getFactory()->createMerchantProductMapper()->mapMerchantProductAbstractEntityToMerchantProductTransfer(
            $merchantProductAbstractEntity,
            new MerchantProductTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer
     */
    public function getMerchantProductAbstractCollection(
        MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
    ): MerchantProductAbstractCollectionTransfer {
        $merchantProductAbstractCollectionTransfer = new MerchantProductAbstractCollectionTransfer();
        $merchantProductAbstractQuery = $this->getFactory()->getMerchantProductAbstractPropelQuery();

        $paginationTransfer = $merchantProductAbstractCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $merchantProductAbstractQuery = $this
                ->applyMerchantProductAbstractPagination($merchantProductAbstractQuery, $paginationTransfer);
            $merchantProductAbstractCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createMerchantMapper()
            ->mapMerchantProductAbstractEntitiesToMerchantProductAbstractCollectionTransfer(
                $merchantProductAbstractQuery->find(),
                $merchantProductAbstractCollectionTransfer,
            );
    }

    /**
     * @param array<int> $merchantIds
     *
     * @return array<int>
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
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function applyFilters(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        if ($merchantProductCriteriaTransfer->getIdProductAbstract()) {
            $merchantProductAbstractQuery->filterByFkProductAbstract($merchantProductCriteriaTransfer->getIdProductAbstract());
        }

        if ($merchantProductCriteriaTransfer->getProductAbstractIds()) {
            $merchantProductAbstractQuery->filterByFkProductAbstract_In($merchantProductCriteriaTransfer->getProductAbstractIds());
        }

        if ($merchantProductCriteriaTransfer->getMerchantProductAbstractIds()) {
            $merchantProductAbstractQuery->filterByIdMerchantProductAbstract_In($merchantProductCriteriaTransfer->getMerchantProductAbstractIds());
        }

        if ($merchantProductCriteriaTransfer->getMerchantIds()) {
            $merchantProductAbstractQuery->filterByFkMerchant_In($merchantProductCriteriaTransfer->getMerchantIds());
        }

        if ($merchantProductCriteriaTransfer->getProductConcreteIds()) {
            $merchantProductAbstractQuery
                ->useProductAbstractQuery()
                    ->useSpyProductQuery()
                        ->filterByIdProduct_In($merchantProductCriteriaTransfer->getProductConcreteIds())
                    ->endUse()
                ->endUse();
        }

        if ($merchantProductCriteriaTransfer->getProductConcreteSkus()) {
            $merchantProductAbstractQuery
                ->useProductAbstractQuery()
                    ->useSpyProductQuery()
                        ->filterBySku_In($merchantProductCriteriaTransfer->getProductConcreteSkus())
                    ->endUse()
                ->endUse();
        }

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function applyMerchantProductAbstractPagination(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        PaginationTransfer $paginationTransfer
    ): SpyMerchantProductAbstractQuery {
        $paginationTransfer->setNbResults($merchantProductAbstractQuery->count());
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $merchantProductAbstractQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $merchantProductAbstractQuery;
    }
}
