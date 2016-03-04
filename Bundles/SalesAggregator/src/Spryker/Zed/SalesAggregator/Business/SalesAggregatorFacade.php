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
class SalesAggregatorFacade extends AbstractFacade
{

    /**
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
     * TODO FW Move to own bundle and add description
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
