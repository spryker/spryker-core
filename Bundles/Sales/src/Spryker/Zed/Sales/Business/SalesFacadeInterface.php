<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \Spryker\Zed\Sales\Business\SalesBusinessFactory getFactory()
 */
interface SalesFacadeInterface
{
    /**
     * Specification:
     * - Adds username to comment
     * - Saves comment to database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer);

    /**
     * Specification:
     * - Returns the distinct states of all order items for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getDistinctOrderStates($idSalesOrder);

    /**
     * Specification:
     * - Returns all comments for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getOrderCommentsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     * - Saves order and items to Persistence.
     * - Sets "is test" flag.
     * - Updates checkout response with saved order data.
     * - Sets initial state for state machine.
     * - Executes `OrderPostSavePluginInterface` stack of plugins according to the quote process flow.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::saveSalesOrder()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     * - Saves order and items to Persistence.
     * - Sets "is test" flag.
     * - Updates checkout response with saved order data.
     * - Sets initial state for state machine.
     * - Executes `OrderPostSavePluginInterface` stack of plugins according to the quote process flow.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::saveOrderRaw()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Saves order to Persistence.
     * - Sets "is test" flag.
     * - Updates checkout response with saved order data.
     * - Sets initial state for state machine.
     * - Executes `OrderPostSavePluginInterface` stack of plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderRaw(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Saves order items to Persistence.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Saves order totals to Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderTotals(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Update sales order with data from order transfer
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostUpdatePluginInterface} plugin stack.
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function updateOrder(OrderTransfer $orderTransfer, $idSalesOrder);

    /**
     * Specification:
     * - Replaces all values of the order address by the values from the addresses transfer
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return bool
     */
    public function updateOrderAddress(AddressTransfer $addressTransfer, $idAddress);

