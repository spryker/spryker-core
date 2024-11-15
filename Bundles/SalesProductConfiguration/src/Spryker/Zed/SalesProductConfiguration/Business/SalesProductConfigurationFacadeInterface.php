<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface SalesProductConfigurationFacadeInterface
{
    /**
     * Specification:
     * - Requires `QuoteTransfer.items.idSalesOrderItem` transfer property to be set.
     * - Requires `QuoteTransfer.items.productConfigurationInstance` transfer property to be set.
     * - Requires `QuoteTransfer.items.productConfigurationInstance.configuratorKey` transfer property to be set.
     * - Persists product configuration from ItemTransfer in Quote to sales_order_item_configuration table.
     * - Expects the product configuration instance to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfigurationsFromQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Expands items with product configuration.
     * - Expects ItemTransfer::ID_SALES_ORDER_ITEM to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithProductConfiguration(array $itemTransfers): array;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.salesOrderItemConfiguration` set.
     * - Expands `CartReorderTransfer.reorderItems` with product configuration instance data if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with product configuration instance, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with product configuration instance set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithProductConfiguration(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
