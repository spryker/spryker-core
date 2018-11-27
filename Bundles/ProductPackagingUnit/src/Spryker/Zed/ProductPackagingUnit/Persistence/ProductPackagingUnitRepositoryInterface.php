<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

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
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer;

    /**
     * @param string $siblingProductSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductBySiblingProductSku(
        string $siblingProductSku
    ): ?ProductPackagingLeadProductTransfer;

    /**
     * @param int[] $productPackagingUnitTypeIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;

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
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer[]
     */
    public function findSalesOrderItemsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @uses State
     *
     * @param string $sku
     * @param string[] $reservedStateNames
     *
     * @return int
     */
    public function sumLeadProductAmountForAllSalesOrderItemsBySku(string $sku, array $reservedStateNames): int;
}
