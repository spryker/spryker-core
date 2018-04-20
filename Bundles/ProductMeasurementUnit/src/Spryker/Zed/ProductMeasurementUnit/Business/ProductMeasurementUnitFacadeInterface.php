<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

interface ProductMeasurementUnitFacadeInterface
{
    /**
     * Specification:
     * - Retrieves a product measurement base unit for a given product concrete.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer;

    /**
     * Specification:
     * - Retrieves all product measurement sales units related to a given product concrete.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array;

    /**
     * Specification:
     * - Returns the expanded group key if item has a sales unit.
     * - Returns the provided group key otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function expandItemGroupKeyWithSalesUnit(ItemTransfer $itemTransfer): string;

    /**
     * Specification:
     * - Returns the normalized quantity sales unit value using the provided item quantity and product measurement unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer): int;

    /**
     * Specification:
     * - Retrieves a product measurement sales unit transfer object by the provided ID.
     * - Sets related default precision and conversion ratio when not defined.
     *
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getSalesUnitEntity(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer;

    /**
     * Specification:
     * - Retrieves a list of all product measurement unit codes, mapped by their own ID.
     *
     * @api
     *
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap(): array;

    /**
     * Specification:
     * - Retrieves a collection of product measurement unit entities by the provided IDs.
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;
}
