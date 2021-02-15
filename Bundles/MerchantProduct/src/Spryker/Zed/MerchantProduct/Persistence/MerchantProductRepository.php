<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence;

use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
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
                $merchantProductMapper->mapMerchantProductEntityToMerchantProductTransfer(
                    $merchantProductAbstractEntity,
                    new MerchantProductTransfer()
                )
            );
        }

        return $merchantProductCollectionTransfer;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param string[] $concreteSku
     *
     * @return array
     */
    public function getConcreteProductSkuMerchantReferenceMap(array $concreteSku): array
    {
        return $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->select([SpyMerchantTableMap::COL_MERCHANT_REFERENCE, SpyProductTableMap::COL_SKU])
            ->joinMerchant()
            ->useProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterBySku_In($concreteSku)
                ->endUse()
            ->endUse()
            ->find()
            ->toKeyValue(SpyProductTableMap::COL_SKU, SpyMerchantTableMap::COL_MERCHANT_REFERENCE);
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

        return $this->getFactory()->createMerchantProductMapper()->mapMerchantProductEntityToMerchantProductTransfer(
            $merchantProductAbstractEntity,
            new MerchantProductTransfer()
        );
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

        if ($merchantProductCriteriaTransfer->getMerchantProductAbstractIds()) {
            $merchantProductAbstractQuery->filterByIdMerchantProductAbstract_In($merchantProductCriteriaTransfer->getMerchantProductAbstractIds());
        }

        if ($merchantProductCriteriaTransfer->getMerchantIds()) {
            $merchantProductAbstractQuery->filterByFkMerchant_In($merchantProductCriteriaTransfer->getMerchantIds());
        }

        if ($merchantProductCriteriaTransfer->getIdMerchant()) {
            $merchantProductAbstractQuery->filterByFkMerchant($merchantProductCriteriaTransfer->getIdMerchant());
        }

        if ($merchantProductCriteriaTransfer->getProductConcreteIds()) {
            $merchantProductAbstractQuery
                ->useProductAbstractQuery()
                    ->useSpyProductQuery()
                        ->filterByIdProduct_In($merchantProductCriteriaTransfer->getProductConcreteIds())
                    ->endUse()
                ->endUse();
        }

        return $merchantProductAbstractQuery;
    }
}
