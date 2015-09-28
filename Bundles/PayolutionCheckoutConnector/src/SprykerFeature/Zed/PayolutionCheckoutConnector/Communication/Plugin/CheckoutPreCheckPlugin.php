<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;
use SprykerFeature\Zed\PayolutionCheckoutConnector\Communication\PayolutionCheckoutConnectorDependencyContainer;

/**
 * @method PayolutionCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutPreCheckPlugin extends AbstractPlugin implements CheckoutPreconditionInterface
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
        $payolutionFacade = $this->getDependencyContainer()->createPayolutionFacade();
        $payolutionResponseTransfer = $payolutionFacade->preCheckPayment($checkoutRequest);

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
