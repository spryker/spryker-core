<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Please use \Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentOrderSaverPlugin instead
 * Will be removed in the next major.
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacade getFacade()
 */
class PaymentSaverPlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->savePaymentForCheckout($quoteTransfer, $checkoutResponse);
    }
}
