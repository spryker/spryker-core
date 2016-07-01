<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface;

class RefundCalculator implements RefundCalculatorInterface
{

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface
     */
    protected $salesAggregatorFacade;

    /**
     * @var \Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface
     */
    protected $refundCalculatorPlugin;

    /**
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface $salesAggregatorFacade
     * @param \Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface $refundCalculatorPlugin
     */
    public function __construct(RefundToSalesAggregatorInterface $salesAggregatorFacade, RefundCalculatorPluginInterface $refundCalculatorPlugin)
    {
        $this->salesAggregatorFacade = $salesAggregatorFacade;
        $this->refundCalculatorPlugin = $refundCalculatorPlugin;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);
        $refundTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());

        $this->refundCalculatorPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        return $refundTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->salesAggregatorFacade
            ->getOrderTotalsByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        return $orderTransfer;
    }

}
