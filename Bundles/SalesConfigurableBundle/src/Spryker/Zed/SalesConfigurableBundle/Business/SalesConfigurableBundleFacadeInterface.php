<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer;

interface SalesConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Retrieves sales order configured bundles entities.
     * - Filters by template uuid when provided.
     * - Filters by slot uuid when provided.
     * - Filters by itemIds when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer;

    /**
     * Specification:
     * - Persists configured bundles from ItemTransfer in Quote to sales_order configured bundle tables.
     * - Expects the configured bundle groupKey to be provided.
     * - Expects the configured bundle quantity to be provided.
     * - Expects the configured bundle template to be provided.
     * - Expects the configured bundle slot to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Expands sales order by configured bundles.
     * - Expands configured bundle items with translations for current locale.
     * - Expands ItemTransfer by configured bundle item.
     *
     * @api
     *
     * @deprecated Use {@link expandOrderItemsWithSalesOrderConfiguredBundles()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithConfiguredBundles(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Splits configurable bundles by configurable bundle quantity.
     * - Applied for configurable bundles when configurable bundle quantity is more than 1.
     * - Duplicates items for each split configurable bundle.
     * - Adjusts items and product options quantity per split.
     * - Alters groupkey for each split configurable bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function transformConfiguredBundleOrderItems(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expands items with sales order configured bundles.
     * - Expects ItemTransfer::ID_SALES_ORDER_ITEM to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithSalesOrderConfiguredBundles(array $itemTransfers): array;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.items.groupKey` to be set.
     * - Requires `CartReorderTransfer.order.items.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.order.items.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.order.items` that have `ItemTransfer.salesOrderConfiguredBundle` and `ItemTransfer.salesOrderConfiguredBundleItem` set.
     * - Filters extracted items by `CartReorderRequestTransfer.salesOrderItemIds`.
     * - Merges extracted items and configured bundles quantity by `ItemTransfer.groupKey`.
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
    public function mergeConfigurableBundleProductsCartReorderItems(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.salesOrderConfiguredBundle` and `ItemTransfer.salesOrderConfiguredBundleItem` set.
     * - Expands `CartReorderTransfer.reorderItems` with configured bundle and configured bundle item data if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with configured bundle, configured bundle item, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with configured bundle and configured bundle item data set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithConfigurableBundle(
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer;

    /**
     * Specification:
     * - Uses `SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order configured bundle item entities by the sales order item IDs.
     * - Deletes found by criteria sales order configured bundle item entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer
     */
    public function deleteSalesOrderConfiguredBundleItemCollection(
        SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
    ): SalesOrderConfiguredBundleItemCollectionResponseTransfer;
}
