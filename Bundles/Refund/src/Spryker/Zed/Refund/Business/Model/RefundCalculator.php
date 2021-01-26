<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;

class RefundCalculator implements RefundCalculatorInterface
{
    /**
     * @var \Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface[]
     */
    protected $refundCalculatorPlugins;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface[] $refundCalculatorPlugins
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface $salesFacade
     */
    public function __construct(
        array $refundCalculatorPlugins,
        RefundToSalesInterface $salesFacade
    ) {
        $this->refundCalculatorPlugins = $refundCalculatorPlugins;
        $this->salesFacade = $salesFacade;
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

        foreach ($this->refundCalculatorPlugins as $refundCalculatorPlugin) {
            $refundTransfer = $refundCalculatorPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);
        }

        return $refundTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $salesOrderEntity)
    {
        return $this->salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());
    }
}
