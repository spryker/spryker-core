<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund\Business\Model\RefundCalculator;

use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\ItemRefundCalculator;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group RefundCalculator
 * @group ItemRefundCalculator
 */
class ItemRefundCalculatorTest extends AbstractRefundCalculatorTest
{

    /**
     * @return void
     */
    public function testCalculateRefundForOrderWithoutAlreadyRefundedItems()
    {
        $refundCalculationPlugin = new ItemRefundCalculator();
        $orderTransfer = $this->getOrderTransferWithoutRefundedItems();
        $salesOrderItems = [
            $this->getSalesOrderItemOne()
        ];

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);
        $refundCalculationPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        $this->assertSame(100, $refundTransfer->getAmount());
    }

}
