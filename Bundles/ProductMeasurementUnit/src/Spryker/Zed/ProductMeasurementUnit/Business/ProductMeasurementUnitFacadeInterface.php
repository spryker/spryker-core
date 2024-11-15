<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

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
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array;

    /**
     * Specification:
     * - Retrieves all product measurement sales units by ids.
     *
     * @api
     *
     * @param array<int> $salesUnitsIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array;

    /**
     * Specification:
     * - Retrieves all product measurement sales units.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
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
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * Specification:
     * - Retrieves product measurement sales unit transfer by the provided id of product measurement sales unit.
     *
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer;

    /**
     * Specification:
     * - Retrieves a collection of product measurement unit entities.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
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

    /**
     * Specification:
     * - Expands order transfer items with quantity sales unit if applicable.
     *
     * @api
     *
     * @deprecated Use {@link ProductMeasurementUnitFacade::expandOrderItemsWithQuantitySalesUnit()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithQuantitySalesUnit(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expands order items with quantity sales unit if applicable.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithQuantitySalesUnit(array $itemTransfers): array;

    /**
     * Specification:
     * - Expands SpySalesOrderItemEntityTransfer with ItemTransfer for the pre-save plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItem(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Translate the glossary keys of name attributes of ProductMeasurementSalesUnit transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function translateProductMeasurementSalesUnit(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer;

    /**
     * Specification:
     * - Retrieves a collection of product measurement unit entities according to provided offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findFilteredProductMeasurementUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Retrieves product measurement sales units according to provided offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function findFilteredProductMeasurementSalesUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Expands items without measurement sales unit with default measurement sales unit if it exists by store and product concrete sku.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithDefaultQuantitySalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Checks if product measurement sales units are found for items with amount sales units in `CartChangeTransfer`.
     * - Returns `CartPreCheckResponseTransfer` with an error in case sales unit not found for item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductMeasurementSalesUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Filters out products which have measurement unit available and returns back modified array.
     * - Requires ProductConcreteTransfer::idProductConcrete to be set.
     * - Requires ProductConcreteTransfer::fkProductAbstract to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filterProductsWithoutMeasurementUnit(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.items.groupKey` to be set.
     * - Requires `CartReorderTransfer.order.items.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.order.items.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.order.items` that have `ItemTransfer.quantitySalesUnit` set.
     * - Filters extracted items by `CartReorderRequestTransfer.salesOrderItemIds`.
     * - Merges extracted items' quantity by `ItemTransfer.groupKey`.
     * - Replaces `CartReorderTransfer.orderItems` with merged items by `idSalesOrderItem`.
     * - Returns `CartReorderTransfer` with merged order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function mergeProductMeasurementUnitCartReorderItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.id` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.amountSalesUnit` set.
     * - Gets product measurement sales unit IDs by store and product concrete SKUs from Persistence.
     * - Expands `CartReorderTransfer.reorderItems` with quantity sales unit if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with quantity sales unit, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with merchant reference set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithQuantitySalesUnit(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
