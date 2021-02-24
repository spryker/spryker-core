<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Business\Payment;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\DummyMarketplacePayment\Dependency\Facade\DummyMarketplacePaymentToRefundInterface;

class MarketplaceRefund implements MarketplaceRefundInterface
{
    /**
     * @var \Spryker\Zed\DummyMarketplacePayment\Dependency\Facade\DummyMarketplacePaymentToRefundInterface
     */
    protected $refundFacade;

    /**
     * @param \Spryker\Zed\DummyMarketplacePayment\Dependency\Facade\DummyMarketplacePaymentToRefundInterface $refundFacade
     */
    public function __construct(DummyMarketplacePaymentToRefundInterface $refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function refund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
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
    protected function refundPayment(RefundTransfer $refundTransfer)
    {
        return ($refundTransfer->getAmount() > 0);
    }
}
