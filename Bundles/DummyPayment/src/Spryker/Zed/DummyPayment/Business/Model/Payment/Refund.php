<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Business\Model\Payment;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundFacadeInterface;

class Refund implements RefundInterface
{
    /**
     * @var \Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundFacadeInterface
     */
    protected $refundFacade;

    /**
     * @param \Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundFacadeInterface $refundFacade
     */
    public function __construct(DummyPaymentToRefundFacadeInterface $refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function refund(array $salesOrderItems, SpySalesOrder $salesOrderEntity): void
    {
        $refundTransfer = $this->refundFacade->calculateRefund($salesOrderItems, $salesOrderEntity);
        $paymentRefundResult = $this->refundPayment($refundTransfer);

        if ($paymentRefundResult) {
            $this->refundFacade->saveRefund($refundTransfer);
        }
    }

    /**
     * This is just a fake method, in a normal environment you would call your facade and trigger the refund process.
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return bool
     */
    protected function refundPayment(RefundTransfer $refundTransfer): bool
    {
        return ($refundTransfer->getAmount() > 0);
    }
}
