<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 */
class PreCheckPlugin extends BaseAbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function checkCondition(
        CheckoutRequestTransfer $checkoutRequestTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $payolutionTransactionResponseTransfer = $this
            ->getFacade()
            ->preCheckPayment($checkoutRequestTransfer);

        $this->checkForErrors($payolutionTransactionResponseTransfer, $checkoutResponseTransfer);

        $checkoutRequestTransfer->getPayolutionPayment()
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
