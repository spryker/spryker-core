<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Dependency\Client;

use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;

class PaymentsRestApiToPaymentClientBridge implements PaymentsRestApiToPaymentClientInterface
{
    /**
     * @var \Spryker\Client\Payment\PaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @param \Spryker\Client\Payment\PaymentClientInterface $paymentClient
     */
    public function __construct($paymentClient)
    {
        $this->paymentClient = $paymentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->paymentClient->initializePreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->paymentClient->cancelPreOrderPayment($preOrderPaymentRequestTransfer);
    }
}
