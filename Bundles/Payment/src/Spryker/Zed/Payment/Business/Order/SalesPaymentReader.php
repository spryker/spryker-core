<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use Generated\Shared\Transfer\SalesPaymentTransfer;
use Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface;

class SalesPaymentReader implements SalesPaymentReaderInterface
{
    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface
     */
    protected $paymentQueryContainer;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface $paymentQueryContainer
     */
    public function __construct(PaymentQueryContainerInterface $paymentQueryContainer)
    {
        $this->paymentQueryContainer = $paymentQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $paymentTransfer
     *
     * @return int
     */
    public function getPaymentMethodPriceToPay(SalesPaymentTransfer $paymentTransfer)
    {
        $salesPaymentEntity = $this->paymentQueryContainer->queryPaymentMethodPriceToPay(
            $paymentTransfer->getFkSalesOrder(),
            $paymentTransfer->getPaymentProvider(),
            $paymentTransfer->getPaymentMethod()
        )->findOne();

        return $salesPaymentEntity->getAmount();
    }
}
