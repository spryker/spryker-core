<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

class RatepayToSalesAggregatorBridge implements RatepayToSalesAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacadeInterface
     */
    protected $salesAggregatorFacade;

    /**
     * @param \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacadeInterface $salesAggregatorFacade
     */
    public function __construct($salesAggregatorFacade)
    {
        $this->salesAggregatorFacade = $salesAggregatorFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesAggregatorFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalByOrderTransfer(OrderTransfer $orderTransfer)
    {
        return $this->salesAggregatorFacade->getOrderTotalByOrderTransfer($orderTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->salesAggregatorFacade->getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem);
    }

}
