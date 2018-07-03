<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductMeasurementUnitFacadeInterface
{
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
     * - Retrieves all product measurement sales units by ids.
     *
     * @api
     *
     * @param int[] $salesUnitsIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array;

    /**
     * Specification:
     * - Retrieves all product measurement sales units.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnits(): array;

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
    public function expandItemGroupKeyWithQuantitySalesUnit(ItemTransfer $itemTransfer): string;

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
     * - Expands CartChangeTransfer with QuantitySalesUnit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithQuantitySalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

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

    /**
     * Specification:
     * - Retrieves a collection of product measurement unit entities.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array;

    /**
     * Specification:
     * - Calculates quantity normalized sales unit value.
     * - Updates quote item transfers
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateQuantitySalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Add infrastructural measurement unit list to database.
     *
     * @api
     *
     * @return void
     */
    public function installProductMeasurementUnit(): void;
}
