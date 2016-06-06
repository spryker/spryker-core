<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 */
class PayolutionPreCheckPlugin extends BaseAbstractPlugin implements CheckoutPreConditionInterface
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
        $payolutionTransactionResponseTransfer = $this->getFacade()->preCheckPayment($quoteTransfer);
        $this->checkForErrors($payolutionTransactionResponseTransfer, $checkoutResponseTransfer);
        $quoteTransfer->getPayment()->getPayolution()
            ->setPreCheckId($payolutionTransactionResponseTransfer->getIdentificationUniqueid());

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer $payolutionTransactionResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
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
        }
    }

}
