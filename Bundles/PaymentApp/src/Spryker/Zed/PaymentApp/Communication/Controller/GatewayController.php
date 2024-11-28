<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Communication\Controller;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequestAction(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        return $this->getFacade()->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomerAction(
        PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
    ): PaymentCustomerResponseTransfer {
        return $this->getFacade()->getCustomer($paymentCustomerRequestTransfer);
    }
}
