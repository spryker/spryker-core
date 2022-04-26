<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutDoSaveOrderInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\SalesPayment\Communication\Plugin\Checkout\SalesPaymentCheckoutDoSaveOrderPlugin}
 * and {@link \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface} instead.
 *
 * Requires Checkout ^4.0.0
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class PaymentOrderSaverPlugin extends AbstractPaymentOrderPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $checkoutResponseTransfer = $this->createCheckoutResponse($saveOrderTransfer);
        $this->getFacade()->savePaymentForCheckout($quoteTransfer, $checkoutResponseTransfer);
    }
}
