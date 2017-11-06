<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreConditionPluginInterface;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacadeInterface getFacade()
 */
class PayolutionPreCheckPlugin extends BaseAbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function execute(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $payolutionTransactionResponseTransfer = $this->getFacade()->preCheckPayment($quoteTransfer);
        $isPassed = $this->checkForErrors($payolutionTransactionResponseTransfer, $checkoutResponseTransfer);
        $quoteTransfer->getPayment()->getPayolution()
            ->setPreCheckId($payolutionTransactionResponseTransfer->getIdentificationUniqueid());

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer $payolutionTransactionResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function checkForErrors(
        PayolutionTransactionResponseTransfer $payolutionTransactionResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        if (PayolutionConstants::REASON_CODE_SUCCESS !== $payolutionTransactionResponseTransfer->getProcessingReasonCode()
            || PayolutionConstants::STATUS_CODE_SUCCESS !== $payolutionTransactionResponseTransfer->getProcessingStatusCode()
            || PayolutionConstants::PAYMENT_CODE_PRE_CHECK !== $payolutionTransactionResponseTransfer->getPaymentCode()
        ) {
            $errorCode = (int)preg_replace('/[^\d]+/', '', $payolutionTransactionResponseTransfer->getProcessingCode());
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($errorCode)
                ->setMessage($payolutionTransactionResponseTransfer->getProcessingReturn());
            $checkoutResponseTransfer->addError($error);
            return false;
        }

        return true;
    }
}
