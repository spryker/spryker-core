<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
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
    protected const SUM_COLUMN = 'sumAmount';
    protected const SKU_COLUMN = 'sku';
    protected const PROCESS_NAME_COLUMN = 'processName';
    protected const STATE_NAME_COLUMN = 'stateName';

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
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductBySiblingProductSku(
        string $siblingProductSku
    ): ?ProductPackagingLeadProductTransfer {
        $productPackagingUnitEntity = $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->useProductQuery('Product')
                ->filterBySku($siblingProductSku)
            ->endUse()
            ->findOne();

        if ($productPackagingUnitEntity === null || $productPackagingUnitEntity->getLeadProduct() === null) {
            return null;
        }

        $productPackagingLeadProductTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingLeadProductTransfer(
                $productPackagingUnitEntity,
                new ProductPackagingLeadProductTransfer()
            );

        return $productPackagingLeadProductTransfer;
    }

    /**
     * @module ProductPackagingUnit
     *
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
     * @uses State
     *
     * @param string $sku
     * @param string[] $reservedStateNames
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateLeadProductAmountForAllSalesOrderItemsBySku(string $sku, array $reservedStateNames): array
    {
        $salesOrderItemAggregations = $this->getFactory()
            ->getSalesOrderItemQuery()
            ->filterByAmountSku($sku)
            ->joinWithProcess()
            ->joinWithState()
            ->useStateQuery()
                ->filterByName($reservedStateNames, Criteria::IN)
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->groupByFkOmsOrderProcess()
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU, static::SKU_COLUMN)
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, static::PROCESS_NAME_COLUMN)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, static::STATE_NAME_COLUMN)
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', static::SUM_COLUMN)
            ->select([SpySalesOrderItemTableMap::COL_SKU])
            ->find();

        $salesAggregationTransfers = [];
        foreach ($salesOrderItemAggregations as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())->fromArray($salesOrderItemAggregation, true);
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
