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
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
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
            return $availabilityEntity;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityBySkuAndStore(
        string $sku,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($sku)
            ->findOne();

        if ($availabilityEntity === null) {
            return $availabilityEntity;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityByIdProductAbstractAndStore(
        int $idProductAbstract,
        StoreTransfer $storeTransfer
    ): ?ProductAbstractAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityAbstractEntity = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->useProductAbstractQuery()
                ->filterByIdProductAbstract($idProductAbstract)
                ->useSpyProductQuery(null, Criteria::INNER_JOIN)
                    ->useStockProductQuery(null, Criteria::LEFT_JOIN)
                        ->leftJoinStock()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, ProductAbstractAvailabilityTransfer::SKU)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY, ProductAbstractAvailabilityTransfer::AVAILABILITY)
            ->withColumn('GROUP_CONCAT(' . SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK)
            ->withColumn('COALESCE(SUM(' . SpyStockProductTableMap::COL_QUANTITY . '), 0)', ProductAbstractAvailabilityTransfer::STOCK_QUANTITY)
            ->withColumn(
                "COALESCE(SUM(" . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . "), 0)",
                ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY
            )->addJoin(
                SpyProductTableMap::COL_SKU,
                SpyOmsProductReservationTableMap::COL_SKU,
                Criteria::LEFT_JOIN
            )->groupByAbstractSku()
            ->select([SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU])
            ->findOne();

        if ($availabilityAbstractEntity === null) {
            return $availabilityAbstractEntity;
        }

        return (new ProductAbstractAvailabilityTransfer())
            ->fromArray($availabilityAbstractEntity, true);
    }
}
