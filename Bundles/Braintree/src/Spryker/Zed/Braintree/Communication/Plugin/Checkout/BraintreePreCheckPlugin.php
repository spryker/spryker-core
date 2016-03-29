<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\BraintreeTransactionResponseTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;

/**
 * @method \Spryker\Zed\Braintree\Business\BraintreeFacade getFacade()
 */
class BraintreePreCheckPlugin extends BaseAbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $braintreeTransactionResponseTransfer = $this->getFacade()->preCheckPayment($quoteTransfer);
        $this->checkForErrors($braintreeTransactionResponseTransfer, $checkoutResponseTransfer);

        //$quoteTransfer->getPayment()->getBraintree()
        //    ->setPreCheckId($braintreeTransactionResponseTransfer->getIdentificationUniqueid());

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer $braintreeTransactionResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function checkForErrors(
        BraintreeTransactionResponseTransfer $braintreeTransactionResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        //TODO
    }

}
