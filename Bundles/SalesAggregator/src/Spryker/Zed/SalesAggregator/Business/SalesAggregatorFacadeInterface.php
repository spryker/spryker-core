<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\SalesAggregator\Business;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \Spryker\Zed\SalesAggregator\Business\SalesAggregatorBusinessFactory getFactory()
 */
interface SalesAggregatorFacadeInterface
{

    /**
     * Specification:
     *  - Reads order from database and stores in order transfer
     *  - Run all aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @throw \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     *  - Reads order from database and stores in order transfer
     *  - Run all item aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @throw \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem);

    /**
     *  Specification:
     *  - Use existing OrderTransfer instead fo quering database
     *  - Run all item aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalByOrderTransfer(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Iterate order exepenses and sum up amounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseAmounts(OrderTransfer $orderTransfer);

    /**
     *  Specification:
     *  - Sum up expenses and subtotals
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderGrandTotal(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Sum up item amounts before discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemAmounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Sum up order subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotal(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Calculate item tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemTaxAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Calculate order total tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTaxAmountAggregator(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Calculate order expense tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxAmountAggregator(OrderTransfer $orderTransfer);

    /**
     *
     * Specification:
     *  - Loops over all items and calculates gross amount for each items
     *  - Data is read from sales order persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemProductOptionGrossPrice(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over all items and calculates subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotalWithProductOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over all quote items, and sum all calculated discounts
     * - Loop over all quote expenses, and sum all calculated discounts
     * - Store total amount into order transfer discount totals
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over item calculated discount and group by discount display name
     * - Loop over expense calculated discount and group by discount display name
     * - Store all variations to order transfer calculated discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over item calculated discounts and calculate item gross amount after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Take current grand total and subtract total discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over expenses and calculate order expense tax after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over order expenses and calculated expense gross amount after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over product option discount with discount sum
     *  - Calculates totals with product options
     *  - Amounts stored: OrderTransfer->getTotals()->setDiscountTotal()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmountWithProductOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over product option calculated discounts and sums up to order total
     *  - Amounts stored in OrderTransfer->getCalculatedDiscounts()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscountsWithProductOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Read order discounts from persistence
     *  - Assign discount to each coresponding item
     *  - Calculate item and product option discount amount fields with discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over items with discounts
     *  - Calculate discount amount for items after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsAndDiscountsTaxAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over items with options and discounts
     *  - Loops over expenses with discounts
     *  - Sum all tax amounts calculated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalTaxAmountWithDiscounts(OrderTransfer $orderTransfer);

    /**
     *
     * Specification:
     *
     *  - Aggregates OrderTransfer::bundleItems prices
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateBundlePrice(OrderTransfer $orderTransfer);

}
