<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface MerchantSalesOrderFacadeInterface
{
    /**
     * Specification:
     * - Requires OrderTransfer.idSalesOrder.
     * - Requires OrderTransfer.orderReference.
     * - Requires OrderTransfer.items.
     * - Iterates through the order items of given order looking for merchant reference presence.
     * - Skips all the order items without merchant reference.
     * - Creates a new merchant order for each unique merchant reference found.
     * - Creates a new merchant order item for each order item with merchant reference and assign it to a merchant order accordingly.
     * - Creates a new merchant order totals and assign it to a merchant order accordingly.
     * - Returns a collection of merchant orders filled with merchant order items and merchant order totals.
     * - Executes MerchantOrderPostCreatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function createMerchantOrderCollection(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer;

    /**
     * Specification:
     * - Requires MerchantOrderItem.idMerchantOrderItem transfer field to be set.
     * - Updates existing merchant order item based on MerchantOrderItem.idMerchantOrderItem in database.
     * - Returns MerchantOrderItemResponse transfer with isSuccessful = false when merchant order item not found.
     * - Returns MerchantOrderItemResponse transfer with isSuccessful = true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function updateMerchantOrderItem(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemResponseTransfer;

    /**
     * Specification:
     * - Finds all the merchant orders using MerchantOrderCriteriaTransfer.
     * - Returns a collection of found merchant orders.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderCollection(
        MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
    ): MerchantOrderCollectionTransfer;

    /**
     * Specification:
     * - Returns a merchant order found using MerchantOrderCriteriaTransfer.
     * - Returns NULL if merchant order is not found.
     * - Executes MerchantOrderExpanderPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): ?MerchantOrderTransfer;

    /**
     * Specification:
     * - Returns a merchant order item found using provided criteria.
     * - Returns NULL if merchant order item was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer|null
     */
    public function findMerchantOrderItem(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): ?MerchantOrderItemTransfer;

    /**
     * Specification:
     * - Expands SpySalesOrderItemEntityTransfer with ItemTransfer.merchantReference if exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithMerchant(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Expands expense transfer with merchant reference from items.
     * - Doesn't expand if items have different merchant references or given expense is not of shipment type.
     * - Requires ShipmentGroup.items property to be set.
     * - Returns expanded expense transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function expandShipmentExpenseWithMerchantReference(
        ExpenseTransfer $expenseTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ExpenseTransfer;

    /**
     * Specification:
     * - Expands order items with merchant order reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithMerchantOrderData(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expands order with merchant references from order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithMerchantReferences(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Returns number of merchant orders filtered by given merchant order criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return int
     */
    public function getMerchantOrdersCount(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): int;

    /**
     * Specification:
     * - Returns a merchant order item collection found using provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function getMerchantOrderItemCollection(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): MerchantOrderItemCollectionTransfer;
}
