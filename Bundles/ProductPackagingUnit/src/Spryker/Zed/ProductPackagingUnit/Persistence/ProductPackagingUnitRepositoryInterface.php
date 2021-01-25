<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductPackagingUnitRepositoryInterface
{
    /**
     * @param string $productPackagingUnitTypeName
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        string $productPackagingUnitTypeName
    ): ?ProductPackagingUnitTypeTransfer;

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeById(
        int $idProductPackagingUnitType
    ): ?ProductPackagingUnitTypeTransfer;

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        int $idProductPackagingUnitType
    ): int;

    /**
     * @param string $siblingProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductPackagingUnitLeadProductForPackagingUnit(
        string $siblingProductSku
    ): ?ProductConcreteTransfer;

    /**
     * @param int[] $productPackagingUnitTypeIds
     *
     * @return int[]
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;

    /**
     * @param int $idProductPackagingUnit
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitById(
        int $idProductPackagingUnit
    ): ?ProductPackagingUnitTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductId(
        int $idProduct
    ): ?ProductPackagingUnitTransfer;

    /**
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductSku(
        string $productSku
    ): ?ProductPackagingUnitTransfer;

    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer[]
     */
    public function findProductPackagingUnitsByProductSku(
        array $productSkus
    ): array;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer[]
     */
    public function findSalesOrderItemsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return string[]
     */
    public function getMappedLeadProductSkusBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getMappedProductMeasurementSalesUnits(array $salesOrderItemIds): array;

    /**
     * @uses State
     *
     * @param string $sku
     * @param string[] $reservedStateNames
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateProductPackagingUnitReservation(string $sku, array $reservedStateNames, ?StoreTransfer $storeTransfer = null): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductPackagingUnitCountByProductConcreteIds(array $productConcreteIds): array;
}
