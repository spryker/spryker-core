<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\PlaceOrder\CheckoutSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentFacade getFacade()
 */
class PaymentOrderSaverPlugin extends AbstractPaymentOrderPlugin implements CheckoutSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $checkoutResponseTransfer = $this->createCheckoutResponse($saveOrderTransfer);
        $this->getFacade()->savePaymentForCheckout($quoteTransfer, $checkoutResponseTransfer);
    }
}
