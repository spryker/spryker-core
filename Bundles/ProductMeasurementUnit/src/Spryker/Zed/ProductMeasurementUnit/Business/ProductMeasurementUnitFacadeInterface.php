<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

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
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getBaseUnitByIdProduct($idProduct);

    /**
     * Specification:
     * - Retrieves all product measurement sales units related to a given product concrete.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getSalesUnitsByIdProduct($idProduct);

    /**
     * Specification:
     * - Validates if the quantity's sales unit measurement values are valid numbers within the given precision.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateQuantitySalesUnitValues(CartChangeTransfer $cartChangeTransfer);

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
    public function expandItemGroupKeyWithSalesUnit(ItemTransfer $itemTransfer);

    /**
     * Specification:
     * - Returns the normalized sales unit quantity value using the provided item quantity and product measurement unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer);

    /**
     * Specification:
     * - Retrieves the SpyProductMeasurementSalesUnitEntity transfer object by the provided ID.
     * - Sets related default precision and conversion ratio when not defined.
     *
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getSalesUnitEntity($idProductMeasurementSalesUnit);
}
