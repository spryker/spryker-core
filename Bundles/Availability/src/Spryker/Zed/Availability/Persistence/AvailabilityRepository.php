<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityRepository extends AbstractRepository implements AvailabilityRepositoryInterface
{
    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityByIdProductConcreteAndStore(
        int $idProductConcrete,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->addJoin(SpyAvailabilityTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->where(sprintf('%s = %d', SpyProductTableMap::COL_ID_PRODUCT, $idProductConcrete))
            ->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityBySkuAndStore(
        string $concreteSku,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($concreteSku)
            ->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityBySkuAndStore(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ?ProductAbstractAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityAbstractEntityArray = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByAbstractSku($abstractSku)
            ->useSpyAvailabilityQuery()
                ->filterByFkStore($storeTransfer->getIdStore())
            ->endUse()
            ->select([
                SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU,
            ])->withColumn(SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, ProductAbstractAvailabilityTransfer::SKU)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY, ProductAbstractAvailabilityTransfer::AVAILABILITY)
            ->withColumn('GROUP_CONCAT(' . SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK)
            ->groupByAbstractSku()
            ->findOne();

        if ($availabilityAbstractEntityArray === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
                $availabilityAbstractEntityArray,
                new ProductAbstractAvailabilityTransfer()
            );
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException
     *
     * @return int
     */
    public function findIdProductAbstractAvailabilityBySku(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): int {
        $idAvailabilityAbstract = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->select(SpyAvailabilityAbstractTableMap::COL_ID_AVAILABILITY_ABSTRACT)
            ->findOne();

        if ($idAvailabilityAbstract === null) {
            throw new AvailabilityAbstractNotFoundException(
                'You cannot update concrete availability without updating abstract availability first'
            );
        }

        return $idAvailabilityAbstract;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getCalculatedProductAbstractAvailabilityBySkuAndStore(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer {
        $availabilityAbstractEntityArray = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->filterBySku($abstractSku)
            ->groupByIdProductAbstract()
            ->useSpyProductQuery(null, Criteria::INNER_JOIN)
                ->useStockProductQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinStock()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, ProductAbstractAvailabilityTransfer::SKU)
            ->withColumn('GROUP_CONCAT(' . SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK)
            ->withColumn('COALESCE(SUM(' . SpyStockProductTableMap::COL_QUANTITY . '), 0)', ProductAbstractAvailabilityTransfer::STOCK_QUANTITY)
            ->withColumn(
                "COALESCE(SUM(" . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . "), 0)",
                ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY
            )->addJoin(
                SpyProductTableMap::COL_SKU,
                SpyOmsProductReservationTableMap::COL_SKU,
                Criteria::LEFT_JOIN
            )->where(sprintf('(%s = %d OR %s IS NULL)', SpyOmsProductReservationTableMap::COL_FK_STORE, $storeTransfer->getIdStore(), SpyOmsProductReservationTableMap::COL_FK_STORE))
            ->select([SpyProductAbstractTableMap::COL_SKU])
            ->findOne();

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
                $availabilityAbstractEntityArray,
                new ProductAbstractAvailabilityTransfer()
            );
    }

    /**
     * @param string $concreteSku
     *
     * @return string|null
     */
    public function getAbstractSkuFromProductConcrete(string $concreteSku): ?string
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductQuery()
                ->filterBySku($concreteSku)
            ->endUse()
            ->select(SpyProductAbstractTableMap::COL_SKU)
            ->findOne();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string|null
     */
    public function getProductConcreteSkuByConcreteId(int $idProductConcrete): ?string
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductQuery()
                ->filterByIdProduct($idProductConcrete)
            ->endUse()
            ->select(SpyProductTableMap::COL_SKU)
            ->findOne();
    }
}
