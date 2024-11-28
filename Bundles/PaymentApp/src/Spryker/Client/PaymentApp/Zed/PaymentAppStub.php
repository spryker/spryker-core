<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentApp\Zed;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Spryker\Client\PaymentApp\Dependency\Client\PaymentAppToZedRequestClientInterface;

class PaymentAppStub implements PaymentAppStubInterface
{
    /**
     * @var \Spryker\Client\PaymentApp\Dependency\Client\PaymentAppToZedRequestClientInterface
     */
    protected PaymentAppToZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\PaymentApp\Dependency\Client\PaymentAppToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(PaymentAppToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\PaymentApp\Communication\Controller\GatewayController::processExpressCheckoutPaymentRequestAction()
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        /** @var \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer */
        $expressCheckoutPaymentResponseTransfer = $this->zedRequestClient->call(
            '/payment-app/gateway/process-express-checkout-payment-request',
            $expressCheckoutPaymentRequestTransfer,
        );

        return $expressCheckoutPaymentResponseTransfer;
    }

    /**
     * @see \Spryker\Zed\PaymentApp\Communication\Controller\GatewayController::getCustomerAction()
     *
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer): PaymentCustomerResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\PaymentCustomerResponseTransfer $paymentCustomerResponseTransfer */
        $paymentCustomerResponseTransfer = $this->zedRequestClient->call('/payment-app/gateway/get-customer', $paymentCustomerRequestTransfer, null);

        return $paymentCustomerResponseTransfer;
    }
}
