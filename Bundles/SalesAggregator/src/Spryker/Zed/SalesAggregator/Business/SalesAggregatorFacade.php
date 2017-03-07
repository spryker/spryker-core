<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesAggregator\Business\SalesAggregatorBusinessFactory getFactory()
 */
class SalesAggregatorFacade extends AbstractFacade implements SalesAggregatorFacadeInterface
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
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesAggregatorOrder($idSalesOrder);
    }

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
    public function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesAggregatorOrderItem($idSalesOrderItem);
    }

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
    public function getOrderTotalByOrderTransfer(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByOrderTransfer($orderTransfer);
    }

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
    public function aggregateOrderExpenseAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createExpenseOrderTotalAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderGrandTotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createGrandTotalOrderTotalAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderItemAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemOrderOrderAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderSubtotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSubtotalOrderAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderItemTaxAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderItemTaxAmountAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTaxAmountAggregator()->aggregate($orderTransfer);
    }

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
    public function aggregateOrderExpenseTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderExpenseTaxAmountAggregator()->aggregate($orderTransfer);
    }

}
