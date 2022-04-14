<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class PaymentAuthorizationCheckoutPostSavePlugin extends AbstractPlugin implements CheckoutPostSaveInterface
{
    /**
     * {@inheritDoc}
     * - Checks whether a payment method that requires authorization is selected for the given order.
     * - Terminates hook execution if not.
     * - Receives all the necessary information about the foreign payment method.
     * - Sends a request with all pre-selected quote fields using PaymentMethodTransfer.paymentAuthorizationEndpoint.
     * - If the response is free of errors, uses PaymentMethodTransfer.paymentAuthorizationEndpoint and response data to build a redirect URL.
     * - Updates CheckoutResponseTransfer with errors or the redirect URL according to response received.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);
    }
}