    /**
     * Specification:
     * - Creates new order address with values from the addresses transfer.
     * - Returns addresses transfer with id of newly created order address.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createOrderAddress(AddressTransfer $addressTransfer): AddressTransfer;

    /**
     * Specification:
     *  - Returns a list of of orders for the given customer id and (optional) filters.
     *  - Aggregates order totals calls -> SalesAggregator
     *  - Executes SearchOrderExpanderPluginInterface plugin stack.
     *  - Skips execution of `SearchOrderExpanderPluginInterface` plugins stack when `OrderListTransfer.withoutSearchOrderExpander` is set to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer);

    /**
     * Specification:
     * - Returns a list of of orders for the given customer id and (optional) filters.
     * - OrderListTransfer::$filters can contain offset-based pagination and ordering parameters.
     * - OrderListTransfer::$pagination can be used to apply page-based pagination strategy to the queried orders.
     * - Hydrates the resulting orders with related data.
     * - Executes SearchOrderExpanderPluginInterface plugin stack.
     * - Aggregates order totals calls -> SalesAggregator.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer);

    /**
     * Specification:
     * - Returns a transfer with the filtered list of orders for the given customer.
     * - Uses OrderListRequestTransfer::$filter to pull params for offset-based pagination strategy.
     * - OrderListRequestTransfer::customerReference must be set.
     * - Hydrates OrderTransfer with data from persistence by idSaleOrder.
     * - Executes SearchOrderExpanderPluginInterface plugin stack.
     * - Updates the total number of orders for the customer to the pagination transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer;

    /**
     * Specification:
     *  - Returns a list of of orders for the given customer id and (optional) filters, without order items information.
     *  - Aggregates order totals calls -> SalesAggregator
     *  - Paginates order list for limited result
     *  - Executes SearchOrderExpanderPluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverview(OrderListTransfer $orderListTransfer, $idCustomer): OrderListTransfer;

    /**
     * Specification:
     *  - Returns the order for the given customer id and sales order id.
     *  - Executes CustomerOrderAccessCheckPluginInterface plugins, expects OrderTransfer::customer to be provided, not applicable to order creator.
     *  - Aggregates order totals calls -> SalesAggregator.
     *  - Hydrates order using quote level (BC) or item level shipping addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrder(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Returns persisted order information for the given sales order id.
     *  - Hydrates order by calling HydrateOrderPlugin's registered in project dependency provider.
     *  - Hydrates order using quote level (BC) or item level shipping addresses.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::getOrder()} instead.
     *
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     *  - Returns persisted order information for the given sales order id.
     *  - Hydrates order by calling HydrateOrderPlugins registered in project dependency provider.
     *  - Hydrates order using quote level (BC) or item level shipping addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrder(OrderFilterTransfer $orderFilterTransfer): OrderTransfer;

    /**
     * Specification:
     *  - Returns persisted order information for the given sales order id.
     *  - Hydrates order by calling HydrateOrderPlugin's registered in project dependency provider.
     *  - Hydrates order using quote level (BC) or item level shipping addresses.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer;

    /**
     * Specification:
     * - Returns the order for the given sales order item id.
     * - Hydrates order using quote level (BC) or item level shipping addresses.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem($idSalesOrderItem);

    /**
     * Specification:
     * - Gets hydrated OrderTransfer by given order reference and customer reference.
     * - OrderTransfer must have customerReference and orderReference, otherwise method fails.
     * - Returns empty OrderTransfer if order entity not found in the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReference(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Executes `ItemPreTransformerPluginInterface` plugins stack.
     * - Transforms provided cart items according configured cart item transformer strategies.
     * - If no cart item transformer strategy is configured, explodes the provided items per quantity.
     * - Recalculates order transfer with new values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer|null $checkoutResponseTransfer Deprecated: Parameter is not used
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer, ?CheckoutResponseTransfer $checkoutResponseTransfer = null);

    /**
     * Specification:
     * - Creates sales expense entity from transfer object.
     * - Adds sales expense to sales order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer;

    /**
     * Specification:
     * - Updates sales expense entity from transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function updateSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer;

    /**
     * Specification:
     * - Returns a collection of order items grouped by unique group key.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::getUniqueItemsFromOrder()} instead.
     *
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getUniqueOrderItems(iterable $itemTransfers): array;

    /**
     * Specification:
     * - Expands AddressTransfer with customer address data or sales address data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expandWithCustomerOrSalesAddress(AddressTransfer $addressTransfer): AddressTransfer;

    /**
     * Specification:
     * - Extracts unique items.
     * - Returns a collection of items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getUniqueItemsFromOrder(OrderTransfer $orderTransfer): array;

    /**
     * Specification:
     * - Retrieves order items from persistence by criteria from OrderItemFilterTransfer.
     * - Executes OrderItemExpanderPluginInterface stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer;

    /**
     * Specification:
     * - Requires `OrderListTransfer.format` to be set.
     * - Expects `OrderListTransfer.pagination` to be set.
     * - Filters orders by `OrderListTransfer.filterFields` if provided.
     * - Filters orders by `OrderListTransfer.filter` if provided.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface} plugin stack.
     * - Finds orders by criteria from `OrderListTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer;

    /**
     * Specification:
     * - Expands order items with currency ISO code.
     * - Expects ItemTransfer::FK_SALES_ORDER to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithCurrencyIsoCode(array $itemTransfers): array;

    /**
     * Specification:
     * - Requires OrderCancelRequestTransfer::orderReference or OrderCancelRequestTransfer::idSalesOrder to be set.
     * - Requires CustomerTransfer:customerReference to be set.
     * - Requires ItemTransfer::idSalesOrderItem to be set.
     * - Retrieves OrderTransfer filtered by orderReference and customerReference.
     * - Checks OrderTransfer::isCancellable.
     * - Triggers cancel event for provided order items.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostCancelPluginInterface} plugin stack.
     * - Returns "isSuccessful=true" and order transfer on success or `isSuccessful=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer;

    /**
     * Specification:
     * - Checks that the order is not a duplicate.
     * - Expects `Quote::orderReference`, `Quote::getIsOrderPlacedSuccessfully` and `Quote::customer::customerReference()` to be set.
     * - Sets `isSuccess=false` and adds an error message in `CheckoutResponseTransfer` if order is a duplicate and returns `false`.
     * - Returns `true` if the order was not found in the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkDuplicateOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Retrieves sales order entities filtered by criteria from Persistence.
     * - Uses `OrderCriteriaTransfer.orderConditions.salesOrderIds` to filter by IDs.
     * - Uses `OrderCriteriaTransfer.orderConditions.orderReferences` to filter by order references.
     * - Uses `OrderCriteriaTransfer.orderConditions.customerReferences` to filter by customer references.
     * - Uses `OrderCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `OrderCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `OrderCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `OrderCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - If `OrderCriteriaTransfer.orderConditions.withOrderExpanderPlugins` is set to `true`, executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface} plugins.
     * - Returns `OrderCollectionTransfer` filled with found orders.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function getOrderCollection(OrderCriteriaTransfer $orderCriteriaTransfer): OrderCollectionTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.originalOrder` to be set.
     * - Requires `QuoteTransfer.customer` to be set.
     * - Requires `QuoteTransfer.customer.customerReference` to be set.
     * - Requires `QuoteTransfer.currency` to be set.
     * - Requires `QuoteTransfer.currency.code` to be set.
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires `QuoteTransfer.store` to be set.
     * - Requires `QuoteTransfer.store.name` to be set.
     * - Requires `QuoteTransfer.billingAddress` to be set.
     * - Requires `QuoteTransfer.billingAddress.iso2Code` to be set.
     * - Requires `QuoteTransfer.originalOrder.billingAddress` to be set.
     * - Requires `QuoteTransfer.originalOrder.billingAddress.idSalesOrderAddress` to be set.
     * - Updates order billing address with billing address data from quote.
     * - BC: Updates order shipping address with shipping address data from quote.
     * - BC: Hydrates quote items with shipping address.
     * - Resolves a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface}.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface}.
     * - Updates order data with data from quote.
     * - Sets the current locale ID to the order.
     * - Resolves a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface} according to the quote process flow.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface} according to the quote process flow.
     * - Maps updated `OrderTransfer` to `SaveOrderTransfer`.
     * - Returns `SaveOrderTransfer` with updated order data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function updateOrderByQuote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): SaveOrderTransfer;

    /**
     * Specification:
     * - Retrieves sales expense entities by provided criteria from Persistence.
     * - Executes {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesExpensePreDeletePluginInterface} plugin stack.
     * - Deletes found sales expense entities.
     * - Uses `SalesExpenseCollectionDeleteCriteriaTransfer.salesOrderIds` to filter sales expenses by the sales order IDs.
     * - Uses `SalesExpenseCollectionDeleteCriteriaTransfer.types` to filter sales expenses by the sales expenses types.
     * - Deletes all the existing entities when no criteria properties are set.
     * - Returns `SalesExpenseCollectionResponseTransfer.salesExpenses[]` filled with deleted sales expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer
     */
    public function deleteSalesExpenseCollection(
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): SalesExpenseCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `SalesOrderItemCollectionRequestTransfer.quote` to be set.
     * - Requires `SalesOrderItemCollectionRequestTransfer.items.fkSalesOrder` to be set.
     * - Validates that all items belong to the same sales order.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreCreatePluginInterface}.
     * - Reuses `SalesFacade::saveSalesOrderItems()` to save order items.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostCreatePluginInterface}.
     * - Returns `SalesOrderItemCollectionResponseTransfer` with created order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function createSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `SalesOrderItemCollectionRequestTransfer.quote` to be set.
     * - Requires `SalesOrderItemCollectionRequestTransfer.items.fkSalesOrder` to be set.
     * - Requires `SalesOrderItemCollectionRequestTransfer.items.idSalesOrderItem` to be set.
     * - Validates that all items belong to the same sales order.
     * - Validates that there no duplicated items in the collection.
     * - Validates that order items exist in the database.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreUpdatePluginInterface}.
     * - Reuses `SalesFacade::saveSalesOrderItems()` to save order items.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface}.
     * - Returns `SalesOrderItemCollectionResponseTransfer` with updated order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ItemTransfer.idSalesOrderItem` to be set for each item in `SalesOrderItemCollectionDeleteCriteriaTransfer.items`.
     * - Expands `SalesOrderItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` with sales order item IDs from `SalesOrderItemCollectionDeleteCriteriaTransfer.items`.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface} plugins.
     * - Uses `SalesOrderItemCollectionDeleteCriteriaTransfer.items.idSalesOrderItem` to filter sales order items by the sales order item IDs.
     * - Deletes found by criteria sales order items from DB.
     * - The plugin stack and sales order items deletion are executed within a database transaction.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function deleteSalesOrderItemCollection(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
