<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Shared\Payolution\PayolutionApiConstants;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * @method PayolutionFacade getFacade()
 */
class PreCheckPlugin extends BaseAbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return CheckoutResponseTransfer
     */
    public function checkCondition(
        CheckoutRequestTransfer $checkoutRequest,
        CheckoutResponseTransfer $checkoutResponse
    ) {
        $payolutionResponseTransfer = $this->getFacade()->preCheckPayment($checkoutRequest);

        if (PayolutionApiConstants::REASON_CODE_SUCCESS !== $payolutionResponseTransfer->getProcessingReasonCode()
            || PayolutionApiConstants::STATUS_CODE_SUCCESS !== $payolutionResponseTransfer->getProcessingStatusCode()
            || PayolutionApiConstants::PAYMENT_CODE_PRE_CHECK !== $payolutionResponseTransfer->getPaymentCode()
        ) {
            $errorCode = (int) preg_replace('/[^\d]+/', '', $payolutionResponseTransfer->getProcessingCode());
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($errorCode)
                ->setMessage($payolutionResponseTransfer->getProcessingReturn());
            $checkoutResponse->addError($error);
        }

        return $checkoutResponse;
    }

}
