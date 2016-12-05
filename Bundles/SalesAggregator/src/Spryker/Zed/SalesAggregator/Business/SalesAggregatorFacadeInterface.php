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

}
