<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Zed;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface;

class PaymentStub implements PaymentStubInterface
{
    /**
     * @var \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(PaymentToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\Payment\Communication\Controller\GatewayController::initializePreOrderPaymentAction()
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        /** @var \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer */
        $preOrderPaymentResponseTransfer = $this->zedRequestClient->call('/payment/gateway/initialize-pre-order-payment', $preOrderPaymentRequestTransfer);

        return $preOrderPaymentResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\Payment\Communication\Controller\GatewayController::cancelPreOrderPaymentAction()
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        /** @var \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer */
        $preOrderPaymentResponseTransfer = $this->zedRequestClient->call('/payment/gateway/cancel-pre-order-payment', $preOrderPaymentRequestTransfer);

        return $preOrderPaymentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        /** @var \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer */
        $paymentMethodsTransfer = $this->zedRequestClient->call('/payment/gateway/get-available-methods', $quoteTransfer, null);

        return $paymentMethodsTransfer;
    }
}
