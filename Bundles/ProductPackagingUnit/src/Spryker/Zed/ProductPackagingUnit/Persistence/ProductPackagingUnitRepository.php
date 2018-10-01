<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitPersistenceFactory getFactory()
 */
class ProductPackagingUnitRepository extends AbstractRepository implements ProductPackagingUnitRepositoryInterface
{
    protected const COL_SUM_AMOUNT = 'SumAmount';

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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        $productPackagingLeadProductEntity = $this->getFactory()
            ->createProductPackagingLeadProductQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->innerJoinWithSpyProduct()
            ->findOne();

        if (!$productPackagingLeadProductEntity) {
            return null;
        }

        $productPackagingLeadProductTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingLeadProductTransfer(
                $productPackagingLeadProductEntity,
                new ProductPackagingLeadProductTransfer()
            );

        return $productPackagingLeadProductTransfer;
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
        $productPackagingLeadProductQuery = $this->getFactory()->createProductPackagingLeadProductQuery();
        $productPackagingLeadProductQuery
            ->useSpyProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterBySku($siblingProductSku)
                ->endUse()
            ->endUse();

        $productPackagingLeadProductEntity = $productPackagingLeadProductQuery
            ->findOne();

        if (!$productPackagingLeadProductEntity) {
            return null;
        }

        $productPackagingLeadProductTransfer = $this->getFactory()
            ->createProductPackagingUnitMapper()
            ->mapProductPackagingLeadProductTransfer(
                $productPackagingLeadProductEntity,
                new ProductPackagingLeadProductTransfer()
            );

        return $productPackagingLeadProductTransfer;
    }

    /**
     * @module Product
     *
     * @param int[] $productPackagingUnitTypeIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->useProductPackagingUnitTypeQuery()
                ->filterByIdProductPackagingUnitType_In($productPackagingUnitTypeIds)
            ->endUse()
            ->joinProduct()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
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
            ->createSalesOrderItemQuery()
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
     * @return int
     */
    public function sumLeadProductAmountForAllSalesOrderItemsBySku(string $sku, array $reservedStateNames): int
    {
        $salesOrderItemQuery = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByAmountSku($sku)
            ->useStateQuery()
                ->filterByName($reservedStateNames, Criteria::IN)
            ->endUse()
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_AMOUNT . ')', static::COL_SUM_AMOUNT)
            ->select([static::COL_SUM_AMOUNT]);

        return (int)$salesOrderItemQuery->findOne();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitCriteria(): SpyProductPackagingUnitQuery
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuery()
            ->innerJoinWithProductPackagingUnitType()
            ->leftJoinWithSpyProductPackagingUnitAmount();
    }
}
