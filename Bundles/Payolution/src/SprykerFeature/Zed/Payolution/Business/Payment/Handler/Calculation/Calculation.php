<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation;

use Generated\Shared\Payolution\PayolutionResponseInterface;
use Generated\Shared\Payolution\CheckoutRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;

class Calculation extends AbstractPaymentHandler implements CalculationInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionResponseInterface
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
     * @return PayolutionResponseInterface
     */
    protected function sendRequest($requestData)
    {
        $responseData = $this->executionAdapter->sendAuthorizedRequest(
            $requestData,
            $this->getConfig()->getCalculationUserLogin(),
            $this->getConfig()->getCalculationUserPassword());
        $responseTransfer = $this->responseConverter->fromArray($responseData);

        return $responseTransfer;
    }

}
