<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitPersistenceFactory getFactory()
 */
class ProductPackagingUnitRepository extends AbstractRepository implements ProductPackagingUnitRepositoryInterface
{
    protected const SKU = 'sku';

    /**
     * @param string $productPackagingUnitTypeName
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        string $productPackagingUnitTypeName
    ): ?ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeName)
            ->findOne();

        if (!$productPackagingUnitTypeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTypeTransfer(
                $productPackagingUnitTypeEntity,
                new ProductPackagingUnitTypeTransfer()
            );
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeById(
        int $idProductPackagingUnitType
    ): ?ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByIdProductPackagingUnitType($idProductPackagingUnitType)
            ->findOne();

        if (!$productPackagingUnitTypeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTypeTransfer(
                $productPackagingUnitTypeEntity,
                new ProductPackagingUnitTypeTransfer()
            );
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        int $idProductPackagingUnitType
    ): int {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->filterByFkProductPackagingUnitType($idProductPackagingUnitType)
            ->count();
    }

    /**
     * @module Product
     *
     * @param string $siblingProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductPackagingUnitLeadProductForPackagingUnit(
        string $siblingProductSku
    ): ?ProductConcreteTransfer {
        $productPackagingUnitEntity = $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->innerJoin('SpyProductPackagingUnit.Product Product')
            ->innerJoinWithLeadProduct()
            ->where('Product.sku = ?', $siblingProductSku)
            ->findOne();

        if ($productPackagingUnitEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductEntityToProductConcreteTransfer(
                $productPackagingUnitEntity->getLeadProduct(),
                new ProductConcreteTransfer()
            );
    }

    /**
     * @param int[] $productPackagingUnitTypeIds
     *
     * @return int[]
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->useProductPackagingUnitTypeQuery()
                ->filterByIdProductPackagingUnitType_In($productPackagingUnitTypeIds)
            ->endUse()
            ->select([SpyProductPackagingUnitTableMap::COL_FK_PRODUCT])
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductPackagingUnit
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitById(
        int $idProductPackagingUnit
    ): ?ProductPackagingUnitTransfer {
        $productPackagingUnitEntity = $this->getProductPackagingUnitCriteria()
            ->filterByIdProductPackagingUnit($idProductPackagingUnit)
            ->find()
            ->getFirst();

        if (!$productPackagingUnitEntity) {
            return null;
        }

        $productPackagingUnitTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTransfer(
                $productPackagingUnitEntity,
                new ProductPackagingUnitTransfer()
            );

        return $productPackagingUnitTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductId(
        int $idProduct
    ): ?ProductPackagingUnitTransfer {
        $productPackagingUnitEntity = $this->getProductPackagingUnitCriteria()
            ->filterByFkProduct($idProduct)
            ->find()
            ->getFirst();

        if (!$productPackagingUnitEntity) {
            return null;
        }

        $productPackagingUnitTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTransfer(
                $productPackagingUnitEntity,
                new ProductPackagingUnitTransfer()
            );

        return $productPackagingUnitTransfer;
    }

    /**
     * @module Product
     *
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductSku(
        string $productSku
    ): ?ProductPackagingUnitTransfer {
        $productPackagingUnitEntity = $this->getProductPackagingUnitCriteria()
            ->useProductQuery()
                ->filterBySku($productSku)
            ->endUse()
            ->find()
            ->getFirst();

        if (!$productPackagingUnitEntity) {
            return null;
        }

        $productPackagingUnitTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingUnitTransfer(
                $productPackagingUnitEntity,
                new ProductPackagingUnitTransfer()
            );

        return $productPackagingUnitTransfer;
    }

    /**
     * @module Product
     *
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer[]
     */
    public function findProductPackagingUnitsByProductSku(
        array $productSkus
    ): array {
        if (!$productSkus) {
            return [];
        }

        $productPackagingUnitEntities = $this->getProductPackagingUnitCriteria()
            ->joinWithProduct()
            ->useProductQuery()
                ->filterBySku_In($productSkus)
                ->withColumn(SpyProductTableMap::COL_SKU, static::SKU)
            ->endUse()
            ->find()
            ->getArrayCopy();

        if (!$productPackagingUnitEntities) {
            return [];
        }

        $productPackagingUnitTransfers = [];
        foreach ($productPackagingUnitEntities as $productPackagingUnitEntity) {
            $sku = $productPackagingUnitEntity->getVirtualColumn(static::SKU);
            $productPackagingUnitTransfers[$sku] = $this->getFactory()
                ->createProductPackagingUnitMapper()
                ->mapProductPackagingUnitTransfer(
                    $productPackagingUnitEntity,
                    new ProductPackagingUnitTransfer()
                );
        }

        return $productPackagingUnitTransfers;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer[]
     */
    public function findSalesOrderItemsByIdSalesOrder(int $idSalesOrder): array
    {
        $salesOrderItemEntities = $this->getFactory()
            ->getSalesOrderItemQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->find();

        $mapper = $this->getFactory()
            ->createProductPackagingUnitMapper();
        $spySalesOrderItemEntityTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $spySalesOrderItemEntityTransfers[] = $mapper
                ->mapSpySalesOrderItemEntityTransfer($salesOrderItemEntity, new SpySalesOrderItemEntityTransfer());
        }

        return $spySalesOrderItemEntityTransfers;
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return string[]
     */
    public function getMappedLeadProductSkusBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        return $this->getFactory()
            ->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->filterByAmountSku(null, Criteria::ISNOTNULL)
            ->filterByAmount(null, Criteria::ISNOTNULL)
            ->select([SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, SpySalesOrderItemTableMap::COL_AMOUNT_SKU])
            ->find()
            ->toKeyValue(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, SpySalesOrderItemTableMap::COL_AMOUNT_SKU);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getMappedProductMeasurementSalesUnits(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemQuery = $this->getFactory()
            ->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->filterByAmountMeasurementUnitName(null, Criteria::ISNOTNULL);

        return $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemEntitiesToProductMeasurementSalesUnitTransfers($salesOrderItemQuery->find());
    }

    /**
     * @uses State
     *
     * @param string $sku
     * @param string[] $reservedStateNames
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateProductPackagingUnitReservation(string $sku, array $reservedStateNames, ?StoreTransfer $storeTransfer = null): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesOrderItemQuery()
            ->groupByAmountSku()
            ->useStateQuery()
                ->filterByName_In($reservedStateNames)
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->innerJoinProcess()
            ->groupByFkOmsOrderProcess()
            ->select([SpySalesOrderItemTableMap::COL_SKU])
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::PROCESS_NAME)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::STATE_NAME)
            ->withColumn(
                sprintf(
                    "CASE WHEN %s = '%s' THEN SUM(%s) ELSE SUM(%s) END",
                    SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
                    $sku,
                    SpySalesOrderItemTableMap::COL_AMOUNT,
                    SpySalesOrderItemTableMap::COL_QUANTITY
                ),
                SalesOrderItemStateAggregationTransfer::SUM_AMOUNT
            )
            ->condition('sku', 'spy_sales_order_item.sku = ?', $sku)
            ->condition('amount_sku', 'spy_sales_order_item.amount_sku = ?', $sku)
            ->where(['sku', 'amount_sku'], Criteria::LOGICAL_OR);

        if ($storeTransfer !== null) {
            $storeTransfer->requireName();

            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
                ->endUse();
        }

        $salesAggregationTransfers = [];
        foreach ($salesOrderItemQuery->find() as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())
                ->fromArray($salesOrderItemAggregation, true)
                ->setSku($sku);
        }

        return $salesAggregationTransfers;
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitCriteria(): SpyProductPackagingUnitQuery
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->innerJoinWithProductPackagingUnitType();
    }
}
