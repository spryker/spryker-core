<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation;

use Generated\Shared\Payolution\PayolutionCalculationResponseInterface;
use Generated\Shared\Payolution\CheckoutRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;

class Calculation extends AbstractPaymentHandler implements CalculationInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseInterface
     */
    public function calculateInstallmentPayments(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $paymentTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildCalculationRequest($checkoutRequestTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param string $requestData
     *
     * @return PayolutionCalculationResponseInterface
     */
    protected function sendRequest($requestData)
    {
        $calculationRequest = $this->converter->toCalculationRequest($requestData);
        $responseData = $this->executionAdapter->sendAuthorizedRequest(
            $calculationRequest,
            $this->getConfig()->getCalculationUserLogin(),
            $this->getConfig()->getCalculationUserPassword()
        );
        $responseTransfer = $this->converter->toCalculationResponseTransfer($responseData);

        return $responseTransfer;
    }

}
