<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class RefundableExpenseAmountCalculatorPlugin extends AbstractPlugin implements RefundCalculatorPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems)
    {
        return $this->getFacade()->calculateRefundableExpenseAmount($refundTransfer, $orderTransfer, $salesOrderItems);
    }
}
