<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

interface ProductMeasurementUnitRepositoryInterface
{
    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer;

    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getProductMeasurementSalesUnitTransfersByIdProduct(int $idProduct): array;

    /**
     * @param array<int> $salesUnitsIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getProductMeasurementSalesUnitTransfersByIds(array $salesUnitsIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getProductMeasurementSalesUnitTransfers(): array;

    /**
     * @param int $idProductMeasurementBaseUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransfer(int $idProductMeasurementBaseUnit): ProductMeasurementBaseUnitTransfer;

    /**
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * @param int $idSalesOrder
     *
     * @return array<\Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer>
     */
    public function querySalesOrderItemsByIdSalesOrder($idSalesOrder): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getMappedProductMeasurementSalesUnits(array $salesOrderItemIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findAllProductMeasurementUnitTransfers(): array;

    /**
     * @param array<string> $productConcreteSkus
     * @param int $idStore
     *
     * @return array<int>
     */
    public function findIndexedStoreAwareDefaultProductMeasurementSalesUnitIds(array $productConcreteSkus, int $idStore): array;

    /**
     * @module Product
     *
     * @param array<string> $productConcreteSkus
     * @param int $idStore
     *
     * @return array<int[]>
     */
    public function findIndexedStoreAwareProductMeasurementSalesUnitIds(array $productConcreteSkus, int $idStore): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findFilteredProductMeasurementUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function findFilteredProductMeasurementSalesUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductMeasurementSalesUnitCountByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function getProductMeasurementBaseUnitCountByProductAbstractIds(array $productAbstractIds): array;
}
