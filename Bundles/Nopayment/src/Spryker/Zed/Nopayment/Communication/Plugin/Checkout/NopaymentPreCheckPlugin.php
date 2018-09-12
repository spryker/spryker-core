<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\Nopayment\Business\NopaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory getFactory()
 */
class NopaymentPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{
    const ERROR_CODE_NOPAYMENT_NOT_ALLOWED = 403;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function execute(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        if ($quoteTransfer->getTotals()->getPriceToPay() > 0) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Nopayment is only available if the price to pay is 0');
            $error->setErrorCode(self::ERROR_CODE_NOPAYMENT_NOT_ALLOWED);
            $checkoutResponseTransfer->addError($error);
        }
    }
}
